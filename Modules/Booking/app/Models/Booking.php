<?php

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'client_name',
        'client_phone',
        'enter_date',
        'leave_date',
        'client_sell_price',
        'marketer_sell_price',
        'buying_price',
        'deposit',
        'note',
        'room_type_id',
        'rooms_number',
        'booking_source_id',
        'marketer_id',
        'status',
        'admin_id',
        'total_client_price',
        'payment_status',
        'booking_number'
    ];

    protected $casts = [
        'enter_date' => 'date',
        'leave_date' => 'date',
        'client_sell_price' => 'float',
        'marketer_sell_price' => 'float',
        'buying_price' => 'float',
    ];

    /**
     * Get the admin that owns the booking.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the hotel associated with the booking.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the room type associated with the booking.
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get the booking source associated with the booking.
     */
    public function bookingSource()
    {
        return $this->belongsTo(BookingSource::class);
    }

    /**
     * Get the marketer associated with the booking.
     */
    public function marketer()
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }

    /**
     * Get the payments for the booking.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate the total client price for this booking.
     */
    public function getTotalClientPriceAttribute()
    {
        return $this->client_sell_price * $this->rooms_number * $this->nights_count;
    }

    /**
     * Calculate the total marketer price for this booking.
     */
    public function getTotalMarketerPriceAttribute()
    {
        return $this->marketer_sell_price * $this->rooms_number * $this->nights_count;
    }

    /**
     * Calculate the total buying price for this booking.
     */
    public function getTotalBuyingPriceAttribute()
    {
        return $this->buying_price * $this->rooms_number * $this->nights_count;
    }


    /**
     * Calculate the remaining balance for the client.
     */
    public function getClientRemainingBalanceAttribute()
    {
        return $this->total_client_price - ($this->deposit ?? 0);
    }

    /**
     * Calculate the remaining balance to pay to the hotel.
     */
    public function getHotelRemainingBalanceAttribute()
    {
        $paid = $this->payments()->where('payment_type', 'admin_to_hotel')->sum('amount');
        return $this->total_buying_price - $paid;
    }

    /**
     * Calculate the remaining balance to pay to the marketer.
     */
    public function getMarketerRemainingBalanceAttribute()
    {
        if (!$this->marketer_id) {
            return 0;
        }
        
        $paid = $this->payments()->where('payment_type', 'admin_to_marketer')->sum('amount');
        return $this->marketer_profit - $paid;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                // Find the latest booking number
                $lastBooking = static::orderBy('id', 'desc')->first();
                
                if ($lastBooking && $lastBooking->booking_number) {
                    // Extract the numeric part and increment
                    $numberPart = intval(str_replace('B-', '', $lastBooking->booking_number));
                    $booking->booking_number = 'B-' . ($numberPart + 1);
                } else {
                    // Start with B-1000 if no bookings exist
                    $booking->booking_number = 'B-1000';
                }
            }
        });
    }

    // Add these to your Booking model

/**
 * Calculate marketer profit
 */
public function getMarketerProfitAttribute()
{
    if (!$this->marketer_id) {
        return 0;
    }
    
    $nights = $this->getNightsCountAttribute();
    return ($this->client_sell_price - $this->marketer_sell_price) * $nights * $this->rooms_number;
}

/**
 * Calculate admin profit
 */
public function getAdminProfitAttribute()
{
    $nights = $this->getNightsCountAttribute();
    
    // If there's a marketer, admin profit is based on marketer_sell_price
    if ($this->marketer_id) {
        return ($this->marketer_sell_price - $this->buying_price) * $nights * $this->rooms_number;
    }
    
    // If there's no marketer, admin gets the full profit
    return ($this->client_sell_price - $this->buying_price) * $nights * $this->rooms_number;
}

/**
 * Calculate nights count
 */
public function getNightsCountAttribute()
{
    $enterDate = Carbon::parse($this->enter_date);
    $leaveDate = Carbon::parse($this->leave_date);
    return $enterDate->diffInDays($leaveDate);
}
}