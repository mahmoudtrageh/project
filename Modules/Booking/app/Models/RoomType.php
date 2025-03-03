<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Booking\Database\Factories\RoomTypeFactory;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the bookings for this room type.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
