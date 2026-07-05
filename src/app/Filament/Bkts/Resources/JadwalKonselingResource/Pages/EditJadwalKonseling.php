<?php

namespace App\Filament\Bkts\Resources\JadwalKonselingResource\Pages;

use App\Filament\Bkts\Resources\JadwalKonselingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKonseling extends EditRecord
{
    protected static string $resource = JadwalKonselingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
