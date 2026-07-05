<?php

namespace App\Filament\Bkts\Resources\RujukanResource\Pages;

use App\Filament\Bkts\Resources\RujukanResource;
use App\Models\BookingKonseling;
use App\Models\Rujukan;
use App\Services\Konseling\BookingKonselingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRujukan extends CreateRecord
{
    protected static string $resource = RujukanResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $booking = BookingKonseling::query()->findOrFail($data['booking_id']);

        return app(BookingKonselingService::class)->refer($booking, $data, auth()->user());
    }

    protected function getRedirectUrl(): string
    {
        /** @var Rujukan $record */
        $record = $this->record;

        return $this->getResource()::getUrl('view', ['record' => $record]);
    }
}
