@extends('front.layouts.app')

@section('css')
<style>
    img {
        margin: 0 auto;
        border-radius: 12px; /* Rounded corners for images */
    }

    .about-content {
        line-height: 1.8; /* Improved readability with increased line height */
        color: #4a5568; /* Slightly darker text for better contrast */
    }

    .about-content h3 {
        font-size: 1.5rem; /* Larger heading size */
        font-weight: 600; /* Bold heading */
        color: #2d3748; /* Darker heading color */
        margin-top: 2rem; /* Spacing above headings */
        margin-bottom: 1rem; /* Spacing below headings */
    }

    .about-content p {
        margin-bottom: 1.5rem; /* Spacing between paragraphs */
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 1.5rem; /* Space between social icons */
        margin-top: 2rem; /* Spacing above social links */
    }

    .social-links a {
        transition: transform 0.3s ease, color 0.3s ease; /* Smooth hover effects */
    }

    .social-links a:hover {
        transform: translateY(-5px); /* Lift icons on hover */
        color: #4c51bf; /* Change color on hover */
    }

    .gradient-underline {
        position: relative;
        display: inline-block;
    }

    .gradient-underline::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px; /* Adjust underline position */
        width: 100%;
        height: 4px; /* Thicker underline */
        background: linear-gradient(90deg, #3b82f6, #8b5cf6); /* Gradient underline */
        border-radius: 2px; /* Rounded corners for underline */
    }
</style>
@endsection

@section('content')
<!-- About Content -->
<div class="pt-24 pb-16 bg-linear-to-b from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Heading -->
        <h2 class="text-4xl font-bold text-gray-900 mb-12 text-center">
            <span class="gradient-underline">About Me</span>
        </h2>

        <!-- About Content -->
        <div class="about-content">
            {!! $about_page->content !!}
        </div>

        <!-- Social Links -->
        <div class="social-links">
            <a href="{{ settings()->get('social_github') }}" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="github" class="w-8 h-8"></i>
            </a>
            <a href="{{ settings()->get('social_twitter') }}" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="twitter" class="w-8 h-8"></i>
            </a>
            <a href="{{ settings()->get('social_linkedin') }}" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="linkedin" class="w-8 h-8"></i>
            </a>
            <a href="mailto:{{ settings()->get('site_email') }}?subject=Hello&body=I want to get in touch" class="text-gray-600 hover:text-gray-900">
                <i data-lucide="mail" class="w-8 h-8"></i>
            </a>
        </div>
    </div>
</div>
@endsection