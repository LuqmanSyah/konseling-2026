<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    foreach (['super_admin', 'admin_bkts', 'konselor', 'mahasiswa'] as $role) {
        Role::firstOrCreate([
            'name' => $role,
            'guard_name' => 'web',
        ]);
    }
});

it('shows the universal login page for guests', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Masuk ke sistem')
        ->assertSee('Konseling Mahasiswa');
});

it('redirects authenticated users away from the login page based on role', function (string $role, string $path): void {
    $user = createUserWithRole($role);

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect($path);
})->with([
    ['super_admin', '/admin'],
    ['admin_bkts', '/bkts'],
    ['konselor', '/konselor'],
    ['mahasiswa', '/mahasiswa'],
]);

it('logs in and redirects users based on role priority', function (string $role, string $path): void {
    createUserWithRole($role, 'role@example.com');

    $this->post('/login', [
        'email' => 'role@example.com',
        'password' => 'password',
    ])
        ->assertRedirect($path);

    $this->assertAuthenticated();
})->with([
    ['super_admin', '/admin'],
    ['admin_bkts', '/bkts'],
    ['konselor', '/konselor'],
    ['mahasiswa', '/mahasiswa'],
]);

it('ignores stale intended panel urls and redirects to the authenticated role panel', function (): void {
    createUserWithRole('mahasiswa', 'mahasiswa@example.com');

    $this->get('/admin')
        ->assertRedirect('/');

    $this->post('/login', [
        'email' => 'mahasiswa@example.com',
        'password' => 'password',
    ])
        ->assertRedirect('/mahasiswa');
});

it('rejects invalid credentials', function (): void {
    createUserWithRole('super_admin', 'admin@example.com');

    $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('redirects panel guests to the universal login page', function (string $path): void {
    $this->get($path)
        ->assertRedirect('/');
})->with([
    '/admin',
    '/bkts',
    '/konselor',
    '/mahasiswa',
]);

it('forbids users from accessing another role panel', function (): void {
    $user = createUserWithRole('mahasiswa');

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('logs out and returns to the universal login page', function (): void {
    $user = createUserWithRole('super_admin');

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});

function createUserWithRole(string $role, string $email = 'user@example.com'): User
{
    $user = User::factory()->create([
        'email' => $email,
    ]);

    $user->assignRole($role);

    return $user;
}
