<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiSimulasi extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public const CHANNEL_SISTEM = 'sistem';

    public const CHANNEL_WHATSAPP_SIMULASI = 'whatsapp_simulasi';

    public const CHANNEL_EMAIL_SIMULASI = 'email_simulasi';

    public const CHANNELS = [
        self::CHANNEL_SISTEM,
        self::CHANNEL_WHATSAPP_SIMULASI,
        self::CHANNEL_EMAIL_SIMULASI,
    ];

    public const STATUS_TERCATAT = 'tercatat';

    public const STATUS_GAGAL_SIMULASI = 'gagal_simulasi';

    public const STATUSES = [
        self::STATUS_TERCATAT,
        self::STATUS_GAGAL_SIMULASI,
    ];

    protected $table = 'notifikasi_simulasi';

    protected $fillable = [
        'booking_id',
        'penerima_id',
        'jenis',
        'pesan',
        'channel',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'channel' => 'string',
            'status' => 'string',
        ];
    }

    public function bookingKonseling(): BelongsTo
    {
        return $this->belongsTo(BookingKonseling::class, 'booking_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}
