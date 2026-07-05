<?php

namespace App\Filament\Bkts\Resources\JadwalKonselingResource\Pages;

use App\Filament\Bkts\Resources\JadwalKonselingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalKonseling extends ViewRecord
{
    protected static string $resource = JadwalKonselingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
