<?php

namespace Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Newsletter\Models\Subscriber;

class SubscriberController extends Controller
{
   /**
     * Subscribe a new email to the newsletter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if subscriber already exists
            $subscriber = Subscriber::where('email', $request->email)->first();

            if ($subscriber) {
                if ($subscriber || !$subscriber->is_active) {
                    // Reactivate subscriber
                    $subscriber->update([
                        'is_active' => true,
                        'unsubscribed_at' => null,
                        'subscribed_at' => now(),
                        'name' => $request->name ?? $subscriber->name,
                        'source' => $request->source ?? $subscriber->source,
                    ]);
                     // Display error message
                     return back()->with('success', 'Welcome back! You have been resubscribed to our newsletter.');

                }

                return back()->with('success', 'You are already subscribed to our newsletter.');
               
            }

            // Create new subscriber
            Subscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'source' => $request->source ?? 'website',
                'subscribed_at' => now(),
            ]);


           return back()->with('success', 'Thank you for subscribing to our newsletter!');

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Subscription error: ' . $e->getMessage());
            
            return back()->with('success', 'An error occurred while processing your subscription. Please try again later.');
            
        }
    }

    /**
     * Unsubscribe from the newsletter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');
        
        // Validate token (this is a simple example - you should implement proper token validation)
        $isValidToken = $this->validateUnsubscribeToken($email, $token);
        
        if (!$isValidToken) {
            return view('newsletter::newsletters.unsubscribed', [
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
                    return view('newsletter::newsletters.unsubscribed', [
                        'success' => true,
                        'message' => 'You have been successfully unsubscribed from our newsletters.',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Unsubscribe error: ' . $e->getMessage());
            }
        }
        
        // Display error message
        return view('newsletter::newsletters.unsubscribed', [
            'success' => false,
            'message' => 'We could not process your unsubscribe request. Please contact support.',
        ]);
    }

    /**
     * Validate unsubscribe token.
     * This is a simple example. In production, use a more secure method.
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

        // Simple token validation example (replace with proper validation in production)
        $expectedToken = hash('sha256', $email . config('app.key'));
        return hash_equals($expectedToken, $token);
    }

    /**
     * Display subscriber management page (admin).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(20);
        return view('newsletter::subscribers.index', compact('subscribers'));
    }

    /**
     * Import subscribers (admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Skip header row
            $header = array_shift($data);
            
            // Find email and name columns
            $emailIndex = array_search('email', array_map('strtolower', $header));
            $nameIndex = array_search('name', array_map('strtolower', $header));
            
            if ($emailIndex === false) {
                return back()->with('error', 'Email column not found in CSV file');
            }
            
            $imported = 0;
            $skipped = 0;
            
            foreach ($data as $row) {
                if (isset($row[$emailIndex]) && filter_var($row[$emailIndex], FILTER_VALIDATE_EMAIL)) {
                    $email = $row[$emailIndex];
                    $name = ($nameIndex !== false && isset($row[$nameIndex])) ? $row[$nameIndex] : null;
                    
                    $subscriber = Subscriber::where('email', $email)->first();
                    
                    if (!$subscriber) {
                        Subscriber::create([
                            'email' => $email,
                            'name' => $name,
                            'source' => 'import',
                            'subscribed_at' => now(),
                        ]);
                        $imported++;
                    } else if ($subscriber || !$subscriber->is_active) {
                        $subscriber;
                        $subscriber->update([
                            'is_active' => true,
                            'name' => $name ?? $subscriber->name,
                            'unsubscribed_at' => null,
                            'subscribed_at' => now(),
                        ]);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                } else {
                    $skipped++;
                }
            }
            
            return back()->with('success', "Imported $imported subscribers. Skipped $skipped invalid or duplicate entries.");
        } catch (\Exception $e) {
            Log::error('Subscriber import error: ' . $e->getMessage());
            return back()->with('error', 'Error importing subscribers: ' . $e->getMessage());
        }
    }

    /**
     * Delete a subscriber (admin).
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscriber $subscriber)
    {
        try {
            $subscriber->delete();
            return back()->with('success', 'Subscriber deleted successfully');
        } catch (\Exception $e) {
            Log::error('Subscriber deletion error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting subscriber: ' . $e->getMessage());
        }
    }

    /**
     * Reactivate a previously unsubscribed subscriber.
     *
     * @param  \Modules\Newsletter\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reactivate(Subscriber $subscriber)
    {
        try {
            $subscriber->update([
                'is_active' => true,
                'unsubscribed_at' => null,
                'subscribed_at' => now(),
            ]);
            
            return back()->with('success', 'Subscriber has been reactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Subscriber reactivation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to reactivate subscriber: ' . $e->getMessage());
        }
    }
}
