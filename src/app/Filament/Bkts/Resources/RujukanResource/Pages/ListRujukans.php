<?php

namespace App\Filament\Bkts\Resources\RujukanResource\Pages;

use App\Filament\Bkts\Resources\RujukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRujukans extends ListRecords
{
    protected static string $resource = RujukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
