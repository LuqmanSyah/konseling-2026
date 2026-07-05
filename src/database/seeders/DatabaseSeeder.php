<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            JadwalKonselingSeeder::class,
            BookingKonselingSeeder::class,
            CatatanKonselingSeeder::class,
            RujukanSeeder::class,
            NotifikasiSimulasiSeeder::class,
        ]);
    }
}
