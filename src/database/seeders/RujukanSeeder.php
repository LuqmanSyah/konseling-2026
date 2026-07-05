<?php

namespace Database\Seeders;

use App\Models\BookingKonseling;
use App\Models\Rujukan;
use App\Models\User;
use Illuminate\Database\Seeder;

class RujukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminBkts = User::query()->where('email', 'bkts@admin.com')->firstOrFail();
        $booking = BookingKonseling::query()->where('kode_booking', 'BKTS-DEMO-0004')->firstOrFail();

        Rujukan::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'tujuan_rujukan' => 'Psikolog Kampus',
                'alasan_rujukan' => 'Perlu asesmen dan pendampingan lanjutan di luar sesi konseling awal.',
                'ringkasan_tindak_lanjut' => 'Admin BKTS menghubungi mahasiswa untuk konfirmasi jadwal rujukan.',
                'dibuat_oleh' => $adminBkts->id,
            ],
        );
    }
}
