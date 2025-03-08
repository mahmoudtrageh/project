<?php

namespace Modules\Newsletter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Newsletter\Http\Requests\NewsletterRequest;
use Modules\Newsletter\Models\Newsletter;
use Modules\Newsletter\Models\Subscriber;
use Modules\Newsletter\Services\NewsletterService;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Display a listing of newsletters.
     */
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(5);
        return view('newsletter::newsletters.index', compact('newsletters'));
    }

    /**
     * Show the form for creating a new newsletter.
     */
    public function create()
    {
        $subscribers = Subscriber::active()->get();
        return view('newsletter::newsletters.create', compact('subscribers'));
    }

    /**
     * Store a newly created newsletter in storage.
     */
    public function store(NewsletterRequest $request)
    {
        try {
            $newsletter = $this->newsletterService->createNewsletter($request->validated());
            
            return redirect()->route('newsletters.show', $newsletter)
                ->with('success', 'Newsletter created successfully!');
        } catch (\Exception $e) {
            Log::error('Newsletter creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create newsletter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified newsletter.
     */
    public function show(Newsletter $newsletter)
    {
        $newsletter->load('recipients', 'analytics');
        return view('newsletter::newsletters.show', compact('newsletter'));
    }

    /**
     * Show the form for editing the specified newsletter.
     */
    public function edit(Newsletter $newsletter)
    {
        $subscribers = Subscriber::active()->get();
        $selectedSubscribers = $newsletter->recipients->pluck('id')->toArray();
        
        return view('newsletter::newsletters.edit', compact('newsletter', 'subscribers', 'selectedSubscribers'));
    }

    /**
     * Update the specified newsletter in storage.
     */
    public function update(NewsletterRequest $request, Newsletter $newsletter)
    {
        try {
            $this->newsletterService->updateNewsletter($newsletter, $request->validated());
            
            return redirect()->route('newsletters.show', $newsletter)
                ->with('success', 'Newsletter updated successfully!');
        } catch (\Exception $e) {
            Log::error('Newsletter update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update newsletter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send the newsletter to selected recipients.
     */
    public function send(Newsletter $newsletter)
    {
        try {
            $this->newsletterService->sendNewsletter($newsletter);
            
            return redirect()->route('newsletters.show', $newsletter)
                ->with('success', 'Newsletter sending initiated!');
        } catch (\Exception $e) {
            Log::error('Newsletter sending failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send newsletter: ' . $e->getMessage());
        }
    }

    /**
     * Preview the newsletter.
     */
    public function preview(Newsletter $newsletter, Request $request)
    {
        $subscriberId = $request->input('subscriber_id');
        $subscriber = $subscriberId ? Subscriber::find($subscriberId) : null;
        
        $content = $this->newsletterService->parseNewsletterContent($newsletter, $subscriber);
        $css = $newsletter->custom_css ?? '';
        $socialLinks = $newsletter->social_links ?? [];
        
        return view('newsletter::newsletters.preview', compact('newsletter', 'content', 'css', 'socialLinks'));
    }

    /**
     * Remove the specified newsletter from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        try {
            $newsletter->delete();
            return redirect()->route('newsletters.index')
                ->with('success', 'Newsletter deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Newsletter deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete newsletter: ' . $e->getMessage());
        }
    }

    public function previewDraft(Request $request)
    {
        // Get the form data
        $title = $request->input('title');
        $content = $request->input('content');
        $customCss = $request->input('custom_css');
        $subscriberId = $request->input('subscriber_id');
        
        // Get subscriber if ID is provided
        $subscriber = $subscriberId ? Subscriber::find($subscriberId) : null;
        
        // Parse content with placeholders
        $parsedContent = $this->newsletterService->parseContent($content, $subscriber);
        
        // Get all subscribers for preview dropdown
        $subscribers = Subscriber::active()->get();
        
        return view('newsletter::newsletters.preview', [
            'title' => $title,
            'content' => $parsedContent,
            'css' => $customCss,
            'subscribers' => $subscribers
        ]);
    }
}
