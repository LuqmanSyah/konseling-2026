<?php

namespace Database\Seeders;

use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class BookingKonselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = Mahasiswa::query()->where('email', 'mahasiswa@admin.com')->firstOrFail();

        foreach ($this->bookings() as $booking) {
            $jadwal = $this->schedule($booking['hari'], $booking['jam_mulai']);

            BookingKonseling::updateOrCreate(
                ['kode_booking' => $booking['kode_booking']],
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'jadwal_id' => $jadwal->id,
                    'konselor_id' => $jadwal->konselor_id,
                    'kategori' => $booking['kategori'],
                    'metode' => $jadwal->metode,
                    'keluhan_awal' => $booking['keluhan_awal'],
                    'status' => $booking['status'],
                    'link_meeting' => $booking['link_meeting'] ?? null,
                    'alasan_pembatalan' => $booking['alasan_pembatalan'] ?? null,
                ],
            );
        }
    }

    private function schedule(string $hari, string $jamMulai): JadwalKonseling
    {
        return JadwalKonseling::query()
            ->where('hari', $hari)
            ->whereTime('jam_mulai', $jamMulai)
            ->firstOrFail();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function bookings(): array
    {
        return [
            [
                'kode_booking' => 'BKTS-DEMO-0001',
                'hari' => JadwalKonseling::HARI_SELASA,
                'jam_mulai' => '10:00:00',
                'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
                'status' => BookingKonseling::STATUS_DIAJUKAN,
                'keluhan_awal' => 'Kesulitan mengatur prioritas tugas dan persiapan ujian.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0002',
                'hari' => JadwalKonseling::HARI_RABU,
                'jam_mulai' => '13:00:00',
                'kategori' => BookingKonseling::KATEGORI_NON_AKADEMIK,
                'status' => BookingKonseling::STATUS_DIJADWALKAN,
                'keluhan_awal' => 'Membutuhkan sesi konseling untuk adaptasi lingkungan kampus.',
                'link_meeting' => 'https://meet.mock/BKTS-DEMO-0002',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0003',
                'hari' => JadwalKonseling::HARI_KAMIS,
                'jam_mulai' => '08:00:00',
                'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
                'status' => BookingKonseling::STATUS_SELESAI,
                'keluhan_awal' => 'Konsultasi strategi belajar dan manajemen waktu.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0004',
                'hari' => JadwalKonseling::HARI_JUMAT,
                'jam_mulai' => '11:00:00',
                'kategori' => BookingKonseling::KATEGORI_NON_AKADEMIK,
                'status' => BookingKonseling::STATUS_DIRUJUK,
                'keluhan_awal' => 'Membutuhkan pendampingan lanjutan terkait tekanan personal.',
                'link_meeting' => 'https://meet.mock/BKTS-DEMO-0004',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0005',
                'hari' => JadwalKonseling::HARI_SABTU,
                'jam_mulai' => '15:00:00',
                'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
                'status' => BookingKonseling::STATUS_DIBATALKAN,
                'keluhan_awal' => 'Pengajuan awal untuk konsultasi akademik.',
                'alasan_pembatalan' => 'Mahasiswa memilih jadwal ulang.',
            ],
        ];
    }
}
