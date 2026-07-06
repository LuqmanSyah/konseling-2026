<?php

namespace App\Filament\Bkts\Pages;

use App\Filament\Bkts\Resources\BookingKonselingResource;
use App\Filament\Bkts\Resources\JadwalKonselingResource;
use App\Filament\Bkts\Resources\NotifikasiSimulasiResource;
use App\Filament\Bkts\Resources\RujukanResource;
use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard Admin BKTS';

    protected static string $view = 'filament.bkts.pages.dashboard';

    /**
     * @return array<int, array{label: string, value: int, icon: string, color: string}>
     */
    public function getStats(): array
    {
        return [
            [
                'label' => 'Total Pengajuan',
                'value' => BookingKonseling::query()->count(),
                'icon' => 'heroicon-o-clipboard-document-list',
                'color' => 'text-sky-600',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => BookingKonseling::query()->where('status', BookingKonseling::STATUS_DIAJUKAN)->count(),
                'icon' => 'heroicon-o-clock',
                'color' => 'text-amber-600',
            ],
            [
                'label' => 'Dijadwalkan',
                'value' => BookingKonseling::query()->where('status', BookingKonseling::STATUS_DIJADWALKAN)->count(),
                'icon' => 'heroicon-o-calendar-days',
                'color' => 'text-blue-600',
            ],
            [
                'label' => 'Selesai',
                'value' => BookingKonseling::query()->where('status', BookingKonseling::STATUS_SELESAI)->count(),
                'icon' => 'heroicon-o-check-circle',
                'color' => 'text-emerald-600',
            ],
            [
                'label' => 'Dibatalkan',
                'value' => BookingKonseling::query()->where('status', BookingKonseling::STATUS_DIBATALKAN)->count(),
                'icon' => 'heroicon-o-x-circle',
                'color' => 'text-rose-600',
            ],
            [
                'label' => 'Dirujuk',
                'value' => BookingKonseling::query()->where('status', BookingKonseling::STATUS_DIRUJUK)->count(),
                'icon' => 'heroicon-o-arrow-top-right-on-square',
                'color' => 'text-violet-600',
            ],
        ];
    }

    /**
     * @return Collection<int, JadwalKonseling>
     */
    public function getUpcomingSchedules(): Collection
    {
        return JadwalKonseling::query()
            ->with('konselor')
            ->orderByRaw(JadwalKonseling::hariOrderCase())
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();
    }

    /**
     * @return Collection<int, BookingKonseling>
     */
    public function getPendingBookings(): Collection
    {
        return BookingKonseling::query()
            ->with(['mahasiswa', 'jadwalKonseling'])
            ->where('status', BookingKonseling::STATUS_DIAJUKAN)
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * @return array<int, array{label: string, icon: string, url: string}>
     */
    public function getShortcuts(): array
    {
        return [
            [
                'label' => 'Jadwal Konseling',
                'icon' => 'heroicon-o-calendar-days',
                'url' => JadwalKonselingResource::getUrl('index'),
            ],
            [
                'label' => 'Pengajuan Konseling',
                'icon' => 'heroicon-o-clipboard-document-check',
                'url' => BookingKonselingResource::getUrl('index'),
            ],
            [
                'label' => 'Rujukan',
                'icon' => 'heroicon-o-arrow-top-right-on-square',
                'url' => RujukanResource::getUrl('index'),
            ],
            [
                'label' => 'Notifikasi Simulasi',
                'icon' => 'heroicon-o-bell-alert',
                'url' => NotifikasiSimulasiResource::getUrl('index'),
            ],
            [
                'label' => 'Laporan Dasar',
                'icon' => 'heroicon-o-chart-bar',
                'url' => LaporanKonseling::getUrl(),
            ],
        ];
    }
}
