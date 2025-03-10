<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;

// use Modules\Booking\Database\Factories\HotelFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_person',
        'contact_phone',
        'manager_id',
        'admin_id',
    ];

     /**
     * Get the admin that owns the hotel.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the manager of the hotel.
     */
    public function manager()
    {
        return $this->belongsTo(Admin::class, 'manager_id');
    }

    /**
     * Get the bookings for the hotel.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
