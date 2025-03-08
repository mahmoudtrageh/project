<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $newsletter->title ?? 'Newsletter' }}</title>
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
            margin: 60px auto 0;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        .social-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f0f0f0;
            color: #555;
            text-decoration: none;
            font-weight: bold;
        }
        .preview-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #1f2937;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .preview-title {
            font-weight: 600;
            font-size: 16px;
            margin-right: 15px;
        }
        .preview-subscriber {
            display: flex;
            align-items: center;
        }
        .preview-subscriber select {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #4b5563;
            background-color: #374151;
            color: white;
            margin-left: 8px;
            outline: none;
        }
        .preview-close {
            background-color: #4b5563;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .preview-close:hover {
            background-color: #6b7280;
        }
        
        /* Apply custom CSS if provided */
        {{ $css ?? '' }}
    </style>
</head>
<body>
    <div class="preview-toolbar">
        <div class="flex items-center">
            <span class="preview-title">Preview: {{ $newsletter->title ?? 'Newsletter' }}</span>
            <div class="preview-device-toggle">
                <button onclick="setPreviewWidth('100%')" class="px-2 py-1 bg-gray-700 text-white rounded text-xs mr-1">Desktop</button>
                <button onclick="setPreviewWidth('414px')" class="px-2 py-1 bg-gray-700 text-white rounded text-xs">Mobile</button>
            </div>
        </div>
        
        <div class="preview-subscriber">
            <form id="previewForm" action="{{ route('newsletters.preview', $newsletter ?? 0) }}" method="GET">
                <label for="subscriber_id" class="text-sm">Preview as:</label>
                <select name="subscriber_id" id="subscriber_id" onchange="document.getElementById('previewForm').submit();">
                    <option value="">Generic Subscriber</option>
                    @foreach($subscribers ?? [] as $subscriber)
                        <option value="{{ $subscriber->id }}" {{ request('subscriber_id') == $subscriber->id ? 'selected' : '' }}>
                            {{ $subscriber->name ? $subscriber->name . ' (' . $subscriber->email . ')' : $subscriber->email }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <button onclick="window.close()" class="preview-close">Close Preview</button>
    </div>

    <div id="previewContainer" style="max-width: 100%; margin: 0 auto;">
        <div class="container">
            <div class="header">
                <h1>{{ $newsletter->title ?? 'Newsletter Title' }}</h1>
            </div>
            
            <div class="content">
                {!! $content ?? 'Newsletter content will appear here.' !!}
            </div>
            
            @if(!empty($socialLinks))
            <div class="social-links">
                @foreach($socialLinks as $platform => $url)
                    <a href="{{ $url }}" target="_blank" class="social-icon">
                        @switch(strtolower($platform))
                            @case('facebook')
                                <i class="fab fa-facebook-f"></i>
                                @break
                            @case('twitter')
                            @case('x')
                                <i class="fab fa-twitter"></i>
                                @break
                            @case('instagram')
                                <i class="fab fa-instagram"></i>
                                @break
                            @case('linkedin')
                                <i class="fab fa-linkedin-in"></i>
                                @break
                            @default
                                {{ substr(ucfirst($platform), 0, 1) }}
                        @endswitch
                    </a>
                @endforeach
            </div>
            @endif
            
            <div class="footer">
                <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
                <p>
                    <a href="#" style="color: #777; text-decoration: underline;">Unsubscribe</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function setPreviewWidth(width) {
            document.getElementById('previewContainer').style.maxWidth = width;
            
            // Center the container
            if (width !== '100%') {
                document.getElementById('previewContainer').style.margin = '0 auto';
            }
        }
    </script>
    
    <!-- Include Font Awesome for social icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>