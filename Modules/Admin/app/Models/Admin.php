<?php

namespace Modules\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Booking\Models\Hotel;
use Modules\Booking\Models\Marketer;

// use Modules\Admin\Database\Factories\AdminFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
        'is_active',
        'last_login_at',
        'phone',
        'user_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user is marketer
     */
    public function isMarketer()
    {
        return $this->user_type === 'marketer';
    }

    /**
     * Check if user is hotel manager
     */
    public function isHotelManager()
    {
        return $this->user_type === 'hotel_manager';
    }

    /**
     * Get the marketer profile associated with the user.
     */
    public function marketerProfile()
    {
        return $this->hasOne(Marketer::class);
    }

    /**
     * Get the hotels managed by this user.
     */
    public function managedHotels()
    {
        return $this->hasMany(Hotel::class, 'manager_id');
    }
}
