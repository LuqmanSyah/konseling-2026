<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Support\KonselingOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = -2;

    private const KONSELOR_FIELDS = [
        'konselor_nama' => 'nama',
        'konselor_bidang' => 'bidang',
        'konselor_no_hp' => 'no_hp',
        'konselor_email' => 'email',
        'konselor_status' => 'status',
    ];

    private const MAHASISWA_FIELDS = [
        'mahasiswa_nim' => 'nim',
        'mahasiswa_nama' => 'nama',
        'mahasiswa_program_studi' => 'program_studi',
        'mahasiswa_angkatan' => 'angkatan',
        'mahasiswa_no_hp' => 'no_hp',
        'mahasiswa_email' => 'email',
    ];

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'roles.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Role' => $record->roles->pluck('name')->implode(', '),
            'Email' => $record->email,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->minLength(2)
                            ->maxLength(255)
                            ->columnSpan('full')
                            ->required(),
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->image()
                            ->optimize('webp')
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('7:2')
                            ->panelLayout('integrated')
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->prefixIcon('heroicon-m-envelope')
                            ->columnSpan('full')
                            ->email()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('status')
                            ->label('Status Akun')
                            ->options(KonselingOptions::userStatuses())
                            ->default(User::STATUS_AKTIF)
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options(KonselingOptions::roles())
                            ->live()
                            ->dehydrated()
                            ->required(),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->confirmed()
                            ->columnSpan(1)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->columnSpan(1)
                            ->password(),
                    ]),

                Forms\Components\Section::make('Profil Konselor')
                    ->schema([
                        Forms\Components\TextInput::make('konselor_nama')
                            ->label('Nama Konselor')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'konselor'),
                        Forms\Components\TextInput::make('konselor_bidang')
                            ->label('Bidang')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'konselor'),
                        Forms\Components\TextInput::make('konselor_no_hp')
                            ->label('No HP')
                            ->tel()
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'konselor'),
                        Forms\Components\TextInput::make('konselor_email')
                            ->label('Email Konselor')
                            ->email()
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'konselor'),
                        Forms\Components\Select::make('konselor_status')
                            ->label('Status Konselor')
                            ->options(KonselingOptions::konselorStatuses())
                            ->default(Konselor::STATUS_AKTIF)
                            ->required(fn (Get $get): bool => $get('role') === 'konselor'),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('role') === 'konselor'),

                Forms\Components\Section::make('Profil Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('mahasiswa_nim')
                            ->label('NIM')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa')
                            ->rule(fn (?User $record): mixed => Rule::unique('mahasiswa', 'nim')->ignore($record?->mahasiswa?->id)),
                        Forms\Components\TextInput::make('mahasiswa_nama')
                            ->label('Nama Mahasiswa')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa'),
                        Forms\Components\TextInput::make('mahasiswa_program_studi')
                            ->label('Program Studi')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa'),
                        Forms\Components\TextInput::make('mahasiswa_angkatan')
                            ->label('Angkatan')
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa'),
                        Forms\Components\TextInput::make('mahasiswa_no_hp')
                            ->label('No HP')
                            ->tel()
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa'),
                        Forms\Components\TextInput::make('mahasiswa_email')
                            ->label('Email Mahasiswa')
                            ->email()
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('role') === 'mahasiswa'),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $get('role') === 'mahasiswa'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->defaultImageUrl(url('https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028?d=mp&r=g&s=250'))
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => KonselingOptions::userStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        User::STATUS_AKTIF => 'success',
                        User::STATUS_NONAKTIF => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Akun')
                    ->options(KonselingOptions::userStatuses()),
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->options(KonselingOptions::roles()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * @return array{role: string|null, konselor: array<string, mixed>, mahasiswa: array<string, mixed>}
     */
    public static function extractRoleAndProfileData(array &$data): array
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        $konselor = [];
        foreach (self::KONSELOR_FIELDS as $formKey => $modelKey) {
            if (array_key_exists($formKey, $data)) {
                $konselor[$modelKey] = $data[$formKey];
                unset($data[$formKey]);
            }
        }

        $mahasiswa = [];
        foreach (self::MAHASISWA_FIELDS as $formKey => $modelKey) {
            if (array_key_exists($formKey, $data)) {
                $mahasiswa[$modelKey] = $data[$formKey];
                unset($data[$formKey]);
            }
        }

        return [
            'role' => $role,
            'konselor' => $konselor,
            'mahasiswa' => $mahasiswa,
        ];
    }

    public static function syncRoleAndProfile(User $user, ?string $role, array $konselor, array $mahasiswa): void
    {
        if ($role !== null) {
            $user->syncRoles([$role]);
        }

        match ($role) {
            'konselor' => Konselor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$konselor,
                    'email' => $konselor['email'] ?? $user->email,
                    'nama' => $konselor['nama'] ?? $user->name,
                    'status' => $konselor['status'] ?? Konselor::STATUS_AKTIF,
                ],
            ),
            'mahasiswa' => Mahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$mahasiswa,
                    'email' => $mahasiswa['email'] ?? $user->email,
                    'nama' => $mahasiswa['nama'] ?? $user->name,
                ],
            ),
            default => null,
        };
    }
}
