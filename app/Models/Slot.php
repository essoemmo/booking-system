<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slot extends Model
{
    protected $table = 'slots';

    protected $fillable = [
        'start_time',
        'end_time',
        'is_available',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
