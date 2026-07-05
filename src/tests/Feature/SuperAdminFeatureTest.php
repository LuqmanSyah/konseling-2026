<?php

use App\Filament\Admin\Pages\DataMaster;
use App\Filament\Admin\Resources\UserResource;
use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Models\Konselor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
    $compiledViewsPath = sys_get_temp_dir() . '/konseling-test-views';

    if (! is_dir($compiledViewsPath)) {
        mkdir($compiledViewsPath, 0777, true);
    }

    config([
        'logging.default' => 'null',
        'view.compiled' => $compiledViewsPath,
    ]);
    $cachePathProperty = new ReflectionProperty(app('blade.compiler'), 'cachePath');
    $cachePathProperty->setAccessible(true);
    $cachePathProperty->setValue(app('blade.compiler'), $compiledViewsPath);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (['super_admin', 'admin_bkts', 'konselor', 'mahasiswa'] as $role) {
        Role::firstOrCreate([
            'name' => $role,
            'guard_name' => 'web',
        ]);
    }

    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('allows super admin to create an active admin bkts account', function (): void {
    $this->actingAs(createSuperAdminUser());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Admin BKTS Baru',
            'email' => 'bkts.baru@example.test',
            'status' => User::STATUS_AKTIF,
            'role' => 'admin_bkts',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'bkts.baru@example.test')->firstOrFail();

    expect($user->status)->toBe(User::STATUS_AKTIF)
        ->and($user->hasRole('admin_bkts'))->toBeTrue()
        ->and($user->mahasiswa)->toBeNull()
        ->and($user->konselor)->toBeNull();
});

it('renders the super admin user management pages', function (): void {
    $superAdmin = createSuperAdminUser();
    $managedUser = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $managedUser->assignRole('admin_bkts');

    $this->actingAs($superAdmin)
        ->get(UserResource::getUrl('index', panel: 'admin'))
        ->assertOk();

    $this->actingAs($superAdmin)
        ->get(UserResource::getUrl('create', panel: 'admin'))
        ->assertOk()
        ->assertSee('Status Akun');

    $this->actingAs($superAdmin)
        ->get(UserResource::getUrl('edit', ['record' => $managedUser], panel: 'admin'))
        ->assertOk()
        ->assertSee('Role');
});

it('creates a konselor profile when super admin creates a konselor account', function (): void {
    $this->actingAs(createSuperAdminUser());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Konselor Baru',
            'email' => 'konselor.baru@example.test',
            'status' => User::STATUS_AKTIF,
            'role' => 'konselor',
            'password' => 'password',
            'password_confirmation' => 'password',
            'konselor_nama' => 'Konselor Baru',
            'konselor_bidang' => 'Konseling Akademik',
            'konselor_no_hp' => '081234567890',
            'konselor_email' => 'konselor.baru@example.test',
            'konselor_status' => Konselor::STATUS_AKTIF,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'konselor.baru@example.test')->firstOrFail();

    expect($user->hasRole('konselor'))->toBeTrue()
        ->and($user->konselor)->not->toBeNull()
        ->and($user->konselor->bidang)->toBe('Konseling Akademik')
        ->and($user->konselor->status)->toBe(Konselor::STATUS_AKTIF);
});

it('creates a mahasiswa profile when super admin creates a mahasiswa account', function (): void {
    $this->actingAs(createSuperAdminUser());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Mahasiswa Baru',
            'email' => 'mahasiswa.baru@example.test',
            'status' => User::STATUS_AKTIF,
            'role' => 'mahasiswa',
            'password' => 'password',
            'password_confirmation' => 'password',
            'mahasiswa_nim' => 'MHS-2026-001',
            'mahasiswa_nama' => 'Mahasiswa Baru',
            'mahasiswa_program_studi' => 'Sistem Informasi',
            'mahasiswa_angkatan' => '2026',
            'mahasiswa_no_hp' => '089876543210',
            'mahasiswa_email' => 'mahasiswa.baru@example.test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'mahasiswa.baru@example.test')->firstOrFail();

    expect($user->hasRole('mahasiswa'))->toBeTrue()
        ->and($user->mahasiswa)->not->toBeNull()
        ->and($user->mahasiswa->nim)->toBe('MHS-2026-001')
        ->and($user->mahasiswa->program_studi)->toBe('Sistem Informasi');
});

it('syncs account status and single role when super admin edits a user', function (): void {
    $this->actingAs(createSuperAdminUser());

    $user = User::factory()->create([
        'email' => 'edit.user@example.test',
        'status' => User::STATUS_AKTIF,
    ]);
    $user->assignRole('admin_bkts');

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => 'Edited User',
            'email' => 'edit.user@example.test',
            'status' => User::STATUS_NONAKTIF,
            'role' => 'konselor',
            'konselor_nama' => 'Edited Konselor',
            'konselor_bidang' => 'Konseling Umum',
            'konselor_no_hp' => '080000000000',
            'konselor_email' => 'edit.user@example.test',
            'konselor_status' => Konselor::STATUS_AKTIF,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();

    expect($user->status)->toBe(User::STATUS_NONAKTIF)
        ->and($user->getRoleNames()->all())->toBe(['konselor'])
        ->and($user->konselor)->not->toBeNull();
});

it('keeps inactive users blocked from their role panel', function (): void {
    $user = User::factory()->create([
        'status' => User::STATUS_NONAKTIF,
    ]);
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('limits the super admin data master page to the admin panel role', function (): void {
    $superAdmin = createSuperAdminUser();

    $this->actingAs($superAdmin)
        ->get(DataMaster::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSee('Kategori Konseling')
        ->assertSee('Non-Akademik')
        ->assertSee('Tatap Muka');

    $mahasiswaUser = User::factory()->create();
    $mahasiswaUser->assignRole('mahasiswa');

    $this->actingAs($mahasiswaUser)
        ->get(DataMaster::getUrl(panel: 'admin'))
        ->assertForbidden();
});

function createSuperAdminUser(): User
{
    $user = User::factory()->create([
        'email' => fake()->unique()->safeEmail(),
        'status' => User::STATUS_AKTIF,
    ]);

    $user->assignRole('super_admin');

    return $user;
}
