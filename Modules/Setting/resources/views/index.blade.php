@extends('admin.layouts.app')

@section('content')
    <div class=" py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your application settings and preferences</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="flex border-b border-gray-200">
                <button id="tab1"
                    class="tab-button flex-1 px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300 active">
                    <i class="fas fa-info-circle mr-2"></i>
                    Info
                </button>
                <button id="tab2"
                    class="tab-button flex-1 px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
                    <i class="fas fa-cog mr-2"></i>
                    Seo
                </button>
                <button id="tab3"
                    class="tab-button flex-1 px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
                    <i class="fas fa-sitemap mr-2"></i>
                    Sitemap
                </button>
                <button id="tab4"
                    class="tab-button flex-1 px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
                    <i class="fas fa-code mr-2"></i>
                    Custom Code
                </button>
            </div>

            <div class="p-6">
                <!-- Info Tab Content -->
                <div id="content1" class="tab-content space-y-8">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-8">
                        @csrf
                        
                        <!-- Language Tabs for Translations -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Content Translations</h3>
                                <nav class="flex -mb-px">
                                    @foreach(config('app.available_locales') as $locale)
                                        <button type="button" 
                                                onclick="switchLanguageTab('{{ $locale }}')"
                                                id="tab-{{ $locale }}" 
                                                class="language-tab {{ $locale == app()->getLocale() ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm">
                                            {{ strtoupper($locale) }}
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                        
                        <!-- Translated Content -->
                        @foreach(config('app.available_locales') as $locale)
                            <div id="content-{{ $locale }}" class="language-content {{ $locale == app()->getLocale() ? 'block' : 'hidden' }} space-y-8">
                                <!-- General Settings -->
                                <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900">General Settings ({{ strtoupper($locale) }})</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Site Name -->
                                        <div class="space-y-2">
                                            <label for="site_name_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Site Name ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" 
                                                   name="translations[site_name][{{ $locale }}]" 
                                                   id="site_name_{{ $locale }}"
                                                   value="{{ isset(json_decode(settings()->get('site_name'))->{$locale}) ? json_decode(settings()->get('site_name'))->{$locale} : '' }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                   placeholder="Enter site name in {{ strtoupper($locale) }}">
                                        </div>
                                        
                                        <!-- Site Email (non-translatable) -->
                                        @if($locale == app()->getFallbackLocale())
                                        <div class="space-y-2">
                                            <label for="site_email" class="block text-sm font-medium text-gray-700">Site Email</label>
                                            <input type="email" 
                                                   name="site_email" 
                                                   id="site_email"
                                                   value="{{ settings()->get('site_email') }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                        </div>
                                        @else
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-500">Site Email</label>
                                            <input type="email" 
                                                   value="{{ settings()->get('site_email') }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed"
                                                   disabled>
                                            <p class="text-xs text-gray-500">This field is not translatable</p>
                                        </div>
                                        @endif

                                        <!-- Site Phone (non-translatable) -->
                                        @if($locale == app()->getFallbackLocale())
                                        <div class="space-y-2">
                                            <label for="site_phone" class="block text-sm font-medium text-gray-700">Site Phone</label>
                                            <input type="text" 
                                                   name="site_phone" 
                                                   id="site_phone"
                                                   value="{{ settings()->get('site_phone') }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                        </div>
                                        @else
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-500">Site Phone</label>
                                            <input type="text" 
                                                   value="{{ settings()->get('site_phone') }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed"
                                                   disabled>
                                            <p class="text-xs text-gray-500">This field is not translatable</p>
                                        </div>
                                        @endif

                                        <!-- About Name -->
                                        <div class="space-y-2">
                                            <label for="about_name_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                About Name ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" 
                                                   name="translations[about_name][{{ $locale }}]" 
                                                   id="about_name_{{ $locale }}"
                                                   value="{{ isset(json_decode(settings()->get('about_name'))->{$locale}) ? json_decode(settings()->get('about_name'))->{$locale} : '' }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                   placeholder="Enter about name in {{ strtoupper($locale) }}">
                                        </div>
                                    </div>

                                    <!-- Descriptions -->
                                    <div class="space-y-6">
                                        <div class="space-y-2">
                                            <label for="site_description_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Site Description ({{ strtoupper($locale) }})
                                            </label>
                                            <textarea name="translations[site_description][{{ $locale }}]" 
                                                      id="site_description_{{ $locale }}" 
                                                      rows="3"
                                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                      placeholder="Enter site description in {{ strtoupper($locale) }}">{{ isset(json_decode(settings()->get('site_description'))->{$locale}) ? json_decode(settings()->get('site_description'))->{$locale} : '' }}</textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="about_description_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                About Description ({{ strtoupper($locale) }})
                                            </label>
                                            <textarea name="translations[about_description][{{ $locale }}]" 
                                                      id="about_description_{{ $locale }}" 
                                                      rows="3"
                                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                      placeholder="Enter about description in {{ strtoupper($locale) }}">{{ isset(json_decode(settings()->get('about_description'))->{$locale}) ? json_decode(settings()->get('about_description'))->{$locale} : '' }}</textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="location_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Location ({{ strtoupper($locale) }})
                                            </label>
                                            <textarea name="translations[location][{{ $locale }}]" 
                                                      id="location_{{ $locale }}" 
                                                      rows="3"
                                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                      placeholder="Enter location in {{ strtoupper($locale) }}">{{ isset(json_decode(settings()->get('location'))->{$locale}) ? json_decode(settings()->get('location'))->{$locale} : '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer Settings -->
                                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Footer Settings ({{ strtoupper($locale) }})</h3>

                                    <div class="space-y-2">
                                        <label for="footer_text_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                            Footer Text ({{ strtoupper($locale) }})
                                        </label>
                                        <textarea name="translations[footer_text][{{ $locale }}]" 
                                                  id="footer_text_{{ $locale }}" 
                                                  rows="2"
                                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                  placeholder="Enter footer text in {{ strtoupper($locale) }}">{{ isset(json_decode(settings()->get('footer_text'))->{$locale}) ? json_decode(settings()->get('footer_text'))->{$locale} : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Current Site Description Preview with Locale -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Current Site Description</h3>
                            
                            <div class="space-y-4">
                                @foreach(config('app.available_locales') as $locale)
                                    <div class="p-4 bg-white rounded-lg shadow-sm">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">{{ strtoupper($locale) }}</span>
                                            <h4 class="text-sm font-medium text-gray-700">Site Description</h4>
                                        </div>
                                        <p class="text-gray-600">
                                            @php
                                                $siteDescription = json_decode(settings()->get('site_description'));
                                            @endphp
                                            {{ isset($siteDescription->{$locale}) ? $siteDescription->{$locale} : 'No translation available for this language' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Media Settings (non-translatable) -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Media Settings</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Site Logo -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">Site Logo</label>
                                    @if(settings()->get('logo'))
                                        <div class="relative group rounded-lg overflow-hidden bg-gray-100 p-2">
                                            <img src="{{ Storage::url(settings()->get('logo')) }}" alt="Site Logo"
                                                class="h-24 w-auto mx-auto object-contain">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <span class="text-white text-sm">Change Logo</span>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="logo"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150">
                                </div>

                                <!-- Site Favicon -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">Site Favicon</label>
                                    @if(settings()->get('favicon'))
                                        <div class="relative group rounded-lg overflow-hidden bg-gray-100 p-2">
                                            <img src="{{ Storage::url(settings()->get('favicon')) }}" alt="Favicon"
                                                class="h-16 w-auto mx-auto object-contain">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <span class="text-white text-sm">Change Favicon</span>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="favicon"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150">
                                </div>

                                <!-- About Image -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">About Image</label>
                                    @if(settings()->get('about_image'))
                                        <div class="relative group rounded-lg overflow-hidden bg-gray-100 p-2">
                                            <img src="{{ Storage::url(settings()->get('about_image')) }}" alt="About Image"
                                                class="h-24 w-auto mx-auto object-contain">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <span class="text-white text-sm">Change Image</span>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="about_image"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150">
                                </div>

                                <!-- Hero Image -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700">Hero Image</label>
                                    @if(settings()->get('hero_image'))
                                        <div class="relative group rounded-lg overflow-hidden bg-gray-100 p-2">
                                            <img src="{{ Storage::url(settings()->get('hero_image')) }}" alt="Hero Image"
                                                class="h-16 w-auto mx-auto object-contain">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                <span class="text-white text-sm">Change Image</span>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="hero_image"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Settings (non-translatable) -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Social Media Links</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label for="social_github" class="block text-sm font-medium text-gray-700">Github
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-github text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_github" id="social_github"
                                            value="{{ settings()->get('social_github') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="social_twitter" class="block text-sm font-medium text-gray-700">Twitter
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-twitter text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_twitter" id="social_twitter"
                                            value="{{ settings()->get('social_twitter') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="social_linkedin" class="block text-sm font-medium text-gray-700">LinkedIn
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-linkedin text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_linkedin" id="social_linkedin"
                                            value="{{ settings()->get('social_linkedin') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="social_instagram" class="block text-sm font-medium text-gray-700">Instagram
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-instagram text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_instagram" id="social_instagram"
                                            value="{{ settings()->get('social_instagram') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="social_tiktok" class="block text-sm font-medium text-gray-700">Tiktok
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-tiktok text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_tiktok" id="social_tiktok"
                                            value="{{ settings()->get('social_tiktok') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="social_facebook" class="block text-sm font-medium text-gray-700">Facebook
                                        URL</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fab fa-facebook text-gray-400"></i>
                                        </div>
                                        <input type="url" name="social_facebook" id="social_facebook"
                                            value="{{ settings()->get('social_facebook') }}"
                                            class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>

                
                <div id="content2" class="tab-content hidden space-y-8">
                    <form action="{{ route('admin.settings.update.seo') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- Language Tabs for SEO Translations -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Content Translations</h3>
                                <nav class="flex -mb-px">
                                    @foreach(config('app.available_locales') as $locale)
                                        <button type="button" 
                                                onclick="switchSeoLanguageTab('{{ $locale }}')"
                                                id="seo-tab-{{ $locale }}" 
                                                class="seo-language-tab {{ $locale == app()->getLocale() ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm">
                                            {{ strtoupper($locale) }}
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                
                        <!-- Translated SEO Content -->
                        @foreach(config('app.available_locales') as $locale)
                            <div id="seo-content-{{ $locale }}" class="seo-language-content {{ $locale == app()->getLocale() ? 'block' : 'hidden' }} space-y-8">
                                <!-- Global SEO Settings -->
                                <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900">Global SEO Settings ({{ strtoupper($locale) }})</h3>
                
                                    <div class="space-y-4">
                                        <!-- Meta Title -->
                                        <div class="space-y-2">
                                            <label for="seo_title_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Meta Title ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" 
                                                   name="translations[seo_title][{{ $locale }}]" 
                                                   id="seo_title_{{ $locale }}"
                                                   value="{{ isset(json_decode(settings()->get('seo_title'))->{$locale}) ? json_decode(settings()->get('seo_title'))->{$locale} : '' }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                   placeholder="Enter meta title in {{ strtoupper($locale) }}"
                                                   maxlength="60">
                                            <p class="text-xs text-gray-500">Recommended: 50-60 characters</p>
                                        </div>
                                        
                                        <!-- Meta Description -->
                                        <div class="space-y-2">
                                            <label for="seo_description_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Meta Description ({{ strtoupper($locale) }})
                                            </label>
                                            <textarea name="translations[seo_description][{{ $locale }}]" 
                                                      id="seo_description_{{ $locale }}" 
                                                      rows="3"
                                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                      placeholder="Enter meta description in {{ strtoupper($locale) }}"
                                                      maxlength="160">{{ isset(json_decode(settings()->get('seo_description'))->{$locale}) ? json_decode(settings()->get('seo_description'))->{$locale} : '' }}</textarea>
                                            <p class="text-xs text-gray-500">Recommended: 150-160 characters</p>
                                        </div>
                                        
                                        <!-- Meta Keywords -->
                                        <div class="space-y-2">
                                            <label for="seo_keywords_{{ $locale }}" class="block text-sm font-medium text-gray-700">
                                                Meta Keywords ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" 
                                                   name="translations[seo_keywords][{{ $locale }}]" 
                                                   id="seo_keywords_{{ $locale }}"
                                                   value="{{ isset(json_decode(settings()->get('seo_keywords'))->{$locale}) ? json_decode(settings()->get('seo_keywords'))->{$locale} : '' }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                   placeholder="keyword1, keyword2, keyword3 in {{ strtoupper($locale) }}">
                                            <p class="text-xs text-gray-500">Separate keywords with commas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Common SEO Settings (non-translatable) -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Global SEO Settings</h3>
                
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Canonical Domain -->
                                <div class="space-y-2">
                                    <label for="canonical_domain" class="block text-sm font-medium text-gray-700">Canonical Domain</label>
                                    <input type="url" 
                                           name="canonical_domain" 
                                           id="canonical_domain"
                                           value="{{ settings()->get('canonical_domain') }}"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                           placeholder="https://example.com">
                                    <p class="text-xs text-gray-500">The primary domain for all canonical URLs</p>
                                </div>
                                
                                <!-- Google Analytics ID -->
                                <div class="space-y-2">
                                    <label for="google_analytics_id" class="block text-sm font-medium text-gray-700">Google Analytics ID</label>
                                    <input type="text" 
                                           name="google_analytics_id" 
                                           id="google_analytics_id"
                                           value="{{ settings()->get('google_analytics_id') }}"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                           placeholder="G-XXXXXXXXXX or UA-XXXXXXXX-X">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Submit -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i> Save SEO Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Custom Code Tab Content -->
                <div id="content4" class="tab-content hidden space-y-8">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Custom Code</h3>
                            <p class="text-gray-600">Add custom CSS and JavaScript to enhance your site. This code will be injected into your app.blade.php layout.</p>
                            
                            <div class="space-y-6">
                                <!-- Header Section -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Header Code</h4>
                                    <p class="text-sm text-gray-600">This code will be placed in the <code>&lt;head&gt;</code> section of your site.</p>
                                    
                                    <div class="space-y-2">
                                        <label for="custom_header_code" class="block text-sm font-medium text-gray-700">
                                            Custom Header Code (CSS/JS)
                                        </label>
                                        <textarea name="custom_header_code" 
                                                id="custom_header_code" 
                                                rows="8"
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 font-mono"
                                                placeholder="<!-- Your custom header code here -->">{{ settings()->get('custom_header_code') }}</textarea>
                                        <p class="text-xs text-gray-500">Add custom styles or scripts that should load in the document head.</p>
                                    </div>
                                </div>
                                
                                <!-- Footer Section -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Footer Code</h4>
                                    <p class="text-sm text-gray-600">This code will be placed before the closing <code>&lt;/body&gt;</code> tag of your site.</p>
                                    
                                    <div class="space-y-2">
                                        <label for="custom_footer_code" class="block text-sm font-medium text-gray-700">
                                            Custom Footer Code (JS)
                                        </label>
                                        <textarea name="custom_footer_code" 
                                                id="custom_footer_code" 
                                                rows="8"
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 font-mono"
                                                placeholder="<!-- Your custom footer code here -->">{{ settings()->get('custom_footer_code') }}</textarea>
                                        <p class="text-xs text-gray-500">Add custom scripts that should load at the end of your document.</p>
                                    </div>
                                </div>

                                <!-- CSS Section -->
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-800">Custom CSS</h4>
                                    <p class="text-sm text-gray-600">Add custom CSS styles that will be included in a <code>&lt;style&gt;</code> tag in the document head.</p>
                                    
                                    <div class="space-y-2">
                                        <label for="custom_css" class="block text-sm font-medium text-gray-700">
                                            Custom CSS Styles
                                        </label>
                                        <textarea name="custom_css" 
                                                id="custom_css" 
                                                rows="8"
                                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 font-mono"
                                                placeholder="/* Your custom CSS here */
                                            body {
                                            /* custom styles */
                                            }">{{ settings()->get('custom_css') }}</textarea>
                                    </div>
                            </div>
                
                            <!-- JavaScript Section -->
                            <div class="space-y-4">
                                <h4 class="text-md font-medium text-gray-800">Custom JavaScript</h4>
                                <p class="text-sm text-gray-600">Add custom JavaScript that will be included in a <code>&lt;script&gt;</code> tag at the end of your document.</p>
                                
                                <div class="space-y-2">
                                    <label for="custom_js" class="block text-sm font-medium text-gray-700">
                                        Custom JavaScript
                                    </label>
                                    <textarea name="custom_js" 
                                            id="custom_js" 
                                            rows="8"
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 font-mono"
                                            placeholder="// Your custom JavaScript here
                                            document.addEventListener('DOMContentLoaded', function() {
                                            // your code here
                                            });">{{ settings()->get('custom_js') }}</textarea>
                                </div>
                            </div>
                        </div>
            
                        <!-- Code Tips -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Notes</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Custom code is executed on every page of your website</li>
                                            <li>Improper code can break your site's functionality</li>
                                            <li>Always test your code before saving</li>
                                            <li>Consider using browser dev tools to validate your CSS/JS first</li>
                                            <li>Use conditional logic if you need code to run on specific pages only</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i> Save Custom Code
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sitemap Tab Content -->
                <div id="content3" class="tab-content hidden space-y-8">
                    <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900">Sitemap Management</h3>
                        <p class="text-gray-600">Generate and manage your website's sitemap to improve SEO.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Sitemap Generation -->
                            <div class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                                <h4 class="text-md font-semibold text-gray-800">Generate Sitemap</h4>
                                <p class="text-sm text-gray-600">Create a new sitemap with all your current content.</p>
                                
                                <form action="{{ route('admin.sitemap.generate') }}" method="GET" class="space-y-4">
                                    <div class="space-y-2">
                                        <label for="sitemap-format" class="block text-sm font-medium text-gray-700">Format</label>
                                        <select id="sitemap-format" name="format" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                            <option value="xml" selected>XML (Standard)</option>
                                            <option value="txt">Text File</option>
                                            <option value="html">HTML Page</option>
                                        </select>
                                        <p class="text-xs text-gray-500">XML is recommended for search engines.</p>
                                    </div>
                                    
                                    <div class="pt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <i class="fas fa-sync-alt mr-2"></i> Generate Sitemap
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Sitemap Export -->
                            <div class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                                <h4 class="text-md font-semibold text-gray-800">Export Sitemap</h4>
                                <p class="text-sm text-gray-600">Download your sitemap in various formats.</p>
                                
                                <form action="{{ route('admin.sitemap.export') }}" method="GET" class="space-y-4">
                                    <div class="space-y-2">
                                        <label for="export-format" class="block text-sm font-medium text-gray-700">Export Format</label>
                                        <select id="export-format" name="format" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                            <option value="xml" selected>XML</option>
                                            <option value="txt">Text File</option>
                                            <option value="html">HTML</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <i class="fas fa-download mr-2"></i> Download Sitemap
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sitemap Status -->
                        <div class="mt-6 space-y-4">
                            <h4 class="text-md font-semibold text-gray-800">Sitemap Status</h4>
                            
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <ul class="space-y-2 text-sm">
                                    @php
                                        $xmlExists = file_exists(public_path('sitemap.xml'));
                                        $txtExists = file_exists(public_path('sitemap.txt'));
                                        $htmlExists = file_exists(public_path('sitemap.html'));
                                    @endphp
                                    
                                    <li class="flex justify-between items-center">
                                        <span class="flex items-center">
                                            <i class="fas fa-file-code mr-2 text-blue-500"></i> XML Sitemap
                                        </span>
                                        <span class="flex items-center">
                                            @if($xmlExists)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full mr-2">Generated</span>
                                                <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Not Generated</span>
                                            @endif
                                        </span>
                                    </li>
                                    
                                    <li class="flex justify-between items-center">
                                        <span class="flex items-center">
                                            <i class="fas fa-file-alt mr-2 text-gray-500"></i> Text Sitemap
                                        </span>
                                        <span class="flex items-center">
                                            @if($txtExists)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full mr-2">Generated</span>
                                                <a href="{{ url('sitemap.txt') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Not Generated</span>
                                            @endif
                                        </span>
                                    </li>
                                    
                                    <li class="flex justify-between items-center">
                                        <span class="flex items-center">
                                            <i class="fas fa-file-code mr-2 text-orange-500"></i> HTML Sitemap
                                        </span>
                                        <span class="flex items-center">
                                            @if($htmlExists)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full mr-2">Generated</span>
                                                <a href="{{ url('sitemap.html') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Not Generated</span>
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            
                            @if($xmlExists)
                                <div class="text-sm text-gray-600">
                                    <p>Last Generated: {{ date("F j, Y, g:i a", filemtime(public_path('sitemap.xml'))) }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Tips -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Sitemap Tips</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Regenerate your sitemap after adding new content</li>
                                            <li>Submit your sitemap to Google Search Console</li>
                                            <li>Make sure your robots.txt file references your sitemap</li>
                                            <li>XML format is most widely supported by search engines</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    // Get references to all tabs and content divs
    const tab1 = document.getElementById('tab1');
    const tab2 = document.getElementById('tab2');
    const tab3 = document.getElementById('tab3');
    const tab4 = document.getElementById('tab4');
    
    const content1 = document.getElementById('content1');
    const content2 = document.getElementById('content2');
    const content3 = document.getElementById('content3');
    const content4 = document.getElementById('content4');
    
    // Info tab
    tab1.addEventListener('click', () => {
        // Hide all content
        [content1, content2, content3, content4].forEach(c => c.classList.add('hidden'));
        // Show content1
        content1.classList.remove('hidden');
        // Update tab styling
        [tab1, tab2, tab3, tab4].forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
        tab1.classList.add('active', 'text-blue-600', 'border-blue-600');
    });
    
    // SEO tab
    tab2.addEventListener('click', () => {
        // Hide all content
        [content1, content2, content3, content4].forEach(c => c.classList.add('hidden'));
        // Show content2
        content2.classList.remove('hidden');
        // Update tab styling
        [tab1, tab2, tab3, tab4].forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
        tab2.classList.add('active', 'text-blue-600', 'border-blue-600');
    });
    
    // Sitemap tab
    tab3.addEventListener('click', () => {
        // Hide all content
        [content1, content2, content3, content4].forEach(c => c.classList.add('hidden'));
        // Show content3
        content3.classList.remove('hidden');
        // Update tab styling
        [tab1, tab2, tab3, tab4].forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
        tab3.classList.add('active', 'text-blue-600', 'border-blue-600');
    });
    
    // Custom Code tab
    tab4.addEventListener('click', () => {
        // Hide all content
        [content1, content2, content3, content4].forEach(c => c.classList.add('hidden'));
        // Show content4
        content4.classList.remove('hidden');
        // Update tab styling
        [tab1, tab2, tab3, tab4].forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
        tab4.classList.add('active', 'text-blue-600', 'border-blue-600');
    });
    
    // Language tab switching functions - maintain these as they are
    window.switchLanguageTab = function(locale) {
        // Hide all content sections
        document.querySelectorAll('.language-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show the selected content section
        document.getElementById('content-' + locale).classList.remove('hidden');
        document.getElementById('content-' + locale).classList.add('block');
        
        // Update tab styles
        document.querySelectorAll('.language-tab').forEach(el => {
            el.classList.remove('border-blue-500', 'text-blue-600');
            el.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        document.getElementById('tab-' + locale).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        document.getElementById('tab-' + locale).classList.add('border-blue-500', 'text-blue-600');
    };

    // SEO language tab switching
    window.switchSeoLanguageTab = function(locale) {
        // Hide all content sections
        document.querySelectorAll('.seo-language-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Show the selected content section
        document.getElementById('seo-content-' + locale).classList.remove('hidden');
        document.getElementById('seo-content-' + locale).classList.add('block');
        
        // Update tab styles
        document.querySelectorAll('.seo-language-tab').forEach(el => {
            el.classList.remove('border-blue-500', 'text-blue-600');
            el.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        document.getElementById('seo-tab-' + locale).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        document.getElementById('seo-tab-' + locale).classList.add('border-blue-500', 'text-blue-600');
    };
});
    </script>
@endsection