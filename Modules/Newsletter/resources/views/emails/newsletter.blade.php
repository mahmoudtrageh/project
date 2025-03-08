<!-- resources/views/emails/newsletter.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Base styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
        .social-links {
            text-align: center;
            padding: 10px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 5px;
        }
        .social-links img {
            width: 32px;
            height: 32px;
        }
        /* Apply custom CSS if provided */
        {{ $customCss ?? '' }}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>
        
        <div class="content">
            {!! $content !!}
        </div>
        
        @if(!empty($socialLinks))
        <div class="social-links">
            @foreach($socialLinks as $platform => $url)
                <a href="{{ route('newsletters.track-click', ['trackingId' => $trackingId, 'url' => $url]) }}" target="_blank">
                    @switch(strtolower($platform))
                        @case('facebook')
                            <img src="{{ asset('images/social/facebook.png') }}" alt="Facebook">
                            @break
                        @case('twitter')
                            <img src="{{ asset('images/social/twitter.png') }}" alt="Twitter">
                            @break
                        @case('instagram')
                            <img src="{{ asset('images/social/instagram.png') }}" alt="Instagram">
                            @break
                        @case('linkedin')
                            <img src="{{ asset('images/social/linkedin.png') }}" alt="LinkedIn">
                            @break
                        @default
                            <img src="{{ asset('images/social/link.png') }}" alt="{{ $platform }}">
                    @endswitch
                </a>
            @endforeach
        </div>
        @endif
        
        <div class="footer">
            <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
            <p>
                <a href="{{ route('subscribers.unsubscribe', ['email' => $email, 'token' => $unsubscribeToken]) }}">Unsubscribe</a>
            </p>
        </div>
    </div>
    
    <!-- Tracking pixel for open tracking -->
    <img src="{{ route('newsletters.track-open', ['trackingId' => $trackingId]) }}" alt="" width="1" height="1" style="display:none;">
</body>
</html>