<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\Models\Admin;

// use Modules\Booking\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_type',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
        'admin_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    /**
     * Get the booking associated with the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

     /**
     * Get the admin that owns the payment.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
