<?php

namespace App\Filament\Bkts\Resources;

use App\Filament\Bkts\Resources\RujukanResource\Pages;
use App\Models\BookingKonseling;
use App\Models\Rujukan;
use App\Support\KonselingOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RujukanResource extends Resource
{
    protected static ?string $model = Rujukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-top-right-on-square';

    protected static ?string $navigationGroup = 'Operasional BKTS';

    protected static ?string $navigationLabel = 'Rujukan';

    protected static ?string $modelLabel = 'Rujukan';

    protected static ?int $navigationSort = 30;

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['bookingKonseling.mahasiswa', 'bookingKonseling.konselor', 'pembuat']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('booking_id')
                    ->label('Booking')
                    ->relationship(
                        name: 'bookingKonseling',
                        titleAttribute: 'kode_booking',
                        modifyQueryUsing: fn (Builder $query): Builder => $query
                            ->where('status', '!=', BookingKonseling::STATUS_DIBATALKAN)
                            ->with(['mahasiswa'])
                    )
                    ->getOptionLabelFromRecordUsing(fn (BookingKonseling $record): string => $record->kode_booking . ' - ' . $record->mahasiswa->nama)
                    ->searchable(['kode_booking'])
                    ->preload()
                    ->required(),
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
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bookingKonseling.kode_booking')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookingKonseling.mahasiswa.nama')
                    ->label('Mahasiswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bookingKonseling.status')
                    ->label('Status Booking')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::bookingStatuses()[$state] ?? $state),
                Tables\Columns\TextColumn::make('tujuan_rujukan')
                    ->label('Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pembuat.name')
                    ->label('Dibuat Oleh'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Data Rujukan')
                    ->schema([
                        Infolists\Components\TextEntry::make('bookingKonseling.kode_booking')->label('Kode Booking'),
                        Infolists\Components\TextEntry::make('bookingKonseling.mahasiswa.nama')->label('Mahasiswa'),
                        Infolists\Components\TextEntry::make('tujuan_rujukan')->label('Tujuan Rujukan'),
                        Infolists\Components\TextEntry::make('pembuat.name')->label('Dibuat Oleh'),
                        Infolists\Components\TextEntry::make('alasan_rujukan')
                            ->label('Alasan')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('ringkasan_tindak_lanjut')
                            ->label('Ringkasan Tindak Lanjut')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRujukans::route('/'),
            'create' => Pages\CreateRujukan::route('/create'),
            'view' => Pages\ViewRujukan::route('/{record}'),
        ];
    }
}
