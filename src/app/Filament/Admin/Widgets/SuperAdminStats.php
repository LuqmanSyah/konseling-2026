<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;

class SuperAdminStats extends StatsOverviewWidget
{
    protected static ?int $sort = -20;

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::query()->count())
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Jumlah Role', Role::query()->count())
                ->icon('heroicon-o-shield-check')
                ->color('info'),
            Stat::make('Akun Aktif', User::query()->where('status', User::STATUS_AKTIF)->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Akun Nonaktif', User::query()->where('status', User::STATUS_NONAKTIF)->count())
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
            Stat::make('Konselor Aktif', Konselor::query()->where('status', Konselor::STATUS_AKTIF)->count())
                ->icon('heroicon-o-user')
                ->color('success'),
            Stat::make('Mahasiswa', Mahasiswa::query()->count())
                ->icon('heroicon-o-academic-cap')
                ->color('warning'),
        ];
    }
}
