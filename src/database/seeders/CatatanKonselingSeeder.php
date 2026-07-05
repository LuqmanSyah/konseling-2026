<?php

namespace Database\Seeders;

use App\Models\BookingKonseling;
use App\Models\CatatanKonseling;
use App\Models\Konselor;
use Illuminate\Database\Seeder;

class CatatanKonselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $konselor = Konselor::query()->where('email', 'konselor@admin.com')->firstOrFail();

        foreach ($this->notes() as $note) {
            $booking = BookingKonseling::query()
                ->where('kode_booking', $note['kode_booking'])
                ->firstOrFail();

            CatatanKonseling::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'konselor_id' => $konselor->id,
                    'catatan_hasil' => $note['catatan_hasil'],
                    'rekomendasi' => $note['rekomendasi'],
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function notes(): array
    {
        return [
            [
                'kode_booking' => 'BKTS-DEMO-0003',
                'catatan_hasil' => 'Mahasiswa dapat mengidentifikasi hambatan belajar utama dan menyusun prioritas mingguan.',
                'rekomendasi' => 'Gunakan jadwal belajar bertahap dan evaluasi progres setiap akhir pekan.',
            ],
            [
                'kode_booking' => 'BKTS-DEMO-0004',
                'catatan_hasil' => 'Mahasiswa membutuhkan dukungan lanjutan karena tekanan personal berdampak pada aktivitas akademik.',
                'rekomendasi' => 'Direkomendasikan tindak lanjut dengan psikolog kampus.',
            ],
        ];
    }
}
