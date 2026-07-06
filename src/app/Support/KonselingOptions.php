<?php

namespace App\Support;

use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Models\User;

class KonselingOptions
{
    /**
     * @return array<string, string>
     */
    public static function roles(): array
    {
        return [
            'super_admin' => 'Super Admin',
            'admin_bkts' => 'Admin BKTS',
            'konselor' => 'Konselor',
            'mahasiswa' => 'Mahasiswa',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function userStatuses(): array
    {
        return [
            User::STATUS_AKTIF => 'Aktif',
            User::STATUS_NONAKTIF => 'Nonaktif',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function konselorStatuses(): array
    {
        return [
            Konselor::STATUS_AKTIF => 'Aktif',
            Konselor::STATUS_NONAKTIF => 'Nonaktif',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function kategoriKonseling(): array
    {
        return [
            BookingKonseling::KATEGORI_AKADEMIK => 'Akademik',
            BookingKonseling::KATEGORI_NON_AKADEMIK => 'Non-Akademik',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function metodeKonseling(): array
    {
        return [
            BookingKonseling::METODE_ONLINE => 'Online',
            BookingKonseling::METODE_TATAP_MUKA => 'Tatap Muka',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function hariDalamMinggu(): array
    {
        return [
            JadwalKonseling::HARI_SENIN => 'Senin',
            JadwalKonseling::HARI_SELASA => 'Selasa',
            JadwalKonseling::HARI_RABU => 'Rabu',
            JadwalKonseling::HARI_KAMIS => 'Kamis',
            JadwalKonseling::HARI_JUMAT => 'Jumat',
            JadwalKonseling::HARI_SABTU => 'Sabtu',
            JadwalKonseling::HARI_MINGGU => 'Minggu',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function jadwalStatuses(): array
    {
        return [
            JadwalKonseling::STATUS_TERSEDIA => 'Tersedia',
            JadwalKonseling::STATUS_TERPAKAI => 'Terpakai',
            JadwalKonseling::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function bookingStatuses(): array
    {
        return [
            BookingKonseling::STATUS_DIAJUKAN => 'Diajukan',
            BookingKonseling::STATUS_DIJADWALKAN => 'Dijadwalkan',
            BookingKonseling::STATUS_SELESAI => 'Selesai',
            BookingKonseling::STATUS_DIRUJUK => 'Dirujuk',
            BookingKonseling::STATUS_DIBATALKAN => 'Dibatalkan',
        ];
    }
}
