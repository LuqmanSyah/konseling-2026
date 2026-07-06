<?php

use App\Filament\Bkts\Pages\LaporanKonseling;
use App\Filament\Bkts\Resources\BookingKonselingResource;
use App\Filament\Bkts\Resources\BookingKonselingResource\Pages\ListBookingKonselings;
use App\Filament\Bkts\Resources\JadwalKonselingResource;
use App\Filament\Bkts\Resources\JadwalKonselingResource\Pages\CreateJadwalKonseling;
use App\Filament\Bkts\Resources\NotifikasiSimulasiResource;
use App\Filament\Bkts\Resources\RujukanResource;
use App\Filament\Bkts\Resources\RujukanResource\Pages\CreateRujukan;
use App\Models\BookingKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\NotifikasiSimulasi;
use App\Models\Rujukan;
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

    Filament::setCurrentPanel(Filament::getPanel('bkts'));
});

it('allows active admin bkts to access the custom dashboard', function (): void {
    $admin = createBktsAdminUser();

    $this->actingAs($admin)
        ->get('/bkts')
        ->assertOk()
        ->assertSee('Dashboard Admin BKTS')
        ->assertSee('Total Pengajuan')
        ->assertSee('Menunggu Verifikasi');
});

it('keeps non admin bkts users out of the bkts panel', function (): void {
    $mahasiswaUser = User::factory()->create();
    $mahasiswaUser->assignRole('mahasiswa');

    $this->actingAs($mahasiswaUser)
        ->get('/bkts')
        ->assertForbidden();
});

it('allows admin bkts to create a valid counseling schedule', function (): void {
    $this->actingAs(createBktsAdminUser());
    $konselor = createKonselorProfile();

    Livewire::test(CreateJadwalKonseling::class)
        ->fillForm([
            'konselor_id' => $konselor->id,
            'hari' => JadwalKonseling::HARI_SENIN,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'metode' => JadwalKonseling::METODE_ONLINE,
            'status' => JadwalKonseling::STATUS_TERSEDIA,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('jadwal_konseling', [
        'konselor_id' => $konselor->id,
        'hari' => JadwalKonseling::HARI_SENIN,
        'metode' => JadwalKonseling::METODE_ONLINE,
        'status' => JadwalKonseling::STATUS_TERSEDIA,
    ]);
});

it('rejects schedules where end time is not after start time', function (): void {
    $this->actingAs(createBktsAdminUser());
    $konselor = createKonselorProfile();

    Livewire::test(CreateJadwalKonseling::class)
        ->fillForm([
            'konselor_id' => $konselor->id,
            'hari' => JadwalKonseling::HARI_SENIN,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '09:00:00',
            'metode' => JadwalKonseling::METODE_ONLINE,
            'status' => JadwalKonseling::STATUS_TERSEDIA,
        ])
        ->call('create')
        ->assertHasFormErrors(['jam_selesai']);
});

it('rejects overlapping weekly schedules for the same counselor and day', function (): void {
    $this->actingAs(createBktsAdminUser());
    $konselor = createKonselorProfile();

    JadwalKonseling::create([
        'konselor_id' => $konselor->id,
        'hari' => JadwalKonseling::HARI_SENIN,
        'jam_mulai' => '09:00:00',
        'jam_selesai' => '10:00:00',
        'metode' => JadwalKonseling::METODE_ONLINE,
        'status' => JadwalKonseling::STATUS_TERSEDIA,
    ]);

    Livewire::test(CreateJadwalKonseling::class)
        ->fillForm([
            'konselor_id' => $konselor->id,
            'hari' => JadwalKonseling::HARI_SENIN,
            'jam_mulai' => '09:30:00',
            'jam_selesai' => '10:30:00',
            'metode' => JadwalKonseling::METODE_TATAP_MUKA,
            'status' => JadwalKonseling::STATUS_TERSEDIA,
        ])
        ->call('create')
        ->assertHasFormErrors(['jam_selesai']);
});

it('does not expose manual booking creation for admin bkts', function (): void {
    expect(BookingKonselingResource::canCreate())->toBeFalse();
});

it('approves submitted booking and records simulated notifications', function (): void {
    $this->actingAs(createBktsAdminUser());
    $booking = createBktsBooking();

    Livewire::test(ListBookingKonselings::class)
        ->callTableAction('approve', $booking)
        ->assertHasNoTableActionErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingKonseling::STATUS_DIJADWALKAN)
        ->and($booking->link_meeting)->toBe('https://meet.mock/BKTS-2026-0001')
        ->and($booking->jadwalKonseling->refresh()->status)->toBe(JadwalKonseling::STATUS_TERPAKAI);

    expect(NotifikasiSimulasi::query()->where('booking_id', $booking->id)->count())->toBe(2);
});

it('requires cancellation reason and reopens the schedule when cancelled', function (): void {
    $this->actingAs(createBktsAdminUser());
    $booking = createBktsBooking(status: BookingKonseling::STATUS_DIJADWALKAN);
    $booking->jadwalKonseling->forceFill(['status' => JadwalKonseling::STATUS_TERPAKAI])->save();

    Livewire::test(ListBookingKonselings::class)
        ->callTableAction('cancel', $booking, data: ['alasan_pembatalan' => ''])
        ->assertHasTableActionErrors(['alasan_pembatalan' => 'required']);

    Livewire::test(ListBookingKonselings::class)
        ->callTableAction('cancel', $booking, data: ['alasan_pembatalan' => 'Mahasiswa berhalangan.'])
        ->assertHasNoTableActionErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingKonseling::STATUS_DIBATALKAN)
        ->and($booking->alasan_pembatalan)->toBe('Mahasiswa berhalangan.')
        ->and($booking->jadwalKonseling->refresh()->status)->toBe(JadwalKonseling::STATUS_TERSEDIA);

    expect(NotifikasiSimulasi::query()->where('booking_id', $booking->id)->where('jenis', 'booking_dibatalkan')->count())->toBe(2);
});

