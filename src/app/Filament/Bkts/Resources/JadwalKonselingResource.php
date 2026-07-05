<?php

namespace App\Filament\Bkts\Resources;

use App\Filament\Bkts\Resources\JadwalKonselingResource\Pages;
use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Support\KonselingOptions;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JadwalKonselingResource extends Resource
{
    protected static ?string $model = JadwalKonseling::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Operasional BKTS';

    protected static ?string $navigationLabel = 'Jadwal Konseling';

    protected static ?string $modelLabel = 'Jadwal Konseling';

    protected static ?int $navigationSort = 10;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->where('status', JadwalKonseling::STATUS_TERSEDIA)
            ->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('konselor_id')
                    ->label('Konselor')
                    ->relationship(
                        name: 'konselor',
                        titleAttribute: 'nama',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->where('status', Konselor::STATUS_AKTIF)
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->native(false)
                    ->required(),
                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            $start = $get('jam_mulai');

                            if (blank($start) || blank($value)) {
                                return;
                            }

                            if (strtotime('1970-01-01 ' . $value) <= strtotime('1970-01-01 ' . $start)) {
                                $fail('Jam selesai harus setelah jam mulai.');
                            }
                        },
                    ])
                    ->required(),
                Forms\Components\Select::make('metode')
                    ->label('Metode')
                    ->options(KonselingOptions::metodeKonseling())
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options(KonselingOptions::jadwalStatuses())
                    ->default(JadwalKonseling::STATUS_TERSEDIA)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('konselor.nama')
                    ->label('Konselor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->label('Selesai')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::metodeKonseling()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        JadwalKonseling::METODE_ONLINE => 'info',
                        JadwalKonseling::METODE_TATAP_MUKA => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::jadwalStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        JadwalKonseling::STATUS_TERSEDIA => 'success',
                        JadwalKonseling::STATUS_TERPAKAI => 'warning',
                        JadwalKonseling::STATUS_TIDAK_AKTIF => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(KonselingOptions::jadwalStatuses()),
                Tables\Filters\SelectFilter::make('metode')
                    ->label('Metode')
                    ->options(KonselingOptions::metodeKonseling()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (JadwalKonseling $record): bool => ! self::hasActiveBooking($record)),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalKonselings::route('/'),
            'create' => Pages\CreateJadwalKonseling::route('/create'),
            'view' => Pages\ViewJadwalKonseling::route('/{record}'),
            'edit' => Pages\EditJadwalKonseling::route('/{record}/edit'),
        ];
    }

    private static function hasActiveBooking(JadwalKonseling $jadwal): bool
    {
        return BookingKonseling::query()
            ->where('jadwal_id', $jadwal->id)
            ->whereIn('status', [
                BookingKonseling::STATUS_DIAJUKAN,
                BookingKonseling::STATUS_DIJADWALKAN,
                BookingKonseling::STATUS_SELESAI,
                BookingKonseling::STATUS_DIRUJUK,
            ])
            ->exists();
    }
}
