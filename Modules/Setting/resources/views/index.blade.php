@extends('admin.layouts.app')

@section('content')

    <div class="flex space-x-4 border-b border-gray-200">
      <button id="tab1" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300 active">
        Info
      </button>
      <button id="tab2" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
        Settings
      </button>
      <button id="tab3" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 hover:text-blue-600 focus:outline-none border-b-2 border-transparent hover:border-blue-600 transition-all duration-300">
        Messages
      </button>
    </div>

    <!-- Tabs Content -->
    <div class="mt-6">
      <!-- Profile Tab Content -->
      <div id="content1" class="tab-content bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- General Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-md font-medium text-gray-700 mb-4">General Settings</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Site Name -->
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" name="site_name" id="site_name" 
                            value="{{ settings()->get('site_name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Site Email -->
                    <div>
                        <label for="site_email" class="block text-sm font-medium text-gray-700">Site Email</label>
                        <input type="email" name="site_email" id="site_email" 
                            value="{{ settings()->get('site_email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                     <!-- Site Email -->
                     <div>
                        <label for="site_phone" class="block text-sm font-medium text-gray-700">Site Phone</label>
                        <input type="text" name="site_phone" id="site_phone" 
                            value="{{ settings()->get('site_phone') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Site Description -->
                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                        <textarea name="site_description" id="site_description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ settings()->get('site_description') }}</textarea>
                    </div>

                    <!-- About Name -->
                    <div>
                        <label for="about_name" class="block text-sm font-medium text-gray-700">About Name</label>
                        <input type="text" name="about_name" id="about_name" 
                            value="{{ settings()->get('about_name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- About Description -->
                    <div>
                        <label for="about_description" class="block text-sm font-medium text-gray-700">About Description</label>
                        <textarea name="about_description" id="about_description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ settings()->get('about_description') }}</textarea>
                    </div>

                     <!-- About Description -->
                     <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <textarea name="location" id="location" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ settings()->get('location') }}</textarea>
                    </div>

                    <!-- Logo and Favicon -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Site Logo</label>
                            @if(settings()->get('logo'))
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url(settings()->get('logo')) }}" alt="Site Logo" class="h-20">
                                </div>
                            @endif
                            <input type="file" name="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Site Favicon</label>
                            @if(settings()->get('favicon'))
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url(settings()->get('favicon')) }}" alt="Favicon" class="h-8">
                                </div>
                            @endif
                            <input type="file" name="favicon" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">About Image</label>
                            @if(settings()->get('about_image'))
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url(settings()->get('about_image')) }}" alt="About_image" class="h-8">
                                </div>
                            @endif
                            <input type="file" name="about_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-md font-medium text-gray-700 mb-4">Social Media Links</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="social_github" class="block text-sm font-medium text-gray-700">Github URL</label>
                        <input type="url" name="social_github" id="social_github" 
                            value="{{ settings()->get('social_github') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="social_twitter" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                        <input type="url" name="social_twitter" id="social_twitter" 
                            value="{{ settings()->get('social_twitter') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="social_linkedin" class="block text-sm font-medium text-gray-700">Linkedin URL</label>
                        <input type="url" name="social_linkedin" id="social_linkedin" 
                            value="{{ settings()->get('social_linkedin') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Footer Settings -->
            <div>
                <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Text</label>
                <textarea name="footer_text" id="footer_text" rows="2" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ settings()->get('footer_text') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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