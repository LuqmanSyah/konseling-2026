<?php

namespace Database\Seeders;

use App\Models\JadwalKonseling;
use App\Models\Konselor;
use Illuminate\Database\Seeder;

class JadwalKonselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $konselor = Konselor::query()->where('email', 'konselor@admin.com')->firstOrFail();

        foreach ($this->schedules($konselor->id) as $schedule) {
            JadwalKonseling::updateOrCreate(
                [
                    'konselor_id' => $schedule['konselor_id'],
                    'tanggal' => $schedule['tanggal'],
                    'jam_mulai' => $schedule['jam_mulai'],
                ],
                [
                    'jam_selesai' => $schedule['jam_selesai'],
                    'metode' => $schedule['metode'],
                    'status' => $schedule['status'],
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function schedules(int $konselorId): array
    {
        return [
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->addDays(1)->toDateString(),
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '10:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERSEDIA,
            ],
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->addDays(2)->toDateString(),
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->addDays(3)->toDateString(),
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->subDays(3)->toDateString(),
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->subDays(2)->toDateString(),
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '12:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'tanggal' => now()->addDays(4)->toDateString(),
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '16:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERSEDIA,
            ],
        ];
    }
}
