<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Newsletter\Database\Factories\NewsletterFactory;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'custom_css',
        'social_links',
        'scheduled_at',
        'sent_at',
        'status', // draft, scheduled, sending, sent, failed
    ];

    protected $casts = [
        'social_links' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the subscribers this newsletter was sent to.
     */
    public function recipients()
    {
        return $this->belongsToMany(Subscriber::class, 'newsletter_subscriber')
            ->withPivot('status', 'sent_at', 'opened_at', 'error')
            ->withTimestamps();
    }
    
    /**
     * Get newsletter analytics.
     */
    public function analytics()
    {
        return $this->hasOne(NewsletterAnalytics::class);
    }
}
