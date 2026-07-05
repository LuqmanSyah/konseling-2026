<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Konselor extends Model
{
    use HasFactory;

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUSES = [
        self::STATUS_AKTIF,
        self::STATUS_NONAKTIF,
    ];

    protected $table = 'konselor';

    protected $fillable = [
        'user_id',
        'nama',
        'bidang',
        'no_hp',
        'email',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalKonseling(): HasMany
    {
        return $this->hasMany(JadwalKonseling::class);
    }

    public function bookingKonseling(): HasMany
    {
        return $this->hasMany(BookingKonseling::class);
    }

    public function catatanKonseling(): HasMany
    {
        return $this->hasMany(CatatanKonseling::class);
    }
}
