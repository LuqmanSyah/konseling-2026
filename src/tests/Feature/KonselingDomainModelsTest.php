<?php

use App\Models\BookingKonseling;
use App\Models\CatatanKonseling;
use App\Models\JadwalKonseling;
use App\Models\Konselor;
use App\Models\Mahasiswa;
use App\Models\NotifikasiSimulasi;
use App\Models\Rujukan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates the konseling domain tables with required columns', function (): void {
    expect(Schema::hasColumns('users', ['status']))->toBeTrue()
        ->and(Schema::hasColumns('mahasiswa', [
            'id',
            'user_id',
            'nim',
            'nama',
            'program_studi',
            'angkatan',
            'no_hp',
            'email',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('konselor', [
            'id',
            'user_id',
            'nama',
            'bidang',
            'no_hp',
            'email',
            'status',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('jadwal_konseling', [
            'id',
            'konselor_id',
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'metode',
            'status',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('booking_konseling', [
            'id',
            'kode_booking',
            'mahasiswa_id',
            'jadwal_id',
            'konselor_id',
            'kategori',
            'metode',
            'keluhan_awal',
            'status',
            'link_meeting',
            'alasan_pembatalan',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('catatan_konseling', [
            'id',
            'booking_id',
            'konselor_id',
            'catatan_hasil',
            'rekomendasi',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('rujukan', [
            'id',
            'booking_id',
            'tujuan_rujukan',
            'alasan_rujukan',
            'ringkasan_tindak_lanjut',
            'dibuat_oleh',
        ]))->toBeTrue()
        ->and(Schema::hasColumns('notifikasi_simulasi', [
            'id',
            'booking_id',
            'penerima_id',
            'jenis',
            'pesan',
            'channel',
            'status',
        ]))->toBeTrue();
});

it('persists the primary konseling relationships', function (): void {
    $mahasiswaUser = User::factory()->create([
        'email' => 'mahasiswa@example.test',
    ]);
    $konselorUser = User::factory()->create([
        'email' => 'konselor@example.test',
    ]);
    $adminUser = User::factory()->create([
        'email' => 'admin@example.test',
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $mahasiswaUser->id,
        'nim' => 'MHS-001',
        'nama' => 'Mahasiswa Demo',
        'program_studi' => 'Sistem Informasi',
        'angkatan' => '2026',
        'no_hp' => '081111111111',
        'email' => $mahasiswaUser->email,
    ]);

    $konselor = Konselor::create([
        'user_id' => $konselorUser->id,
        'nama' => 'Konselor Demo',
        'bidang' => 'Akademik',
        'no_hp' => '082222222222',
        'email' => $konselorUser->email,
        'status' => Konselor::STATUS_AKTIF,
    ]);

    $jadwal = JadwalKonseling::create([
        'konselor_id' => $konselor->id,
        'tanggal' => '2026-07-06',
        'jam_mulai' => '09:00:00',
        'jam_selesai' => '10:00:00',
        'metode' => JadwalKonseling::METODE_ONLINE,
        'status' => JadwalKonseling::STATUS_TERSEDIA,
    ]);

    $booking = BookingKonseling::create([
        'kode_booking' => 'BKTS-2026-0001',
        'mahasiswa_id' => $mahasiswa->id,
        'jadwal_id' => $jadwal->id,
        'konselor_id' => $konselor->id,
        'kategori' => BookingKonseling::KATEGORI_AKADEMIK,
        'metode' => BookingKonseling::METODE_ONLINE,
        'keluhan_awal' => 'Butuh konseling akademik.',
        'status' => BookingKonseling::STATUS_DIAJUKAN,
        'link_meeting' => 'meet.mock/BKTS-2026-0001',
    ]);

    $catatan = CatatanKonseling::create([
        'booking_id' => $booking->id,
        'konselor_id' => $konselor->id,
        'catatan_hasil' => 'Catatan rahasia sesi.',
        'rekomendasi' => 'Lanjutkan pendampingan.',
    ]);

    $rujukan = Rujukan::create([
        'booking_id' => $booking->id,
        'tujuan_rujukan' => 'Psikolog',
        'alasan_rujukan' => 'Perlu tindak lanjut.',
        'ringkasan_tindak_lanjut' => 'Admin BKTS menghubungi psikolog.',
        'dibuat_oleh' => $adminUser->id,
    ]);

    $notifikasi = NotifikasiSimulasi::create([
        'booking_id' => $booking->id,
        'penerima_id' => $mahasiswaUser->id,
        'jenis' => 'pengajuan_dibuat',
        'pesan' => 'Pengajuan konseling berhasil dibuat.',
        'channel' => NotifikasiSimulasi::CHANNEL_SISTEM,
        'status' => NotifikasiSimulasi::STATUS_TERCATAT,
    ]);

    expect($mahasiswaUser->mahasiswa->is($mahasiswa))->toBeTrue()
        ->and($konselorUser->konselor->is($konselor))->toBeTrue()
        ->and($mahasiswa->bookingKonseling)->toHaveCount(1)
        ->and($konselor->jadwalKonseling)->toHaveCount(1)
        ->and($konselor->bookingKonseling)->toHaveCount(1)
        ->and($konselor->catatanKonseling)->toHaveCount(1)
        ->and($jadwal->bookingKonseling)->toHaveCount(1)
        ->and($booking->mahasiswa->is($mahasiswa))->toBeTrue()
        ->and($booking->jadwalKonseling->is($jadwal))->toBeTrue()
        ->and($booking->konselor->is($konselor))->toBeTrue()
        ->and($booking->catatanKonseling->is($catatan))->toBeTrue()
        ->and($booking->rujukan->is($rujukan))->toBeTrue()
        ->and($booking->notifikasiSimulasi)->toHaveCount(1)
        ->and($notifikasi->penerima->is($mahasiswaUser))->toBeTrue()
        ->and($rujukan->pembuat->is($adminUser))->toBeTrue();
});
