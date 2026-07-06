<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\BookingKonselingResource\Pages;
use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Support\KonselingOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Layanan Konseling';

    protected static ?string $navigationLabel = 'Pengajuan Konseling';

    protected static ?string $modelLabel = 'Pengajuan Konseling';

    protected static ?int $navigationSort = 10;

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
        $mahasiswa = auth()->user()?->mahasiswa;

        if ($mahasiswa === null) {
            return '0';
        }

        return (string) static::getModel()::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIJADWALKAN,
            ])
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['konselor', 'jadwalKonseling']);

        $mahasiswa = auth()->user()?->mahasiswa;

        if ($mahasiswa === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('mahasiswa_id', $mahasiswa->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori')
                    ->label('Kategori Konseling')
                    ->options(KonselingOptions::kategoriKonseling())
                    ->required(),
                Forms\Components\Select::make('metode')
                    ->label('Metode Konseling')
                    ->options(KonselingOptions::metodeKonseling())
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set): mixed => $set('jadwal_id', null))
                    ->required(),
                Forms\Components\ViewField::make('jadwal_id')
                    ->label('Jadwal Tersedia')
                    ->view('filament.mahasiswa.forms.schedule-table')
                    ->viewData([
                        'getSchedules' => fn (Get $get): array => self::availableScheduleRows($get('metode')),
                    ])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('keluhan_awal')
                    ->label('Keluhan Awal')
                    ->required()
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_booking')
                    ->label('Kode')
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
                Tables\Columns\TextColumn::make('konselor.nama')
                    ->label('Konselor')
                    ->searchable()
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingKonselings::route('/'),
            'create' => Pages\CreateBookingKonseling::route('/create'),
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

    /**
     * @return array<int, string>
     */
    public static function availableScheduleRows(?string $metode): array
    {
        return JadwalKonseling::query()
            ->with('konselor')
            ->where('status', JadwalKonseling::STATUS_TERSEDIA)
            ->when(filled($metode), fn (Builder $query): Builder => $query->where('metode', $metode))
            ->orderByRaw(JadwalKonseling::hariOrderCase())
            ->orderBy('jam_mulai')
            ->limit(50)
            ->get()
            ->map(fn (JadwalKonseling $jadwal): array => [
                'id' => $jadwal->id,
                'hari' => KonselingOptions::hariDalamMinggu()[$jadwal->hari] ?? $jadwal->hari,
                'jam' => $jadwal->jam_mulai->format('H:i') . ' - ' . $jadwal->jam_selesai->format('H:i'),
                'konselor' => $jadwal->konselor?->nama ?? 'Konselor',
                'metode' => KonselingOptions::metodeKonseling()[$jadwal->metode] ?? $jadwal->metode,
            ])
            ->all();
    }
}
