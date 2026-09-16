<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'rows_count',
        'seats_per_row',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
