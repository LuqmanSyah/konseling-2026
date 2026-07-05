<?php

namespace App\Filament\Bkts\Resources\JadwalKonselingResource\Pages;

use App\Filament\Bkts\Resources\JadwalKonselingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalKonseling extends CreateRecord
{
    protected static string $resource = JadwalKonselingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
