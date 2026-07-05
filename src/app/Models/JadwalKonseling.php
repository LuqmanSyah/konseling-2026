<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalKonseling extends Model
{
    use HasFactory;

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
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'metode',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
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
