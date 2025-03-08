<?php

namespace Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Newsletter\Models\Subscriber;
use Modules\Newsletter\Models\Newsletter;
use Modules\Newsletter\Services\NewsletterService;

class NewsletterTrackingController extends Controller
{
    protected $newsletterService;
    
    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }
    
    /**
     * Track newsletter open.
     *
     * @param string $trackingId
     * @return \Illuminate\Http\Response
     */
    public function trackOpen(string $trackingId)
    {
        try {
            // Track the open in the service
            $this->newsletterService->trackOpen($trackingId);
            
            // Return a 1x1 transparent pixel
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            return response($pixel, 200)
                ->header('Content-Type', 'image/gif')
                ->header('Content-Length', strlen($pixel));
        } catch (\Exception $e) {
            Log::error('Error tracking open: ' . $e->getMessage());
            
            // Still return the pixel even if tracking fails
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            return response($pixel, 200)
                ->header('Content-Type', 'image/gif')
                ->header('Content-Length', strlen($pixel));
        }
    }
    
    /**
     * Track newsletter link click and redirect.
     *
     * @param string $trackingId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function trackClick(string $trackingId, Request $request)
    {
        $url = $request->query('url');
        
        if (empty($url)) {
            return redirect('/');
        }
        
        try {
            // Track the click and get the original URL
            $this->newsletterService->trackClick($trackingId, $url);
        } catch (\Exception $e) {
            Log::error('Error tracking click: ' . $e->getMessage());
        }
        
        // Always redirect to the original URL, even if tracking fails
        return redirect()->away($url);
    }
    
    /**
     * Process unsubscribe request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');
        
        // Validate token
        $isValidToken = $this->validateUnsubscribeToken($email, $token);
        
        if (!$isValidToken) {
            return view('newsletter::unsubscribed', [
                'success' => false,
                'message' => 'Invalid unsubscribe link. Please contact support if you need assistance.',
            ]);
        }

        if ($email) {
            try {
                // Find subscriber by email
                $subscriber = Subscriber::where('email', $email)->first();
                
                if ($subscriber) {
                    // Update subscriber status
                    $subscriber->update([
                        'is_active' => false,
                        'unsubscribed_at' => now(),
                    ]);
                    
                    // Display success message
                    return view('newsletter::unsubscribed', [
                        'success' => true,
                        'message' => 'You have been successfully unsubscribed from our newsletters.',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Unsubscribe error: ' . $e->getMessage());
            }
        }
        
        // Display error message
        return view('newsletter::unsubscribed', [
            'success' => false,
            'message' => 'We could not process your unsubscribe request. Please contact support.',
        ]);
    }

    /**
     * Validate unsubscribe token.
     *
     * @param  string  $email
     * @param  string  $token
     * @return bool
     */
    private function validateUnsubscribeToken($email, $token)
    {
        if (empty($email) || empty($token)) {
            return false;
        }

        // Create the expected token using the same algorithm as when it was generated
        $expectedToken = hash('sha256', $email . config('app.key'));
        
        // Compare tokens using a time-constant comparison to prevent timing attacks
        return hash_equals($expectedToken, $token);
    }
    
    /**
     * Display unsubscribe confirmation page (for manual unsubscribe).
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function showUnsubscribe($email)
    {
        // Find subscriber by email
        $subscriber = Subscriber::where('email', $email)->first();
        
        if (!$subscriber) {
            return view('newsletter::unsubscribe-confirm', [
                'success' => false,
                'message' => 'Email address not found in our system.',
            ]);
        }
        
        // Generate unsubscribe token
        $token = hash('sha256', $email . config('app.key'));
        
        return view('newsletter::unsubscribe-confirm', [
            'email' => $email,
            'token' => $token,
        ]);
    }
}
