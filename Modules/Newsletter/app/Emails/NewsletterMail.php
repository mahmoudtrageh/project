<?php

namespace Modules\Newsletter\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The newsletter title.
     *
     * @var string
     */
    public $title;
    
    /**
     * The newsletter content.
     *
     * @var string
     */
    public $content;
    
    /**
     * The custom CSS for the newsletter.
     *
     * @var string|null
     */
    public $customCss;
    
    /**
     * The social links for the newsletter.
     *
     * @var array
     */
    public $socialLinks;
    
    /**
     * The tracking ID for this email.
     *
     * @var string
     */
    public $trackingId;

    /**
     * The subscriber's email.
     *
     * @var string
     */
    public $subscriberEmail;

    /**
     * The unsubscribe token.
     *
     * @var string
     */
    public $unsubscribeToken;

    /**
     * Create a new message instance.
     *
     * @param string $title
     * @param string $content
     * @param string|null $customCss
     * @param array $socialLinks
     * @param string $subscriberEmail
     * @param string $unsubscribeToken
     * @param int|null $newsletterId
     * @return void
     */
    public function __construct(
        string $title, 
        string $content, 
        ?string $customCss = null, 
        array $socialLinks = [],
        string $subscriberEmail = '',
        string $unsubscribeToken = '',
        ?int $newsletterId = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->customCss = $customCss;
        $this->socialLinks = $socialLinks;
        $this->subscriberEmail = $subscriberEmail;
        $this->unsubscribeToken = $unsubscribeToken;
        
        // Create tracking ID for opens/clicks
        $this->trackingId = base64_encode(($newsletterId ?? 'draft') . '_' . $subscriberEmail);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'newsletter::emails.newsletter',
            with: [
                'title' => $this->title,
                'content' => $this->content,
                'customCss' => $this->customCss,
                'socialLinks' => $this->socialLinks,
                'trackingId' => $this->trackingId,
                'email' => $this->subscriberEmail,
                'unsubscribeToken' => $this->unsubscribeToken,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
