<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'showtime_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function bookedSeats()
    {
        return $this->hasMany(BookedSeat::class);
    }
}
