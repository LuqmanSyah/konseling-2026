<?php

namespace App\Filament\Mahasiswa\Resources\BookingKonselingResource\Pages;

use App\Filament\Mahasiswa\Resources\BookingKonselingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingKonselings extends ListRecords
{
    protected static string $resource = BookingKonselingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajukan Konseling'),
        ];
    }
}
