<?php

namespace App\Models;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{

    protected $table = 'bookings';
    protected $fillable = ['user_id', 'slot_id', 'status'];

    protected $casts = [
        'status' => BookingStatusEnum::class,
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }
}
