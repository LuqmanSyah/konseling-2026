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
                    'hari' => $schedule['hari'],
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
                'hari' => JadwalKonseling::HARI_SENIN,
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '10:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERSEDIA,
            ],
            [
                'konselor_id' => $konselorId,
                'hari' => JadwalKonseling::HARI_SELASA,
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '11:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'hari' => JadwalKonseling::HARI_RABU,
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '14:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'hari' => JadwalKonseling::HARI_KAMIS,
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'hari' => JadwalKonseling::HARI_JUMAT,
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '12:00:00',
                'metode' => JadwalKonseling::METODE_ONLINE,
                'status' => JadwalKonseling::STATUS_TERPAKAI,
            ],
            [
                'konselor_id' => $konselorId,
                'hari' => JadwalKonseling::HARI_SABTU,
                'jam_mulai' => '15:00:00',
                'jam_selesai' => '16:00:00',
                'metode' => JadwalKonseling::METODE_TATAP_MUKA,
                'status' => JadwalKonseling::STATUS_TERSEDIA,
            ],
        ];
    }
}
