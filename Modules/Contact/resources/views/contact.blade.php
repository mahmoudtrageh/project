@extends('front.layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8 mt-20">
    <!-- Contact Header -->
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
      <p class="text-gray-600">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      <!-- Contact Information -->
      <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
        
        <!-- Contact Details -->
        <div class="space-y-6">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900">Phone</h3>
              <p class="mt-1 text-gray-600">{{settings()->get('site_phone')}}</p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900">Email</h3>
              <p class="mt-1 text-gray-600">{{settings()->get('site_email')}}</p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900">Location</h3>
              <p class="mt-1 text-gray-600">{{settings()->get('location')}}</p>
            </div>
          </div>
        </div>

        <!-- Social Media Links -->
        <div class="mt-8">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Follow Us</h3>
          <div class="mb-12">
            <div class="flex justify-center space-x-6 mt-8">
                <a href="{{settings()->get('social_github')}}" class="text-gray-600 hover:text-gray-900">
                  <i data-lucide="github" class="w-8 h-8"></i>
                </a>
                <a href="{{settings()->get('social_twitter')}}" class="text-gray-600 hover:text-gray-900">
                  <i data-lucide="twitter" class="w-8 h-8"></i>
                </a>
                <a href="{{settings()->get('social_linkedin')}}" class="text-gray-600 hover:text-gray-900">
                  <i data-lucide="linkedin" class="w-8 h-8"></i>
                </a>
                <a href="mailto:{{settings()->get('site_email')}}?subject=Hello&body=I want to get in touch" class="text-gray-600 hover:text-gray-900">
                  <i data-lucide="mail" class="w-8 h-8"></i>
                </a>
            </div>
        </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="bg-white rounded-lg shadow-md p-8">
        <form class="space-y-6" action="{{route('contact.store')}}" method="POST">
            @csrf
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <input type="text" id="subject" name="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
          </div>

          <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
