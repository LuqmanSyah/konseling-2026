<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role'] = $this->record->roles()->value('name');

        if ($this->record->konselor !== null) {
            $data['konselor_nama'] = $this->record->konselor->nama;
            $data['konselor_bidang'] = $this->record->konselor->bidang;
            $data['konselor_no_hp'] = $this->record->konselor->no_hp;
            $data['konselor_email'] = $this->record->konselor->email;
            $data['konselor_status'] = $this->record->konselor->status;
        }

        if ($this->record->mahasiswa !== null) {
            $data['mahasiswa_nim'] = $this->record->mahasiswa->nim;
            $data['mahasiswa_nama'] = $this->record->mahasiswa->nama;
            $data['mahasiswa_program_studi'] = $this->record->mahasiswa->program_studi;
            $data['mahasiswa_angkatan'] = $this->record->mahasiswa->angkatan;
            $data['mahasiswa_no_hp'] = $this->record->mahasiswa->no_hp;
            $data['mahasiswa_email'] = $this->record->mahasiswa->email;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->roleAndProfileData = UserResource::extractRoleAndProfileData($data);

        return $data;
    }

    protected function afterSave(): void
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
