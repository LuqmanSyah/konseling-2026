<?php

use App\Filament\Mahasiswa\Resources\BookingKonselingResource;
use App\Filament\Mahasiswa\Resources\BookingKonselingResource\Pages\CreateBookingKonseling;
use App\Models\BookingKonseling;
use App\Models\CatatanKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\NotifikasiSimulasi;
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

    Filament::setCurrentPanel(Filament::getPanel('mahasiswa'));
});

it('allows active student to access the custom dashboard', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-001');

    $this->actingAs($mahasiswa->user)
        ->get('/mahasiswa')
        ->assertOk()
        ->assertSee('Dashboard Mahasiswa')
        ->assertSee('Layanan Konseling')
        ->assertSee('Ajukan Konseling');
});

it('keeps non student users out of the student panel', function (): void {
    $admin = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $admin->assignRole('admin_bkts');

    $this->actingAs($admin)
        ->get('/mahasiswa')
        ->assertForbidden();
});

it('lets student submit counseling request and reserves the schedule', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-002');
    $jadwal = createMahasiswaFeatureSchedule();

    $this->actingAs($mahasiswa->user);

    Livewire::test(CreateBookingKonseling::class)
        ->fillForm([
            'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
            'metode' => BookingKonseling::METODE_ONLINE,
            'jadwal_id' => $jadwal->id,
            'keluhan_awal' => 'Butuh konseling akademik.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $booking = BookingKonseling::query()
        ->where('mahasiswa_id', $mahasiswa->id)
        ->firstOrFail();

    expect($booking->status)->toBe(BookingKonseling::STATUS_DIAJUKAN)
        ->and($booking->konselor_id)->toBe($jadwal->konselor_id)
        ->and($booking->kode_booking)->toStartWith('BKTS-')
        ->and($jadwal->refresh()->status)->toBe(JadwalKonseling::STATUS_TERPAKAI)
        ->and(NotifikasiSimulasi::query()
            ->where('booking_id', $booking->id)
            ->where('penerima_id', $mahasiswa->user_id)
            ->where('jenis', 'pengajuan_dibuat')
            ->exists())->toBeTrue();
});

it('rejects unavailable schedules and method mismatches', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-003');
    $usedSchedule = createMahasiswaFeatureSchedule(status: JadwalKonseling::STATUS_TERPAKAI);
    $offlineSchedule = createMahasiswaFeatureSchedule(
        method: JadwalKonseling::METODE_TATAP_MUKA,
        date: '2026-07-11',
    );

    $this->actingAs($mahasiswa->user);

    Livewire::test(CreateBookingKonseling::class)
        ->fillForm([
            'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
            'metode' => BookingKonseling::METODE_ONLINE,
            'jadwal_id' => $usedSchedule->id,
            'keluhan_awal' => 'Butuh konseling akademik.',
        ])
        ->call('create')
        ->assertHasErrors();

    Livewire::test(CreateBookingKonseling::class)
        ->fillForm([
            'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
            'metode' => BookingKonseling::METODE_ONLINE,
            'jadwal_id' => $offlineSchedule->id,
            'keluhan_awal' => 'Butuh konseling akademik.',
        ])
        ->call('create')
        ->assertHasErrors();

    expect(BookingKonseling::query()->where('mahasiswa_id', $mahasiswa->id)->exists())->toBeFalse();
});

it('requires all request fields', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-004');

    $this->actingAs($mahasiswa->user);

    Livewire::test(CreateBookingKonseling::class)
        ->fillForm([
            'kategori' => null,
            'metode' => null,
            'jadwal_id' => null,
            'keluhan_awal' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'kategori' => 'required',
            'metode' => 'required',
            'jadwal_id' => 'required',
            'keluhan_awal' => 'required',
        ]);
});

it('only shows student own bookings and blocks direct access to other bookings', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-005');
    $otherMahasiswa = createMahasiswaFeatureStudent('MHS-2026-006');

    $ownBooking = createMahasiswaFeatureBooking($mahasiswa, 'BKTS-20260705-000001');
    $otherBooking = createMahasiswaFeatureBooking($otherMahasiswa, 'BKTS-20260705-000002', date: '2026-07-11');

    $this->actingAs($mahasiswa->user)
        ->get(BookingKonselingResource::getUrl('index', panel: 'mahasiswa'))
        ->assertOk()
        ->assertSee($ownBooking->kode_booking)
        ->assertDontSee($otherBooking->kode_booking);

    $this->actingAs($mahasiswa->user)
        ->get(BookingKonselingResource::getUrl('view', ['record' => $otherBooking], panel: 'mahasiswa'))
        ->assertNotFound();
});

