<?php

namespace App\Services\Konseling;

use App\Models\BookingKonseling;
use App\Models\CatatanKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Models\NotifikasiSimulasi;
use App\Models\Rujukan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingKonselingService
{
    public function approve(BookingKonseling $booking): void
    {
        DB::transaction(function () use ($booking): void {
            $booking->refresh();

            if ($booking->status !== BookingKonseling::STATUS_DIAJUKAN) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya pengajuan berstatus Diajukan yang dapat disetujui.',
                ]);
            }

            $booking->loadMissing('jadwalKonseling.konselor.user', 'mahasiswa.user');

            $data = [
                'status' => BookingKonseling::STATUS_DIJADWALKAN,
                'konselor_id' => $booking->jadwalKonseling->konselor_id,
            ];

            if ($booking->metode === BookingKonseling::METODE_ONLINE && blank($booking->link_meeting)) {
                $data['link_meeting'] = $this->makeMeetingLink($booking->kode_booking);
            }

            $booking->forceFill($data)->save();

            $booking->jadwalKonseling->forceFill([
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ])->save();

            $this->recordNotification(
                $booking,
                $booking->mahasiswa->user,
                'booking_dijadwalkan',
                'Pengajuan konseling Anda telah disetujui dan dijadwalkan.'
            );

            $this->recordNotification(
                $booking,
                $booking->jadwalKonseling->konselor->user,
                'booking_dijadwalkan',
                'Anda mendapat jadwal konseling baru.'
            );
        });
    }

    public function cancel(BookingKonseling $booking, string $reason): void
    {
        DB::transaction(function () use ($booking, $reason): void {
            $booking->refresh();

            if (! in_array($booking->status, [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIJADWALKAN,
            ], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya booking berstatus Diajukan atau Dijadwalkan yang dapat dibatalkan.',
                ]);
            }

            if (blank($reason)) {
                throw ValidationException::withMessages([
                    'alasan_pembatalan' => 'Alasan pembatalan wajib diisi.',
                ]);
            }

            $booking->loadMissing('jadwalKonseling.konselor.user', 'mahasiswa.user');

            $booking->forceFill([
                'status' => BookingKonseling::STATUS_DIBATALKAN,
                'alasan_pembatalan' => $reason,
            ])->save();

            if (! $this->scheduleHasActiveBooking($booking)) {
                $booking->jadwalKonseling->forceFill([
                    'status' => JadwalKonseling::STATUS_TERSEDIA,
                ])->save();
            }

            $this->recordNotification(
                $booking,
                $booking->mahasiswa->user,
                'booking_dibatalkan',
                'Pengajuan konseling Anda dibatalkan. Alasan: ' . $reason
            );

            $this->recordNotification(
                $booking,
                $booking->jadwalKonseling->konselor->user,
                'booking_dibatalkan',
                'Jadwal konseling dibatalkan. Alasan: ' . $reason
            );
        });
    }

    /**
     * @param  array{catatan_hasil: string, rekomendasi: string}  $data
     */
    public function saveCounselingNote(BookingKonseling $booking, array $data, Konselor $konselor): CatatanKonseling
    {
        return DB::transaction(function () use ($booking, $data, $konselor): CatatanKonseling {
            $booking->refresh();
            $this->ensureAssignedCounselor($booking, $konselor);

            if (in_array($booking->status, [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIBATALKAN,
            ], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Catatan hanya dapat diisi untuk booking yang sudah dijadwalkan.',
                ]);
            }

            return CatatanKonseling::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'konselor_id' => $konselor->id,
                    'catatan_hasil' => $data['catatan_hasil'],
                    'rekomendasi' => $data['rekomendasi'],
                ],
            );
        });
    }

    /**
     * @param  array{catatan_hasil: string, rekomendasi: string}  $data
     */
    public function completeCounseling(BookingKonseling $booking, array $data, Konselor $konselor): CatatanKonseling
    {
        return DB::transaction(function () use ($booking, $data, $konselor): CatatanKonseling {
            $booking->refresh();
            $this->ensureAssignedCounselor($booking, $konselor);

            if ($booking->status !== BookingKonseling::STATUS_DIJADWALKAN) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya booking berstatus Dijadwalkan yang dapat ditandai selesai.',
                ]);
            }

            $note = CatatanKonseling::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'konselor_id' => $konselor->id,
                    'catatan_hasil' => $data['catatan_hasil'],
                    'rekomendasi' => $data['rekomendasi'],
                ],
            );

            $booking->forceFill([
                'status' => BookingKonseling::STATUS_SELESAI,
            ])->save();

            $booking->loadMissing('mahasiswa.user');

            $this->recordNotification(
                $booking,
                $booking->mahasiswa->user,
                'booking_selesai',
                'Sesi konseling Anda telah selesai.'
            );

            return $note;
        });
    }

    /**
     * @param  array{catatan_hasil: string, rekomendasi: string}  $noteData
     * @param  array{tujuan_rujukan: string, alasan_rujukan: string, ringkasan_tindak_lanjut?: string|null}  $referralData
     */
    public function referCounseling(BookingKonseling $booking, array $noteData, array $referralData, Konselor $konselor, User $creator): Rujukan
    {
        return DB::transaction(function () use ($booking, $noteData, $referralData, $konselor, $creator): Rujukan {
            $booking->refresh();
            $this->ensureAssignedCounselor($booking, $konselor);

            if ($booking->status !== BookingKonseling::STATUS_DIJADWALKAN) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya booking berstatus Dijadwalkan yang dapat dirujuk oleh konselor.',
                ]);
            }

            CatatanKonseling::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'konselor_id' => $konselor->id,
                    'catatan_hasil' => $noteData['catatan_hasil'],
                    'rekomendasi' => $noteData['rekomendasi'],
                ],
            );

            return $this->refer($booking, $referralData, $creator);
        });
    }

    /**
     * @param  array{tujuan_rujukan: string, alasan_rujukan: string, ringkasan_tindak_lanjut?: string|null}  $data
     */
    public function refer(BookingKonseling $booking, array $data, User $creator): Rujukan
    {
        return DB::transaction(function () use ($booking, $data, $creator): Rujukan {
            $booking->refresh();

            if ($booking->status === BookingKonseling::STATUS_DIBATALKAN) {
                throw ValidationException::withMessages([
                    'booking_id' => 'Booking yang dibatalkan tidak dapat dirujuk.',
                ]);
            }

            $rujukan = Rujukan::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'tujuan_rujukan' => $data['tujuan_rujukan'],
                    'alasan_rujukan' => $data['alasan_rujukan'],
                    'ringkasan_tindak_lanjut' => $data['ringkasan_tindak_lanjut'] ?? null,
                    'dibuat_oleh' => $creator->id,
                ],
            );

            $booking->forceFill([
                'status' => BookingKonseling::STATUS_DIRUJUK,
            ])->save();

            User::role('admin_bkts')
                ->where('status', User::STATUS_AKTIF)
                ->get()
                ->each(fn (User $admin): mixed => $this->recordNotification(
                    $booking,
                    $admin,
                    'booking_dirujuk',
                    'Mahasiswa membutuhkan tindak lanjut rujukan.'
                ));

            return $rujukan;
        });
    }

    private function makeMeetingLink(string $bookingCode): string
    {
        $code = str_starts_with($bookingCode, 'BKTS-') ? $bookingCode : 'BKTS-' . $bookingCode;

        return 'https://meet.mock/' . $code;
    }

    private function recordNotification(BookingKonseling $booking, ?User $recipient, string $type, string $message): void
    {
        if ($recipient === null) {
            return;
        }

        NotifikasiSimulasi::create([
            'booking_id' => $booking->id,
            'penerima_id' => $recipient->id,
            'jenis' => $type,
            'pesan' => $message,
            'channel' => NotifikasiSimulasi::CHANNEL_SISTEM,
            'status' => NotifikasiSimulasi::STATUS_TERCATAT,
        ]);
    }

    private function ensureAssignedCounselor(BookingKonseling $booking, Konselor $konselor): void
    {
        if ($booking->konselor_id !== $konselor->id) {
            throw ValidationException::withMessages([
                'booking_id' => 'Konselor hanya dapat menangani booking yang ditugaskan kepadanya.',
            ]);
        }
    }

    private function scheduleHasActiveBooking(BookingKonseling $booking): bool
    {
        return BookingKonseling::query()
            ->where('jadwal_id', $booking->jadwal_id)
            ->whereKeyNot($booking->id)
            ->whereIn('status', [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIJADWALKAN,
                BookingKonseling::STATUS_SELESAI,
                BookingKonseling::STATUS_DIRUJUK,
            ])
            ->exists();
    }
}
