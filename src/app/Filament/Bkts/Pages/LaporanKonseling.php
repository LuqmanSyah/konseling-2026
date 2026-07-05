<?php

namespace App\Filament\Bkts\Pages;

use App\Models\BookingKonseling;
use App\Support\KonselingOptions;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LaporanKonseling extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Operasional BKTS';

    protected static ?string $navigationLabel = 'Laporan Dasar';

    protected static ?string $title = 'Laporan Dasar Layanan Konseling';

    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.bkts.pages.laporan-konseling';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public ?string $status = null;

    /**
     * @return array<string, string>
     */
    public function getStatusOptions(): array
    {
        return KonselingOptions::bookingStatuses();
    }

    /**
     * @return array<string, int>
     */
    public function getStatusSummary(): array
    {
        return $this->baseQuery()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->mapWithKeys(fn (int $count, string $status): array => [
                KonselingOptions::bookingStatuses()[$status] ?? $status => $count,
            ])
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function getCategorySummary(): array
    {
        return $this->baseQuery()
            ->selectRaw('kategori, count(*) as aggregate')
            ->groupBy('kategori')
            ->pluck('aggregate', 'kategori')
            ->mapWithKeys(fn (int $count, string $kategori): array => [
                KonselingOptions::kategoriKonseling()[$kategori] ?? $kategori => $count,
            ])
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function getMethodSummary(): array
    {
        return $this->baseQuery()
            ->selectRaw('metode, count(*) as aggregate')
            ->groupBy('metode')
            ->pluck('aggregate', 'metode')
            ->mapWithKeys(fn (int $count, string $metode): array => [
                KonselingOptions::metodeKonseling()[$metode] ?? $metode => $count,
            ])
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function getCounselorSummary(): array
    {
        return $this->baseQuery()
            ->join('konselor', 'booking_konseling.konselor_id', '=', 'konselor.id')
            ->selectRaw('konselor.nama as nama, count(*) as aggregate')
            ->groupBy('konselor.nama')
            ->pluck('aggregate', 'nama')
            ->all();
    }

    /**
     * @return Collection<int, BookingKonseling>
     */
    public function getRows(): Collection
    {
        return $this->baseQuery()
            ->with(['mahasiswa', 'konselor', 'jadwalKonseling'])
            ->latest('booking_konseling.created_at')
            ->limit(50)
            ->get();
    }

    private function baseQuery(): Builder
    {
        return BookingKonseling::query()
            ->when($this->startDate, fn (Builder $query): Builder => $query->whereDate('booking_konseling.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn (Builder $query): Builder => $query->whereDate('booking_konseling.created_at', '<=', $this->endDate))
            ->when($this->status, fn (Builder $query): Builder => $query->where('booking_konseling.status', $this->status));
    }
}
