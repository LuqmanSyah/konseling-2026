<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Filament\Mahasiswa\Resources\BookingKonselingResource;
use App\Models\BookingKonseling;
use App\Models\Mahasiswa;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard Mahasiswa';

    protected static string $view = 'filament.mahasiswa.pages.dashboard';

    /**
     * @return array<int, array{label: string, value: int, icon: string, color: string}>
     */
    public function getStats(): array
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa === null) {
            return [];
        }

        return [
            [
                'label' => 'Total Pengajuan',
                'value' => $this->bookingQuery($mahasiswa)->count(),
                'icon' => 'heroicon-o-clipboard-document-list',
                'color' => 'text-indigo-600',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => $this->bookingQuery($mahasiswa)->where('status', BookingKonseling::STATUS_DIAJUKAN)->count(),
                'icon' => 'heroicon-o-clock',
                'color' => 'text-amber-600',
            ],
            [
                'label' => 'Dijadwalkan',
                'value' => $this->bookingQuery($mahasiswa)->where('status', BookingKonseling::STATUS_DIJADWALKAN)->count(),
                'icon' => 'heroicon-o-calendar-days',
                'color' => 'text-blue-600',
            ],
            [
                'label' => 'Selesai',
                'value' => $this->bookingQuery($mahasiswa)->where('status', BookingKonseling::STATUS_SELESAI)->count(),
                'icon' => 'heroicon-o-check-circle',
                'color' => 'text-emerald-600',
            ],
        ];
    }

    public function getLatestBooking(): ?BookingKonseling
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa === null) {
            return null;
        }

        return $this->bookingQuery($mahasiswa)
            ->with(['konselor', 'jadwalKonseling'])
            ->latest()
            ->first();
    }

    public function getActiveBooking(): ?BookingKonseling
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa === null) {
            return null;
        }

        return $this->bookingQuery($mahasiswa)
            ->with(['konselor', 'jadwalKonseling'])
            ->whereIn('booking_konseling.status', [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIJADWALKAN,
            ])
            ->join('jadwal_konseling', 'booking_konseling.jadwal_id', '=', 'jadwal_konseling.id')
            ->orderBy('jadwal_konseling.tanggal')
            ->orderBy('jadwal_konseling.jam_mulai')
            ->select('booking_konseling.*')
            ->first();
    }

    /**
     * @return Collection<int, BookingKonseling>
     */
    public function getRecentBookings(): Collection
    {
        $mahasiswa = $this->getMahasiswa();

        if ($mahasiswa === null) {
            return new Collection;
        }

        return $this->bookingQuery($mahasiswa)
            ->with(['konselor', 'jadwalKonseling'])
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
                'label' => 'Ajukan Konseling',
                'icon' => 'heroicon-o-plus-circle',
                'url' => BookingKonselingResource::getUrl('create'),
            ],
            [
                'label' => 'Riwayat Pengajuan',
                'icon' => 'heroicon-o-clipboard-document-check',
                'url' => BookingKonselingResource::getUrl('index'),
            ],
        ];
    }

    private function getMahasiswa(): ?Mahasiswa
    {
        return auth()->user()?->mahasiswa;
    }

    private function bookingQuery(Mahasiswa $mahasiswa): Builder
    {
        return BookingKonseling::query()
            ->where('booking_konseling.mahasiswa_id', $mahasiswa->id);
    }
}
