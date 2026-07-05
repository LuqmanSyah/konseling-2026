<?php

namespace Database\Seeders;

use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createUser('admin@admin.com', 'Super Admin', 'super_admin');
        $this->createUser('bkts@admin.com', 'Admin BKTS', 'admin_bkts');
        $this->createUser('konselor@admin.com', 'Konselor', 'konselor');
        $this->createUser('mahasiswa@admin.com', 'Mahasiswa', 'mahasiswa');
    }

    private function createUser(string $email, string $name, string $role): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'status' => User::STATUS_AKTIF,
            ]
        );

        if ($user->status !== User::STATUS_AKTIF) {
            $user->forceFill(['status' => User::STATUS_AKTIF])->save();
        }

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        match ($role) {
            'konselor' => Konselor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $name,
                    'bidang' => 'Konseling Umum',
                    'no_hp' => '080000000003',
                    'email' => $email,
                    'status' => Konselor::STATUS_AKTIF,
                ],
            ),
            'mahasiswa' => Mahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => 'MHS0001',
                    'nama' => $name,
                    'program_studi' => 'Program Studi Demo',
                    'angkatan' => '2026',
                    'no_hp' => '080000000004',
                    'email' => $email,
                ],
            ),
            default => null,
        };
    }
}