it('does not expose confidential counseling notes to student', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-007');
    $booking = createMahasiswaFeatureBooking($mahasiswa, 'BKTS-20260705-000003');

    CatatanKonseling::create([
        'booking_id' => $booking->id,
        'konselor_id' => $booking->konselor_id,
        'catatan_hasil' => 'Catatan rahasia konselor tidak boleh tampil.',
        'rekomendasi' => 'Rekomendasi internal konselor.',
    ]);

    $this->actingAs($mahasiswa->user)
        ->get(BookingKonselingResource::getUrl('view', ['record' => $booking], panel: 'mahasiswa'))
        ->assertOk()
        ->assertSee($booking->kode_booking)
        ->assertDontSee('Catatan rahasia konselor tidak boleh tampil.')
        ->assertDontSee('Rekomendasi internal konselor.');
});

it('shows simulated meeting link only after online booking is scheduled', function (): void {
    $mahasiswa = createMahasiswaFeatureStudent('MHS-2026-008');
    $pendingBooking = createMahasiswaFeatureBooking($mahasiswa, 'BKTS-20260705-000004');
    $scheduledBooking = createMahasiswaFeatureBooking(
        $mahasiswa,
        'BKTS-20260705-000005',
        status: BookingKonseling::STATUS_DIJADWALKAN,
        link: 'https://meet.mock/BKTS-20260705-000005',
        date: '2026-07-11',
    );

    $this->actingAs($mahasiswa->user)
        ->get(BookingKonselingResource::getUrl('view', ['record' => $pendingBooking], panel: 'mahasiswa'))
        ->assertOk()
        ->assertDontSee('https://meet.mock/BKTS-20260705-000005');

    $this->actingAs($mahasiswa->user)
        ->get(BookingKonselingResource::getUrl('view', ['record' => $scheduledBooking], panel: 'mahasiswa'))
        ->assertOk()
        ->assertSee('https://meet.mock/BKTS-20260705-000005');
});

function createMahasiswaFeatureStudent(string $nim): Mahasiswa
{
    $user = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $user->assignRole('mahasiswa');

    return Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => $nim,
        'nama' => 'Mahasiswa ' . $nim,
        'program_studi' => 'Sistem Informasi',
        'angkatan' => '2026',
        'no_hp' => '082222222222',
        'email' => $user->email,
    ]);
}

function createMahasiswaFeatureCounselor(string $name = 'Konselor Demo'): Konselor
{
    $user = User::factory()->create([
        'name' => $name,
        'status' => User::STATUS_AKTIF,
    ]);
    $user->assignRole('konselor');

    return Konselor::create([
        'user_id' => $user->id,
        'nama' => $name,
        'bidang' => 'Konseling Akademik',
        'no_hp' => '081111111111',
        'email' => $user->email,
        'status' => Konselor::STATUS_AKTIF,
    ]);
}

function createMahasiswaFeatureSchedule(
    string $method = JadwalKonseling::METODE_ONLINE,
    string $status = JadwalKonseling::STATUS_TERSEDIA,
    string $date = '2026-07-10',
): JadwalKonseling {
    $konselor = createMahasiswaFeatureCounselor('Konselor ' . $date . ' ' . $method);

    return JadwalKonseling::create([
        'konselor_id' => $konselor->id,
        'tanggal' => $date,
        'jam_mulai' => '09:00:00',
        'jam_selesai' => '10:00:00',
        'metode' => $method,
        'status' => $status,
    ]);
}

function createMahasiswaFeatureBooking(
    Mahasiswa $mahasiswa,
    string $code,
    string $status = BookingKonseling::STATUS_DIAJUKAN,
    ?string $link = null,
    string $date = '2026-07-10',
): BookingKonseling {
    $jadwal = createMahasiswaFeatureSchedule(
        status: in_array($status, [
            BookingKonseling::STATUS_DIAJUKAN,
            BookingKonseling::STATUS_DIJADWALKAN,
            BookingKonseling::STATUS_SELESAI,
            BookingKonseling::STATUS_DIRUJUK,
        ], true)
            ? JadwalKonseling::STATUS_TERPAKAI
            : JadwalKonseling::STATUS_TERSEDIA,
        date: $date,
    );

    return BookingKonseling::create([
        'kode_booking' => $code,
        'mahasiswa_id' => $mahasiswa->id,
        'jadwal_id' => $jadwal->id,
        'konselor_id' => $jadwal->konselor_id,
        'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
        'metode' => BookingKonseling::METODE_ONLINE,
        'keluhan_awal' => 'Butuh konseling akademik.',
        'status' => $status,
        'link_meeting' => $link,
    ]);
}
