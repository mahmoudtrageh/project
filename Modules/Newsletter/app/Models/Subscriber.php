<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Newsletter\Database\Factories\SubscriberFactory;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'source',
        'last_sent_at',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Get the newsletters this subscriber has received.
     */
    public function newsletters()
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_subscriber')
            ->withPivot('status', 'sent_at', 'opened_at', 'error')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the first name from the full name.
     */
    public function getFirstNameAttribute()
    {
        $nameParts = explode(' ', $this->name);
        return $nameParts[0] ?? $this->name ?? '';
    }
}
