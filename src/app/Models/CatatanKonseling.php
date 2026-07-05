<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanKonseling extends Model
{
    use HasFactory;

    protected $table = 'catatan_konseling';

    protected $fillable = [
        'booking_id',
        'konselor_id',
        'catatan_hasil',
        'rekomendasi',
    ];

    public function bookingKonseling(): BelongsTo
    {
        return $this->belongsTo(BookingKonseling::class, 'booking_id');
    }

    public function konselor(): BelongsTo
    {
        return $this->belongsTo(Konselor::class);
    }
}
