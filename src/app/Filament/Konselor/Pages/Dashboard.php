<?php

namespace App\Filament\Konselor\Pages;

use App\Filament\Konselor\Resources\BookingKonselingResource;
use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard Konselor';

    protected static string $view = 'filament.konselor.pages.dashboard';

    /**
     * @return array<int, array{label: string, value: int, icon: string, color: string}>
     */
    public function getStats(): array
    {
        $konselor = $this->getKonselor();

        if ($konselor === null) {
            return [];
        }

        return [
            [
                'label' => 'Slot Mingguan',
                'value' => JadwalKonseling::query()
                    ->where('konselor_id', $konselor->id)
                    ->count(),
                'icon' => 'heroicon-o-calendar-days',
                'color' => 'text-teal-600',
            ],
            [
                'label' => 'Sesi Dijadwalkan',
                'value' => $this->bookingQuery($konselor)
                    ->where('status', BookingKonseling::STATUS_DIJADWALKAN)
                    ->count(),
                'icon' => 'heroicon-o-clock',
                'color' => 'text-blue-600',
            ],
            [
                'label' => 'Belum Ada Catatan',
                'value' => $this->bookingQuery($konselor)
                    ->where('status', BookingKonseling::STATUS_DIJADWALKAN)
                    ->whereDoesntHave('catatanKonseling')
                    ->count(),
                'icon' => 'heroicon-o-pencil-square',
                'color' => 'text-amber-600',
            ],
            [
                'label' => 'Sesi Selesai',
                'value' => $this->bookingQuery($konselor)
                    ->where('status', BookingKonseling::STATUS_SELESAI)
                    ->count(),
                'icon' => 'heroicon-o-check-circle',
                'color' => 'text-emerald-600',
            ],
            [
                'label' => 'Sesi Dirujuk',
                'value' => $this->bookingQuery($konselor)
                    ->where('status', BookingKonseling::STATUS_DIRUJUK)
                    ->count(),
                'icon' => 'heroicon-o-arrow-top-right-on-square',
                'color' => 'text-violet-600',
            ],
        ];
    }

    /**
     * @return Collection<int, BookingKonseling>
     */
    public function getUpcomingBookings(): Collection
    {
        $konselor = $this->getKonselor();

        if ($konselor === null) {
            return new Collection;
        }

        return $this->bookingQuery($konselor)
            ->with(['mahasiswa', 'jadwalKonseling'])
            ->where('booking_konseling.status', BookingKonseling::STATUS_DIJADWALKAN)
            ->join('jadwal_konseling', 'booking_konseling.jadwal_id', '=', 'jadwal_konseling.id')
            ->orderByRaw(JadwalKonseling::hariOrderCase('jadwal_konseling.hari'))
            ->orderBy('jadwal_konseling.jam_mulai')
            ->select('booking_konseling.*')
            ->limit(5)
            ->get();
    }

    /**
     * @return Collection<int, BookingKonseling>
     */
    public function getRecentSessions(): Collection
    {
        $konselor = $this->getKonselor();

        if ($konselor === null) {
            return new Collection;
        }

        return $this->bookingQuery($konselor)
            ->with(['mahasiswa', 'jadwalKonseling'])
            ->whereIn('status', [
                BookingKonseling::STATUS_SELESAI,
                BookingKonseling::STATUS_DIRUJUK,
            ])
            ->latest('updated_at')
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
                'label' => 'Jadwal Saya',
                'icon' => 'heroicon-o-calendar-days',
                'url' => BookingKonselingResource::getUrl('index'),
            ],
            [
                'label' => 'Riwayat Sesi',
                'icon' => 'heroicon-o-clipboard-document-list',
                'url' => BookingKonselingResource::getUrl('index', ['tableFilters[status][value]' => BookingKonseling::STATUS_SELESAI]),
            ],
        ];
    }

    private function getKonselor(): ?Konselor
    {
        return auth()->user()?->konselor;
    }

    private function bookingQuery(Konselor $konselor): \Illuminate\Database\Eloquent\Builder
    {
        return BookingKonseling::query()
            ->where('booking_konseling.konselor_id', $konselor->id);
    }
}
