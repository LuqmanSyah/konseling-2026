<?php

use App\Filament\Konselor\Resources\BookingKonselingResource;
use App\Filament\Konselor\Resources\BookingKonselingResource\Pages\ViewBookingKonseling;
use App\Models\BookingKonseling;
use App\Models\CatatanKonseling;
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

    Filament::setCurrentPanel(Filament::getPanel('konselor'));
});

it('allows active counselor to access the custom dashboard', function (): void {
    $konselor = createKonselorFeatureCounselor('Konselor Utama');

    $this->actingAs($konselor->user)
        ->get('/konselor')
        ->assertOk()
        ->assertSee('Dashboard Konselor')
        ->assertSee('Jadwal Hari Ini')
        ->assertSee('Sesi Dijadwalkan');
});

it('keeps non counselor users out of the counselor panel', function (): void {
    $mahasiswaUser = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $mahasiswaUser->assignRole('mahasiswa');

    $this->actingAs($mahasiswaUser)
        ->get('/konselor')
        ->assertForbidden();
});

it('only shows bookings assigned to the logged in counselor', function (): void {
    $konselor = createKonselorFeatureCounselor('Konselor Utama');
    $otherKonselor = createKonselorFeatureCounselor('Konselor Lain');

    $ownBooking = createKonselorFeatureBooking($konselor, 'BKTS-2026-0001');
    $otherBooking = createKonselorFeatureBooking($otherKonselor, 'BKTS-2026-0002');

    $this->actingAs($konselor->user)
        ->get(BookingKonselingResource::getUrl('index', panel: 'konselor'))
        ->assertOk()
        ->assertSee($ownBooking->kode_booking)
        ->assertDontSee($otherBooking->kode_booking);
});

it('does not allow counselor to open another counselor booking directly', function (): void {
    $konselor = createKonselorFeatureCounselor('Konselor Utama');
    $otherKonselor = createKonselorFeatureCounselor('Konselor Lain');
    $otherBooking = createKonselorFeatureBooking($otherKonselor, 'BKTS-2026-0002');

    $this->actingAs($konselor->user)
        ->get(BookingKonselingResource::getUrl('view', ['record' => $otherBooking], panel: 'konselor'))
        ->assertNotFound();
});

it('lets counselor complete assigned session with counseling note', function (): void {
    $konselor = createKonselorFeatureCounselor('Konselor Utama');
    $booking = createKonselorFeatureBooking($konselor, 'BKTS-2026-0001');

    $this->actingAs($konselor->user);

    Livewire::test(ViewBookingKonseling::class, ['record' => $booking->getRouteKey()])
        ->callAction('complete', data: [
            'catatan_hasil' => 'Mahasiswa sudah mengikuti sesi konseling.',
            'rekomendasi' => 'Lanjutkan strategi belajar mingguan.',
        ])
        ->assertHasNoActionErrors();

    $booking->refresh();

    expect($booking->status)->toBe(BookingKonseling::STATUS_SELESAI)
        ->and(CatatanKonseling::query()->where('booking_id', $booking->id)->where('konselor_id', $konselor->id)->exists())->toBeTrue()
        ->and(NotifikasiSimulasi::query()
            ->where('booking_id', $booking->id)
            ->where('penerima_id', $booking->mahasiswa->user_id)
            ->where('jenis', 'booking_selesai')
            ->exists())->toBeTrue();
});

it('lets counselor refer assigned session with counseling note', function (): void {
    $admin = User::factory()->create([
        'status' => User::STATUS_AKTIF,
    ]);
    $admin->assignRole('admin_bkts');

    $konselor = createKonselorFeatureCounselor('Konselor Utama');
    $booking = createKonselorFeatureBooking($konselor, 'BKTS-2026-0001');

    $this->actingAs($konselor->user);

    Livewire::test(ViewBookingKonseling::class, ['record' => $booking->getRouteKey()])
        ->callAction('refer', data: [
            'catatan_hasil' => 'Mahasiswa membutuhkan pendampingan lanjutan.',
            'rekomendasi' => 'Perlu konsultasi dengan psikolog.',
            'tujuan_rujukan' => 'Psikolog',
            'alasan_rujukan' => 'Indikasi tekanan akademik berat.',
            'ringkasan_tindak_lanjut' => 'Admin BKTS menghubungi psikolog.',
        ])
        ->assertHasNoActionErrors();

    $booking->refresh();
    $rujukan = Rujukan::query()->where('booking_id', $booking->id)->firstOrFail();

    expect($booking->status)->toBe(BookingKonseling::STATUS_DIRUJUK)
        ->and($rujukan->dibuat_oleh)->toBe($konselor->user_id)
        ->and(CatatanKonseling::query()->where('booking_id', $booking->id)->where('konselor_id', $konselor->id)->exists())->toBeTrue()
        ->and(NotifikasiSimulasi::query()
            ->where('booking_id', $booking->id)
            ->where('penerima_id', $admin->id)
            ->where('jenis', 'booking_dirujuk')
            ->exists())->toBeTrue();
});

function createKonselorFeatureCounselor(string $name): Konselor
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

function createKonselorFeatureStudent(string $nim): Mahasiswa
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

function createKonselorFeatureBooking(Konselor $konselor, string $code): BookingKonseling
{
    $mahasiswa = createKonselorFeatureStudent('MHS-' . substr($code, -4));

    $jadwal = JadwalKonseling::create([
        'konselor_id' => $konselor->id,
        'hari' => JadwalKonseling::HARI_SENIN,
        'jam_mulai' => '09:00:00',
        'jam_selesai' => '10:00:00',
        'metode' => JadwalKonseling::METODE_ONLINE,
        'status' => JadwalKonseling::STATUS_TERPAKAI,
    ]);

    return BookingKonseling::create([
        'kode_booking' => $code,
        'mahasiswa_id' => $mahasiswa->id,
        'jadwal_id' => $jadwal->id,
        'konselor_id' => $konselor->id,
        'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
        'metode' => BookingKonseling::METODE_ONLINE,
        'keluhan_awal' => 'Butuh konseling akademik.',
        'status' => BookingKonseling::STATUS_DIJADWALKAN,
        'link_meeting' => 'https://meet.mock/' . $code,
    ]);
}
