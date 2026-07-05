<?php

namespace App\Filament\Bkts\Resources\JadwalKonselingResource\Pages;

use App\Filament\Bkts\Resources\JadwalKonselingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalKonselings extends ListRecords
{
    protected static string $resource = JadwalKonselingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
