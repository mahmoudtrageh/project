<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\Admin;

// use Modules\Booking\Database\Factories\MarketerFactory;

class Marketer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commission_percentage',
        'active',
    ];

    /**
     * Get the user that owns the marketer profile.
     */
    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the bookings associated with the marketer.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
