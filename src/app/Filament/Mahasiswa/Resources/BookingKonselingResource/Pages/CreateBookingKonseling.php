<?php

namespace App\Filament\Mahasiswa\Resources\BookingKonselingResource\Pages;

use App\Filament\Mahasiswa\Resources\BookingKonselingResource;
use App\Services\Konseling\BookingKonselingService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBookingKonseling extends CreateRecord
{
    protected static string $resource = BookingKonselingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $mahasiswa = auth()->user()?->mahasiswa;

        abort_if($mahasiswa === null, 403);

        return app(BookingKonselingService::class)->submitFromStudent($mahasiswa, $data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->record,
        ]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pengajuan konseling berhasil dibuat';
    }
}
