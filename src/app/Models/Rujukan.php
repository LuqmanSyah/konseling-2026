<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rujukan extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $table = 'rujukan';

    protected $fillable = [
        'booking_id',
        'tujuan_rujukan',
        'alasan_rujukan',
        'ringkasan_tindak_lanjut',
        'dibuat_oleh',
    ];

    public function bookingKonseling(): BelongsTo
    {
        return $this->belongsTo(BookingKonseling::class, 'booking_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
