<?php

namespace Database\Seeders;

use App\Models\BookingKonseling;
use App\Models\NotifikasiSimulasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSimulasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminBkts = User::query()->where('email', 'bkts@admin.com')->firstOrFail();
        $konselor = User::query()->where('email', 'konselor@admin.com')->firstOrFail();
        $mahasiswa = User::query()->where('email', 'mahasiswa@admin.com')->firstOrFail();

        foreach ($this->notifications($adminBkts, $konselor, $mahasiswa) as $notification) {
            $booking = BookingKonseling::query()
                ->where('kode_booking', $notification['kode_booking'])
                ->firstOrFail();

            NotifikasiSimulasi::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'penerima_id' => $notification['recipient']->id,
                    'jenis' => $notification['jenis'],
                ],
                [
                    'pesan' => $notification['pesan'],
                    'channel' => NotifikasiSimulasi::CHANNEL_SISTEM,
                    'status' => NotifikasiSimulasi::STATUS_TERCATAT,
                ],
            );
        }
    }

    /**
     * @return array<int, array{kode_booking: string, recipient: User, jenis: string, pesan: string}>
     */
    private function notifications(User $adminBkts, User $konselor, User $mahasiswa): array
    {
        return [
            [
                'kode_booking' => 'BKTS-DEMO-0001',
                'recipient' => $mahasiswa,
                'jenis' => 'pengajuan_dibuat',
                'pesan' => 'Pengajuan konseling berhasil dibuat dan menunggu verifikasi Admin BKTS.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0002',
                'recipient' => $mahasiswa,
                'jenis' => 'booking_dijadwalkan',
                'pesan' => 'Pengajuan konseling Anda telah disetujui dan dijadwalkan.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0002',
                'recipient' => $konselor,
                'jenis' => 'booking_dijadwalkan',
                'pesan' => 'Anda mendapat jadwal konseling baru.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0003',
                'recipient' => $mahasiswa,
                'jenis' => 'booking_selesai',
                'pesan' => 'Sesi konseling Anda telah selesai.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0004',
                'recipient' => $adminBkts,
                'jenis' => 'booking_dirujuk',
                'pesan' => 'Mahasiswa membutuhkan tindak lanjut rujukan.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0005',
                'recipient' => $mahasiswa,
                'jenis' => 'booking_dibatalkan',
                'pesan' => 'Pengajuan konseling Anda dibatalkan. Alasan: Mahasiswa memilih jadwal ulang.',
            ],
        ];
    }
}
