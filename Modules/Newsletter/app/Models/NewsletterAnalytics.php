<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Newsletter\Database\Factories\NewsletterAnalyticsFactory;

class NewsletterAnalytics extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'newsletter_id',
        'total_recipients',
        'successful_deliveries',
        'failed_deliveries',
        'open_count',
        'click_count',
    ];

    /**
     * Get the newsletter that owns the analytics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function newsletter()
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * Calculate the open rate.
     *
     * @return float
     */
    public function getOpenRateAttribute(): float
    {
        if ($this->successful_deliveries <= 0) {
            return 0;
        }

        return round(($this->open_count / $this->successful_deliveries) * 100, 2);
    }

    /**
     * Calculate the click rate based on opens.
     *
     * @return float
     */
    public function getClickRateAttribute(): float
    {
        if ($this->open_count <= 0) {
            return 0;
        }

        return round(($this->click_count / $this->open_count) * 100, 2);
    }

    /**
     * Calculate the click-to-delivery rate.
     *
     * @return float
     */
    public function getClickToDeliveryRateAttribute(): float
    {
        if ($this->successful_deliveries <= 0) {
            return 0;
        }

        return round(($this->click_count / $this->successful_deliveries) * 100, 2);
    }

    /**
     * Calculate the delivery rate.
     *
     * @return float
     */
    public function getDeliveryRateAttribute(): float
    {
        if ($this->total_recipients <= 0) {
            return 0;
        }

        return round(($this->successful_deliveries / $this->total_recipients) * 100, 2);
    }

    /**
     * Get the unique opens (approximate).
     * This is an estimate since we count total opens.
     *
     * @return int
     */
    public function getUniqueOpensAttribute(): int
    {
        // Since we track total opens, we estimate unique opens
        // Assuming each person opens an email 1.5 times on average
        $estimatedUnique = round($this->open_count / 1.5);
        
        // Ensure we don't return more unique opens than recipients
        return min($estimatedUnique, $this->successful_deliveries);
    }

    /**
     * Get the unique opens rate.
     *
     * @return float
     */
    public function getUniqueOpenRateAttribute(): float
    {
        if ($this->successful_deliveries <= 0) {
            return 0;
        }

        return round(($this->unique_opens / $this->successful_deliveries) * 100, 2);
    }

    /**
     * Get the failure rate.
     *
     * @return float
     */
    public function getFailureRateAttribute(): float
    {
        if ($this->total_recipients <= 0) {
            return 0;
        }

        return round(($this->failed_deliveries / $this->total_recipients) * 100, 2);
    }

    /**
     * Increment a specific counter with validation.
     *
     * @param string $counter
     * @param int $amount
     * @return bool
     */
    public function safeIncrement(string $counter, int $amount = 1): bool
    {
        if (!in_array($counter, $this->fillable)) {
            return false;
        }

        $this->increment($counter, $amount);
        return true;
    }
}
