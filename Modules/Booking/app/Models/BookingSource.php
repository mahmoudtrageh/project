<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;

// use Modules\Booking\Database\Factories\BookingSourceFactory;

class BookingSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'admin_id',
    ];

    /**
     * Get the bookings from this source.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

     /**
     * Get the admin that owns the booking source.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
