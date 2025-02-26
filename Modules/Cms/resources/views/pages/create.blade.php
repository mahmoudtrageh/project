@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Page Management</h1>
        <p class="text-gray-600">Create Page</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Language Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            @foreach(config('app.available_locales') as $locale)
                                <button type="button" 
                                        data-locale="{{ $locale }}"
                                        id="tab-{{ $locale }}" 
                                        class="language-tab {{ $locale == app()->getLocale() ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm">
                                    {{ strtoupper($locale) }}
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </div>

                <!-- Form Fields with Translation Tabs -->
                @foreach(config('app.available_locales') as $locale)
                    <div id="content-{{ $locale }}" class="language-content {{ $locale == app()->getLocale() ? 'block' : 'hidden' }}">
                        <!-- Title Field -->
                        <div class="mb-6">
                            <label for="title_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Title ({{ strtoupper($locale) }})</label>
                            <input
                                type="text"
                                id="title_{{ $locale }}"
                                name="title[{{ $locale }}]"
                                value="{{ old('title.'.$locale) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title.'.$locale) border-red-500 @enderror"
                                placeholder="Enter page title in {{ strtoupper($locale) }}"
                                {{ $locale == app()->getFallbackLocale() ? 'required' : '' }}
                            />
                            @error('title.'.$locale)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Field -->
                        <div class="mb-6">
                            <label for="editor_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Content ({{ strtoupper($locale) }})</label>
                            <textarea
                                id="editor_{{ $locale }}"
                                name="content[{{ $locale }}]"
                                class="editor-instance w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content.'.$locale) border-red-500 @enderror"
                                rows="10"
                                placeholder="Enter page content in {{ strtoupper($locale) }}"
                                {{ $locale == app()->getFallbackLocale() ? 'required' : '' }}
                            >{{ old('content.'.$locale) }}</textarea>
                            @error('content.'.$locale)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <!-- Slug Field (Not Translated) -->
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                        placeholder="Enter page slug"
                    />
                    <p class="text-xs text-gray-500 mt-1">The slug will be automatically generated from the title, but you can modify it.</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Image Field -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Page Image</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror"
                        accept="image/*"
                    />
                    <p class="text-xs text-gray-500 mt-1">Optional: Upload an image for this page</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Menu Settings -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="show_in_menu"
                                name="show_in_menu"
                                value="1"
                                {{ old('show_in_menu') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label for="show_in_menu" class="ms-2 block text-sm text-gray-700">Show in Navigation Menu</label>
                        </div>
                        @error('show_in_menu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Menu Order</label>
                        <input
                            type="number"
                            id="order"
                            name="order"
                            value="{{ old('order', 0) }}"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror"
                        />
                        <p class="text-xs text-gray-500 mt-1">Order in which page appears in menu (0 = first)</p>
                        @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                <!-- Status and Published At Fields -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required
                        >
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date & Time</label>
                        <input
                            type="datetime-local"
                            id="published_at"
                            name="published_at"
                            value="{{ old('published_at') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('published_at') border-red-500 @enderror"
                        />
                        <p class="text-xs text-gray-500 mt-1">Leave empty to publish immediately when status is set to published</p>
                        @error('published_at')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Create Page
                    </button>
            
                    <a href="{{ route('admin.pages.index') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
            </form>           
        </div>
    </div>
@endsection

@section('js')
<script>

document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to all language tabs
        document.querySelectorAll('.language-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const locale = this.getAttribute('data-locale');
                
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
                
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                this.classList.add('border-blue-500', 'text-blue-600');
            });
        });
        
      
    });
</script>


@endsection