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
                    Settings
                </button>
                <button id="tab3"
                    class="tab-button flex-1 px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
                    <i class="fas fa-envelope mr-2"></i>
                    Messages
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

                <!-- Settings Tab Content -->
                <div id="content2" class="tab-content hidden bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Account Settings</h2>
                    <p class="text-gray-600">Manage your account settings, including privacy and security options.</p>
                </div>

                <!-- Messages Tab Content -->
                <div id="content3" class="tab-content hidden bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Messages</h2>
                    <p class="text-gray-600">View and manage your messages here.</p>
                </div>
            </div>
        </div>
</div>
@endsection

@section('js')
    <script>
        // JavaScript to handle tab switching
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and hide all content
                tabs.forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
                contents.forEach(c => c.classList.add('hidden'));

                // Add active class to the clicked tab and show the corresponding content
                tab.classList.add('active', 'text-blue-600', 'border-blue-600');
                contents[index].classList.remove('hidden');
            });
        });
        
        // JavaScript to handle language tab switching
        function switchLanguageTab(locale) {
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
        }
    </script>
@endsection