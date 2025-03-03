<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Booking\Database\Factories\BookingFactory;

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
    ];

    protected $casts = [
        'enter_date' => 'date',
        'leave_date' => 'date',
    ];

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
        return $this->belongsTo(Marketer::class);
    }

    /**
     * Get the payments for the booking.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate the number of nights for this booking.
     */
    public function getNightsCountAttribute()
    {
        return $this->enter_date->diffInDays($this->leave_date);
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
     * Calculate the marketer profit for this booking.
     */
    public function getMarketerProfitAttribute()
    {
        return $this->total_client_price - $this->total_marketer_price;
    }

    /**
     * Calculate the admin profit for this booking.
     */
    public function getAdminProfitAttribute()
    {
        if ($this->marketer_id) {
            return $this->total_marketer_price - $this->total_buying_price;
        } else {
            return $this->total_client_price - $this->total_buying_price;
        }
    }

    /**
     * Calculate the remaining balance for the client.
     */
    public function getClientRemainingBalanceAttribute()
    {
        $paid = $this->payments()->where('payment_type', 'client_to_admin')->sum('amount');
        return $this->total_client_price - $paid - $this->deposit;
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
}
