<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;

// use Modules\Booking\Database\Factories\MarketerFactory;

class Marketer extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'active',
    ];

    /**
     * Get the user that owns the marketer profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    /**
     * Get the bookings associated with the marketer.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
