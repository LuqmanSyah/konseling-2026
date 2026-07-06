<?php

namespace App\Filament\Bkts\Resources;

use App\Filament\Bkts\Resources\BookingKonselingResource\Pages;
use App\Models\BookingKonseling;
use App\Services\Konseling\BookingKonselingService;
use App\Support\KonselingOptions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingKonselingResource extends Resource
{
    protected static ?string $model = BookingKonseling::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Operasional BKTS';

    protected static ?string $navigationLabel = 'Pengajuan Konseling';

    protected static ?string $modelLabel = 'Pengajuan Konseling';

    protected static ?int $navigationSort = 20;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->where('status', BookingKonseling::STATUS_DIAJUKAN)
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['mahasiswa', 'konselor', 'jadwalKonseling']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_booking')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('konselor.nama')
                    ->label('Konselor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwalKonseling.hari')
                    ->label('Hari')
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::hariDalamMinggu()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::kategoriKonseling()[$state] ?? $state),
                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::metodeKonseling()[$state] ?? $state),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::bookingStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => self::statusColor($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(KonselingOptions::bookingStatuses()),
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options(KonselingOptions::kategoriKonseling()),
                Tables\Filters\SelectFilter::make('metode')
                    ->label('Metode')
                    ->options(KonselingOptions::metodeKonseling()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (BookingKonseling $record): bool => $record->status === BookingKonseling::STATUS_DIAJUKAN)
                    ->action(fn (BookingKonseling $record): mixed => app(BookingKonselingService::class)->approve($record))
                    ->successNotificationTitle('Pengajuan disetujui'),
                Tables\Actions\Action::make('cancel')
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
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Pengajuan')
                    ->schema([
                        Infolists\Components\TextEntry::make('kode_booking')->label('Kode Booking'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => KonselingOptions::bookingStatuses()[$state] ?? $state)
                            ->color(fn (string $state): string => self::statusColor($state)),
                        Infolists\Components\TextEntry::make('mahasiswa.nama')->label('Mahasiswa'),
                        Infolists\Components\TextEntry::make('konselor.nama')->label('Konselor'),
                        Infolists\Components\TextEntry::make('kategori')
                            ->label('Kategori')
                            ->formatStateUsing(fn (string $state): string => KonselingOptions::kategoriKonseling()[$state] ?? $state),
                        Infolists\Components\TextEntry::make('metode')
                            ->label('Metode')
                            ->formatStateUsing(fn (string $state): string => KonselingOptions::metodeKonseling()[$state] ?? $state),
                        Infolists\Components\TextEntry::make('jadwalKonseling.hari')
                            ->label('Hari')
                            ->formatStateUsing(fn (string $state): string => KonselingOptions::hariDalamMinggu()[$state] ?? $state),
                        Infolists\Components\TextEntry::make('jadwalKonseling.jam_mulai')
                            ->label('Jam Mulai')
                            ->time('H:i'),
                        Infolists\Components\TextEntry::make('jadwalKonseling.jam_selesai')
                            ->label('Jam Selesai')
                            ->time('H:i'),
                        Infolists\Components\TextEntry::make('link_meeting')
                            ->label('Link Meeting Simulasi')
                            ->placeholder('-')
                            ->url(fn (?string $state): ?string => $state),
                        Infolists\Components\TextEntry::make('alasan_pembatalan')
                            ->label('Alasan Pembatalan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('keluhan_awal')
                            ->label('Keluhan Awal')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Catatan Konseling')
                    ->schema([
                        Infolists\Components\TextEntry::make('catatanKonseling.catatan_hasil')
                            ->label('Catatan Hasil')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('catatanKonseling.rekomendasi')
                            ->label('Rekomendasi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make('Rujukan')
                    ->schema([
                        Infolists\Components\TextEntry::make('rujukan.tujuan_rujukan')
                            ->label('Tujuan')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('rujukan.alasan_rujukan')
                            ->label('Alasan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('rujukan.ringkasan_tindak_lanjut')
                            ->label('Ringkasan Tindak Lanjut')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make('Notifikasi Simulasi')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('notifikasiSimulasi')
                            ->label('Notifikasi')
                            ->schema([
                                Infolists\Components\TextEntry::make('jenis')->label('Jenis'),
                                Infolists\Components\TextEntry::make('penerima.name')->label('Penerima'),
                                Infolists\Components\TextEntry::make('channel')->label('Channel'),
                                Infolists\Components\TextEntry::make('status')->label('Status'),
                                Infolists\Components\TextEntry::make('pesan')
                                    ->label('Pesan')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingKonselings::route('/'),
            'view' => Pages\ViewBookingKonseling::route('/{record}'),
        ];
    }

    private static function statusColor(string $state): string
    {
        return match ($state) {
            BookingKonseling::STATUS_DIAJUKAN => 'warning',
            BookingKonseling::STATUS_DIJADWALKAN => 'info',
            BookingKonseling::STATUS_SELESAI => 'success',
            BookingKonseling::STATUS_DIRUJUK => 'primary',
            BookingKonseling::STATUS_DIBATALKAN => 'danger',
            default => 'gray',
        };
    }
}
