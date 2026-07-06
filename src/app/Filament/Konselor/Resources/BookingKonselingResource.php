<?php

namespace App\Filament\Konselor\Resources;

use App\Filament\Konselor\Resources\BookingKonselingResource\Pages;
use App\Models\BookingKonseling;
use App\Support\KonselingOptions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BookingKonselingResource extends Resource
{
    protected static ?string $model = BookingKonseling::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Layanan Konseling';

    protected static ?string $navigationLabel = 'Jadwal Saya';

    protected static ?string $modelLabel = 'Jadwal Konseling';

    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $konselor = auth()->user()?->konselor;

        if ($konselor === null) {
            return '0';
        }

        return (string) static::getModel()::query()
            ->where('konselor_id', $konselor->id)
            ->where('status', BookingKonseling::STATUS_DIJADWALKAN)
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['mahasiswa', 'jadwalKonseling', 'catatanKonseling', 'rujukan']);

        $konselor = auth()->user()?->konselor;

        if ($konselor === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('konselor_id', $konselor->id);
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
                Tables\Columns\TextColumn::make('jadwalKonseling.hari')
                    ->label('Hari')
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::hariDalamMinggu()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwalKonseling.jam_mulai')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwalKonseling.jam_selesai')
                    ->label('Selesai')
                    ->time('H:i')
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
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Sesi')
                    ->schema([
                        Infolists\Components\TextEntry::make('kode_booking')->label('Kode Booking'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => KonselingOptions::bookingStatuses()[$state] ?? $state)
                            ->color(fn (string $state): string => self::statusColor($state)),
                        Infolists\Components\TextEntry::make('mahasiswa.nama')->label('Mahasiswa'),
                        Infolists\Components\TextEntry::make('mahasiswa.nim')->label('NIM'),
                        Infolists\Components\TextEntry::make('mahasiswa.program_studi')->label('Program Studi'),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingKonselings::route('/'),
            'view' => Pages\ViewBookingKonseling::route('/{record}'),
        ];
    }

    public static function statusColor(string $state): string
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
