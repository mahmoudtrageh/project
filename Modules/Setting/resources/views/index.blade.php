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
                        <!-- General Settings -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Site Name -->
                                <div class="space-y-2">
                                    <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                                    <input type="text" name="site_name" id="site_name"
                                        value="{{ settings()->get('site_name') }}"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                </div>

                                <!-- Site Email -->
                                <div class="space-y-2">
                                    <label for="site_email" class="block text-sm font-medium text-gray-700">Site
                                        Email</label>
                                    <input type="email" name="site_email" id="site_email"
                                        value="{{ settings()->get('site_email') }}"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                </div>

                                <!-- Site Phone -->
                                <div class="space-y-2">
                                    <label for="site_phone" class="block text-sm font-medium text-gray-700">Site
                                        Phone</label>
                                    <input type="text" name="site_phone" id="site_phone"
                                        value="{{ settings()->get('site_phone') }}"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                </div>

                                <!-- About Name -->
                                <div class="space-y-2">
                                    <label for="about_name" class="block text-sm font-medium text-gray-700">About
                                        Name</label>
                                    <input type="text" name="about_name" id="about_name"
                                        value="{{ settings()->get('about_name') }}"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="site_description" class="block text-sm font-medium text-gray-700">Site
                                        Description</label>
                                    <textarea name="site_description" id="site_description" rows="3"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ settings()->get('site_description') }}</textarea>
                                </div>

                                <div class="space-y-2">
                                    <label for="about_description" class="block text-sm font-medium text-gray-700">About
                                        Description</label>
                                    <textarea name="about_description" id="about_description" rows="3"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ settings()->get('about_description') }}</textarea>
                                </div>

                                <div class="space-y-2">
                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                    <textarea name="location" id="location" rows="3"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ settings()->get('location') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Media Settings -->
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
                            </div>
                        </div>

                        <!-- Social Media Settings -->
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
                            </div>
                        </div>

                        <!-- Footer Settings -->
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Footer Settings</h3>

                            <div class="space-y-2">
                                <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Text</label>
                                <textarea name="footer_text" id="footer_text" rows="2"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ settings()->get('footer_text') }}</textarea>
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
        </script>
    @endsection