<?php

namespace App\Filament\Bkts\Resources\BookingKonselingResource\Pages;

use App\Filament\Bkts\Resources\BookingKonselingResource;
use App\Models\BookingKonseling;
use App\Services\Konseling\BookingKonselingService;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingKonseling extends ViewRecord
{
    protected static string $resource = BookingKonselingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (BookingKonseling $record): bool => $record->status === BookingKonseling::STATUS_DIAJUKAN)
                ->action(fn (BookingKonseling $record): mixed => app(BookingKonselingService::class)->approve($record))
                ->successNotificationTitle('Pengajuan disetujui'),
            Actions\Action::make('cancel')
                ->label('Batalkan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (BookingKonseling $record): bool => in_array($record->status, [
                    BookingKonseling::STATUS_DIAJUKAN,
                    BookingKonseling::STATUS_DIJADWALKAN,
                ], true))
                ->form([
                    Forms\Components\Textarea::make('alasan_pembatalan')
                        ->label('Alasan Pembatalan')
                        ->required()
                        ->maxLength(1000),
                ])
                ->action(fn (BookingKonseling $record, array $data): mixed => app(BookingKonselingService::class)->cancel($record, $data['alasan_pembatalan']))
                ->successNotificationTitle('Pengajuan dibatalkan'),
        ];
    }
}
