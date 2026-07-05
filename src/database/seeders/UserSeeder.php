<?php

namespace Database\Seeders;

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
            ['name' => $name, 'password' => Hash::make('password')]
        );

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }
    }
}
