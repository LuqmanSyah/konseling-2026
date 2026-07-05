<?php

namespace App\Filament\Konselor\Resources\BookingKonselingResource\Pages;

use App\Filament\Konselor\Resources\BookingKonselingResource;
use App\Models\BookingKonseling;
use App\Models\Konselor;
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
            Actions\Action::make('save_note')
                ->label('Simpan Catatan')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->visible(fn (BookingKonseling $record): bool => in_array($record->status, [
                    BookingKonseling::STATUS_DIJADWALKAN,
                    BookingKonseling::STATUS_SELESAI,
                    BookingKonseling::STATUS_DIRUJUK,
                ], true))
                ->fillForm(fn (BookingKonseling $record): array => $this->noteFormState($record))
                ->form($this->noteFormSchema())
                ->action(function (BookingKonseling $record, array $data): void {
                    app(BookingKonselingService::class)->saveCounselingNote($record, $data, $this->getKonselor());
                    $this->refreshFormData(['catatanKonseling']);
                })
                ->successNotificationTitle('Catatan konseling tersimpan'),
            Actions\Action::make('complete')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (BookingKonseling $record): bool => $record->status === BookingKonseling::STATUS_DIJADWALKAN)
                ->fillForm(fn (BookingKonseling $record): array => $this->noteFormState($record))
                ->form($this->noteFormSchema())
                ->requiresConfirmation()
                ->action(function (BookingKonseling $record, array $data): void {
                    app(BookingKonselingService::class)->completeCounseling($record, $data, $this->getKonselor());
                    $this->record->refresh();
                    $this->refreshFormData(['status', 'catatanKonseling']);
                })
                ->successNotificationTitle('Sesi ditandai selesai'),
            Actions\Action::make('refer')
                ->label('Rujuk')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('primary')
                ->visible(fn (BookingKonseling $record): bool => $record->status === BookingKonseling::STATUS_DIJADWALKAN)
                ->fillForm(fn (BookingKonseling $record): array => $this->referralFormState($record))
                ->form([
                    ...$this->noteFormSchema(),
                    Forms\Components\TextInput::make('tujuan_rujukan')
                        ->label('Tujuan Rujukan')
                        ->maxLength(255)
                        ->required(),
                    Forms\Components\Textarea::make('alasan_rujukan')
                        ->label('Alasan Rujukan')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('ringkasan_tindak_lanjut')
                        ->label('Ringkasan Tindak Lanjut')
                        ->columnSpanFull(),
                ])
                ->requiresConfirmation()
                ->action(function (BookingKonseling $record, array $data): void {
                    app(BookingKonselingService::class)->referCounseling(
                        $record,
                        [
                            'catatan_hasil' => $data['catatan_hasil'],
                            'rekomendasi' => $data['rekomendasi'],
                        ],
                        [
                            'tujuan_rujukan' => $data['tujuan_rujukan'],
                            'alasan_rujukan' => $data['alasan_rujukan'],
                            'ringkasan_tindak_lanjut' => $data['ringkasan_tindak_lanjut'] ?? null,
                        ],
                        $this->getKonselor(),
                        auth()->user(),
                    );

                    $this->record->refresh();
                    $this->refreshFormData(['status', 'catatanKonseling', 'rujukan']);
                })
                ->successNotificationTitle('Sesi ditandai dirujuk'),
        ];
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    private function noteFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('catatan_hasil')
                ->label('Catatan Hasil Konseling')
                ->required()
                ->columnSpanFull(),
            Forms\Components\Textarea::make('rekomendasi')
                ->label('Rekomendasi Tindak Lanjut')
                ->required()
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array{catatan_hasil: ?string, rekomendasi: ?string}
     */
    private function noteFormState(BookingKonseling $record): array
    {
        $record->loadMissing('catatanKonseling');

        return [
            'catatan_hasil' => $record->catatanKonseling?->catatan_hasil,
            'rekomendasi' => $record->catatanKonseling?->rekomendasi,
        ];
    }

    /**
     * @return array{catatan_hasil: ?string, rekomendasi: ?string, tujuan_rujukan: ?string, alasan_rujukan: ?string, ringkasan_tindak_lanjut: ?string}
     */
    private function referralFormState(BookingKonseling $record): array
    {
        $record->loadMissing('catatanKonseling', 'rujukan');

        return [
            'catatan_hasil' => $record->catatanKonseling?->catatan_hasil,
            'rekomendasi' => $record->catatanKonseling?->rekomendasi,
            'tujuan_rujukan' => $record->rujukan?->tujuan_rujukan,
            'alasan_rujukan' => $record->rujukan?->alasan_rujukan,
            'ringkasan_tindak_lanjut' => $record->rujukan?->ringkasan_tindak_lanjut,
        ];
    }

    private function getKonselor(): Konselor
    {
        $konselor = auth()->user()?->konselor;

        abort_if($konselor === null, 403);

        return $konselor;
    }
}
