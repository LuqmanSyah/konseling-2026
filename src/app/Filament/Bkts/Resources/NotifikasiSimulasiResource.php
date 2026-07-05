<?php

namespace App\Filament\Bkts\Resources;

use App\Filament\Bkts\Resources\NotifikasiSimulasiResource\Pages;
use App\Models\NotifikasiSimulasi;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotifikasiSimulasiResource extends Resource
{
    protected static ?string $model = NotifikasiSimulasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Operasional BKTS';

    protected static ?string $navigationLabel = 'Notifikasi Simulasi';

    protected static ?string $modelLabel = 'Notifikasi Simulasi';

    protected static ?int $navigationSort = 40;

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['bookingKonseling.mahasiswa', 'penerima']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bookingKonseling.kode_booking')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penerima.name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Channel')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => $state === NotifikasiSimulasi::STATUS_TERCATAT ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tercatat')
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
                Infolists\Components\Section::make('Notifikasi Simulasi')
                    ->schema([
                        Infolists\Components\TextEntry::make('bookingKonseling.kode_booking')->label('Kode Booking'),
                        Infolists\Components\TextEntry::make('penerima.name')->label('Penerima'),
                        Infolists\Components\TextEntry::make('jenis')->label('Jenis'),
                        Infolists\Components\TextEntry::make('channel')->label('Channel'),
                        Infolists\Components\TextEntry::make('status')->label('Status'),
                        Infolists\Components\TextEntry::make('pesan')
                            ->label('Pesan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifikasiSimulasis::route('/'),
            'view' => Pages\ViewNotifikasiSimulasi::route('/{record}'),
        ];
    }
}