it('creates referral with creator and marks booking as referred', function (): void {
    $admin = createBktsAdminUser();
    $this->actingAs($admin);
    $booking = createBktsBooking(status: BookingKonseling::STATUS_DIJADWALKAN);

    Livewire::test(CreateRujukan::class)
        ->fillForm([
            'booking_id' => $booking->id,
            'tujuan_rujukan' => 'Psikolog',
            'alasan_rujukan' => 'Perlu tindak lanjut psikologis.',
            'ringkasan_tindak_lanjut' => 'Admin BKTS menghubungi psikolog.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $rujukan = Rujukan::query()->where('booking_id', $booking->id)->firstOrFail();

    expect($rujukan->dibuat_oleh)->toBe($admin->id)
        ->and($booking->refresh()->status)->toBe(BookingKonseling::STATUS_DIRUJUK);
});

it('renders read only notifications and basic report pages', function (): void {
    $this->actingAs(createBktsAdminUser());
    $booking = createBktsBooking();

    NotifikasiSimulasi::create([
        'booking_id' => $booking->id,
        'penerima_id' => $booking->mahasiswa->user_id,
        'jenis' => 'pengajuan_dibuat',
        'pesan' => 'Pengajuan konseling berhasil dibuat.',
        'channel' => NotifikasiSimulasi::CHANNEL_SISTEM,
        'status' => NotifikasiSimulasi::STATUS_TERCATAT,
    ]);

    expect(NotifikasiSimulasiResource::canCreate())->toBeFalse();

    $this->get(NotifikasiSimulasiResource::getUrl('index', panel: 'bkts'))
        ->assertOk()
        ->assertSee('pengajuan_dibuat');

    $this->get(LaporanKonseling::getUrl(panel: 'bkts'))
        ->assertOk()
        ->assertSee('Laporan Dasar Layanan Konseling')
        ->assertSee('BKTS-2026-0001');

    $this->get(JadwalKonselingResource::getUrl('index', panel: 'bkts'))->assertOk();
    $this->get(RujukanResource::getUrl('index', panel: 'bkts'))->assertOk();
});

function createBktsAdminUser(): User
{
    $user = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);

    $user->assignRole('admin_bkts');

    return $user;
}

function createKonselorProfile(): Konselor
{
    $user = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $user->assignRole('konselor');

    return Konselor::create([
        'user_id' => $user->id,
        'nama' => 'Konselor Demo',
        'bidang' => 'Konseling Akademik',
        'no_hp' => '081111111111',
        'email' => $user->email,
        'status' => Konselor::STATUS_AKTIF,
    ]);
}

function createMahasiswaProfile(): Mahasiswa
{
    $user = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $user->assignRole('mahasiswa');

    return Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'MHS-2026-001',
        'nama' => 'Mahasiswa Demo',
        'program_studi' => 'Sistem Informasi',
        'angkatan' => '2026',
        'no_hp' => '082222222222',
        'email' => $user->email,
    ]);
}

function createBktsBooking(string $status = BookingKonseling::STATUS_DIAJUKAN): BookingKonseling
{
    $konselor = createKonselorProfile();
    $mahasiswa = createMahasiswaProfile();

    $jadwal = JadwalKonseling::create([
        'konselor_id' => $konselor->id,
        'hari' => JadwalKonseling::HARI_SENIN,
        'jam_mulai' => '09:00:00',
        'jam_selesai' => '10:00:00',
        'metode' => JadwalKonseling::METODE_ONLINE,
        'status' => $status === BookingKonseling::STATUS_DIAJUKAN
            ? JadwalKonseling::STATUS_TERSEDIA
            : JadwalKonseling::STATUS_TERPAKAI,
    ]);

    return BookingKonseling::create([
        'kode_booking' => 'BKTS-2026-0001',
        'mahasiswa_id' => $mahasiswa->id,
        'jadwal_id' => $jadwal->id,
        'konselor_id' => $konselor->id,
        'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
        'metode' => BookingKonseling::METODE_ONLINE,
        'keluhan_awal' => 'Butuh konseling akademik.',
        'status' => $status,
    ]);
}
