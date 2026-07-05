<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @var array{role: string|null, konselor: array<string, mixed>, mahasiswa: array<string, mixed>}
     */
    private array $roleAndProfileData = [
        'role' => null,
        'konselor' => [],
        'mahasiswa' => [],
    ];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->roleAndProfileData = UserResource::extractRoleAndProfileData($data);

        return $data;
    }

    protected function afterCreate(): void
    {
        UserResource::syncRoleAndProfile(
            $this->record,
            $this->roleAndProfileData['role'],
            $this->roleAndProfileData['konselor'],
            $this->roleAndProfileData['mahasiswa'],
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
