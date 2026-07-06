<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalKonseling extends Model
{
    use HasFactory;

    public const HARI_SENIN = 'senin';

    public const HARI_SELASA = 'selasa';

    public const HARI_RABU = 'rabu';

    public const HARI_KAMIS = 'kamis';

    public const HARI_JUMAT = 'jumat';

    public const HARI_SABTU = 'sabtu';

    public const HARI_MINGGU = 'minggu';

    public const HARIS = [
        self::HARI_SENIN,
        self::HARI_SELASA,
        self::HARI_RABU,
        self::HARI_KAMIS,
        self::HARI_JUMAT,
        self::HARI_SABTU,
        self::HARI_MINGGU,
    ];

    public static function hariOrderCase(string $column = 'hari'): string
    {
        return sprintf(
            "case %s when '%s' then 1 when '%s' then 2 when '%s' then 3 when '%s' then 4 when '%s' then 5 when '%s' then 6 when '%s' then 7 else 8 end",
            $column,
            self::HARI_SENIN,
            self::HARI_SELASA,
            self::HARI_RABU,
            self::HARI_KAMIS,
            self::HARI_JUMAT,
            self::HARI_SABTU,
            self::HARI_MINGGU,
        );
    }

    public const METODE_ONLINE = 'online';

    public const METODE_TATAP_MUKA = 'tatap_muka';

    public const METODES = [
        self::METODE_ONLINE,
        self::METODE_TATAP_MUKA,
    ];

    public const STATUS_TERSEDIA = 'tersedia';

    public const STATUS_TERPAKAI = 'terpakai';

    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';

    public const STATUSES = [
        self::STATUS_TERSEDIA,
        self::STATUS_TERPAKAI,
        self::STATUS_TIDAK_AKTIF,
    ];

    protected $table = 'jadwal_konseling';

    protected $fillable = [
        'konselor_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'metode',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'hari' => 'string',
            'jam_mulai' => 'datetime:H:i:s',
            'jam_selesai' => 'datetime:H:i:s',
            'metode' => 'string',
            'status' => 'string',
        ];
    }

    public function konselor(): BelongsTo
    {
        return $this->belongsTo(Konselor::class);
    }

    public function bookingKonseling(): HasMany
    {
        return $this->hasMany(BookingKonseling::class, 'jadwal_id');
    }
}
