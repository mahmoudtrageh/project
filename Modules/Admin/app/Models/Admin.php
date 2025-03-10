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
     * Check if user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->user_type === 'super_admin';
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user is a hotel manager.
     *
     * @return bool
     */
    public function isHotelManager()
    {
        return $this->user_type === 'hotel_manager';
    }

    /**
     * Check if user is a marketer.
     *
     * @return bool
     */
    public function isMarketer()
    {
        return $this->user_type === 'marketer';
    }

    /**
     * Get the marketer profile associated with the user.
     */
    public function marketerProfile()
    {
        return $this->hasOne(\Modules\Booking\Models\Marketer::class);
    }

    /**
     * Get the hotels managed by the user.
     */
    public function managedHotels()
    {
        return $this->hasMany(\Modules\Booking\Models\Hotel::class, 'manager_id');
    }

    /**
     * Get the bookings associated with the admin user.
     */
    public function bookings()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\Modules\Booking\Models\Booking::class, 'admin_id');
        }
        
        return null;
    }

    /**
     * Get the hotels associated with the admin user.
     */
    public function hotels()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\Modules\Booking\Models\Hotel::class, 'admin_id');
        }
        
        return null;
    }

    /**
     * Get the marketers associated with the admin user.
     */
    public function marketers()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\Modules\Booking\Models\Marketer::class, 'admin_id');
        }
        
        return null;
    }

    /**
     * Get the booking sources associated with the admin user.
     */
    public function bookingSources()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\Modules\Booking\Models\BookingSource::class, 'admin_id');
        }
        
        return null;
    }

    /**
     * Get the payments associated with the admin user.
     */
    public function payments()
    {
        if ($this->isAdmin()) {
            return $this->hasMany(\Modules\Booking\Models\Payment::class, 'admin_id');
        }
        
        return null;
    }
}
