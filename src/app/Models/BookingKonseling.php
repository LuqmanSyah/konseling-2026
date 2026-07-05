<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingKonseling extends Model
{
    use HasFactory;

    public const KATEGORI_AKADEMIK = 'akademik';

    public const KATEGORI_NON_AKADEMIK = 'non_akademik';

    public const KATEGORIS = [
        self::KATEGORI_AKADEMIK,
        self::KATEGORI_NON_AKADEMIK,
    ];

    public const METODE_ONLINE = 'online';

    public const METODE_TATAP_MUKA = 'tatap_muka';

    public const METODES = [
        self::METODE_ONLINE,
        self::METODE_TATAP_MUKA,
    ];

    public const STATUS_DIAJUKAN = 'diajukan';

    public const STATUS_DIJADWALKAN = 'dijadwalkan';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIRUJUK = 'dirujuk';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUSES = [
        self::STATUS_DIAJUKAN,
        self::STATUS_DIJADWALKAN,
        self::STATUS_SELESAI,
        self::STATUS_DIRUJUK,
        self::STATUS_DIBATALKAN,
    ];

    protected $table = 'booking_konseling';

    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'kategori' => 'string',
            'metode' => 'string',
            'status' => 'string',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jadwalKonseling(): BelongsTo
    {
        return $this->belongsTo(JadwalKonseling::class, 'jadwal_id');
    }

    public function konselor(): BelongsTo
    {
        return $this->belongsTo(Konselor::class);
    }

    public function catatanKonseling(): HasOne
    {
        return $this->hasOne(CatatanKonseling::class, 'booking_id');
    }

    public function rujukan(): HasOne
    {
        return $this->hasOne(Rujukan::class, 'booking_id');
    }

    public function notifikasiSimulasi(): HasMany
    {
        return $this->hasMany(NotifikasiSimulasi::class, 'booking_id');
    }
}
