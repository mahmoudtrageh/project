<?php

namespace Modules\Newsletter\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Newsletter\Emails\NewsletterMail;
use Modules\Newsletter\Models\Newsletter;
use Modules\Newsletter\Models\NewsletterAnalytics;
use Modules\Newsletter\Models\Subscriber;
use Throwable;

class NewsletterService
{
      /**
     * Create a new newsletter.
     *
     * @param array $data
     * @return Newsletter
     */
    public function createNewsletter(array $data)
    {
        try {
            DB::beginTransaction();
            
            $newsletter = Newsletter::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'custom_css' => $data['custom_css'] ?? null,
                'social_links' => $data['social_links'] ?? [],
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'status' => $data['scheduled_at'] ? 'scheduled' : 'draft',
            ]);
            
            // Create analytics record
            NewsletterAnalytics::create([
                'newsletter_id' => $newsletter->id,
                'total_recipients' => 0,
                'successful_deliveries' => 0,
                'failed_deliveries' => 0,
                'open_count' => 0,
                'click_count' => 0,
            ]);
            
            // Attach recipients if provided
            if (!empty($data['recipients'])) {
                $recipients = is_array($data['recipients']) ? $data['recipients'] : [$data['recipients']];
                $newsletter->recipients()->attach($recipients);
                
                // Update analytics
                $newsletter->analytics()->update([
                    'total_recipients' => count($recipients),
                ]);
            }
            
            DB::commit();
            return $newsletter;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create newsletter: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Update an existing newsletter.
     *
     * @param Newsletter $newsletter
     * @param array $data
     * @return Newsletter
     */
    public function updateNewsletter(Newsletter $newsletter, array $data)
    {
        try {
            DB::beginTransaction();
            
            $newsletter->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'custom_css' => $data['custom_css'] ?? $newsletter->custom_css,
                'social_links' => $data['social_links'] ?? $newsletter->social_links,
                'scheduled_at' => $data['scheduled_at'] ?? $newsletter->scheduled_at,
                'status' => $data['scheduled_at'] && $newsletter->status == 'draft' ? 'scheduled' : $newsletter->status,
            ]);
            
            // Update recipients if provided
            if (isset($data['recipients'])) {
                $recipients = is_array($data['recipients']) ? $data['recipients'] : [$data['recipients']];
                $newsletter->recipients()->sync($recipients);
                
                // Update analytics
                $newsletter->analytics()->update([
                    'total_recipients' => count($recipients),
                ]);
            }
            
            DB::commit();
            return $newsletter;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update newsletter: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Send newsletter to all recipients.
     *
     * @param Newsletter $newsletter
     * @return void
     */
    public function sendNewsletter(Newsletter $newsletter)
    {
        try {
            // Update newsletter status
            $newsletter->update(['status' => 'sending']);
            
            // Get all recipients
            $recipients = $newsletter->recipients;
            
            // Send to each recipient
            foreach ($recipients as $recipient) {
                try {
                    // Parse content with placeholders for this subscriber
                    $parsedContent = $this->parseNewsletterContent($newsletter, $recipient);
                    
                    // Generate unsubscribe token
                    $unsubscribeToken = hash('sha256', $recipient->email . config('app.key'));
                    
                    try {
                        // Create the email object separately to help with debugging
                        $email = new NewsletterMail(
                            $newsletter->title,
                            $parsedContent,
                            $newsletter->custom_css,
                            $newsletter->social_links ?? [], // Ensure it's always an array
                            $recipient->email,
                            $unsubscribeToken,
                            $newsletter->id
                        );
                        
                        // Try to send it directly (not queued) to see immediate errors
                        Mail::to($recipient->email)->send($email);
                        
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                        Log::error("Newsletter mail error: " . $e->getMessage());
                        Log::error("Stack trace: " . $e->getTraceAsString());
                        // Don't throw the error so other recipients can be processed
                        
                        // Update pivot table with error
                        $newsletter->recipients()->updateExistingPivot($recipient->id, [
                            'status' => 'failed',
                            'error' => $e->getMessage(),
                        ]);
                        
                        // Increment failed deliveries
                        $newsletter->analytics()->increment('failed_deliveries');
                    }
                    
                    // Update pivot table with success
                    $newsletter->recipients()->updateExistingPivot($recipient->id, [
                        'status' => 'sent',
                        'sent_at' => now(),
                        'error' => null,
                    ]);
                    
                    // Update subscriber last sent date
                    $recipient->update(['last_sent_at' => now()]);
                    
                    // Increment successful deliveries
                    $newsletter->analytics()->increment('successful_deliveries');
                } catch (Throwable $e) {
                    dd($e->getMessage());
                    // Log error and update pivot table
                    Log::error("Failed to send newsletter {$newsletter->id} to subscriber {$recipient->id}: " . $e->getMessage());
                    
                    $newsletter->recipients()->updateExistingPivot($recipient->id, [
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ]);
                    
                    // Increment failed deliveries
                    $newsletter->analytics()->increment('failed_deliveries');
                }
            }
            
            // Update newsletter as sent
            $newsletter->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Update newsletter status to failed
            $newsletter->update(['status' => 'failed']);
            
            Log::error("Failed to process newsletter {$newsletter->id}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Parse newsletter content with placeholders.
     *
     * @param Newsletter $newsletter
     * @param Subscriber|null $subscriber
     * @return string
     */
    public function parseNewsletterContent(Newsletter $newsletter, ?Subscriber $subscriber = null): string
    {
        $content = $newsletter->content;
        
        if ($subscriber) {
            // Replace placeholders with subscriber data
            $placeholders = [
                '{{email}}' => $subscriber->email,
                // Add more placeholders as needed
            ];
            
            $content = str_replace(array_keys($placeholders), array_values($placeholders), $content);
        }
        
        return $content;
    }
    
    /**
     * Track newsletter open.
     *
     * @param string $trackingId
     * @return void
     */
    public function trackOpen(string $trackingId)
    {
        try {
            [$newsletterId, $subscriberId] = explode('_', base64_decode($trackingId));
            
            $newsletter = Newsletter::find($newsletterId);
            if (!$newsletter) {
                return;
            }
            
            // Update pivot table with open time
            $newsletter->recipients()->updateExistingPivot($subscriberId, [
                'opened_at' => now(),
            ]);
            
            // Update analytics
            $newsletter->analytics()->increment('open_count');
        } catch (Throwable $e) {
            Log::error('Failed to track newsletter open: ' . $e->getMessage());
        }
    }
    
    /**
     * Track newsletter link click.
     *
     * @param string $trackingId
     * @param string $url
     * @return string Original URL to redirect to
     */
    public function trackClick(string $trackingId, string $url)
    {
        try {
            [$newsletterId, $subscriberId] = explode('_', base64_decode($trackingId));
            
            $newsletter = Newsletter::find($newsletterId);
            if (!$newsletter) {
                return $url;
            }
            
            // Update analytics
            $newsletter->analytics()->increment('click_count');
            
            // Could also track specific URLs clicked if needed
        } catch (Throwable $e) {
            Log::error('Failed to track newsletter click: ' . $e->getMessage());
        }
        
        return $url;
    }

    /**
     * Parse plain content with placeholders for a subscriber.
     *
     * @param string $content
     * @param Subscriber|null $subscriber
     * @return string
     */
    public function parseContent(string $content, ?Subscriber $subscriber = null): string
    {
        if ($subscriber) {
            // Replace placeholders with subscriber data
            $placeholders = [
                '{{name}}' => $subscriber->name ?? '',
                '{{first_name}}' => $subscriber->first_name ?? '',
                '{{email}}' => $subscriber->email,
                // Add more placeholders as needed
            ];
            
            $content = str_replace(array_keys($placeholders), array_values($placeholders), $content);
        }
        
        return $content;
    }
}