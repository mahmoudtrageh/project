@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Category Management</h1>
        <p class="text-gray-600">Edit Category</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
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
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Name ({{ strtoupper($locale) }})</label>
                            <input
                                type="text"
                                id="name_{{ $locale }}"
                                name="name[{{ $locale }}]"
                                value="{{ old('name.'.$locale, $category->getTranslation('name', $locale, false) ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name.'.$locale) border-red-500 @enderror"
                                placeholder="Enter category name in {{ strtoupper($locale) }}"
                                {{ $locale == app()->getFallbackLocale() ? 'required' : '' }}
                            />
                            @error('name.'.$locale)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="description_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Description ({{ strtoupper($locale) }})</label>
                            <textarea
                                id="description_{{ $locale }}"
                                name="description[{{ $locale }}]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description.'.$locale) border-red-500 @enderror"
                                rows="4"
                                placeholder="Enter category description in {{ strtoupper($locale) }}"
                            >{{ old('description.'.$locale, $category->getTranslation('description', $locale, false) ?? '') }}</textarea>
                            @error('description.'.$locale)
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
                        value="{{ old('slug', $category->slug ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                        placeholder="Enter category slug"
                    />
                    <p class="text-xs text-gray-500 mt-1">The slug will be automatically generated from the name, but you can modify it.</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                    @if($category->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($category->image) }}" alt="Current Image" class="h-32 w-auto rounded">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror"
                        accept="image/*"
                    />
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Parent Category and Order -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                        <select
                            id="parent_id"
                            name="parent_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror"
                        >
                            <option value="">None (Root Category)</option>
                            @foreach($categories as $parentCategory)
                                @if($parentCategory->id != $category->id && !in_array($parentCategory->id, $childrenIds ?? []))
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select a parent category or leave empty for root level</p>
                        @error('parent_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input
                            type="number"
                            id="order"
                            name="order"
                            value="{{ old('order', $category->order) }}"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror"
                        />
                        <p class="text-xs text-gray-500 mt-1">Order in which category appears (0 = first)</p>
                        @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                <!-- Status -->
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <label for="is_active" class="ms-2 block text-sm text-gray-700">Active</label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Info Section -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">SEO Information</h3>
                    
                    <!-- Meta Title -->
                    <div class="mb-4">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input
                            type="text"
                            id="meta_title"
                            name="meta_title"
                            value="{{ old('meta_title', $category->meta_title) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_title') border-red-500 @enderror"
                            placeholder="Enter meta title"
                        />
                        @error('meta_title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea
                            id="meta_description"
                            name="meta_description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror"
                            rows="2"
                            placeholder="Enter meta description"
                        >{{ old('meta_description', $category->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input
                            type="text"
                            id="meta_keywords"
                            name="meta_keywords"
                            value="{{ old('meta_keywords', $category->meta_keywords) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_keywords') border-red-500 @enderror"
                            placeholder="Enter keywords separated by commas"
                        />
                        @error('meta_keywords')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Update Category
                    </button>
            
                    <a href="{{ route('admin.categories.index') }}" 
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
    // Slug generation from default locale name
    const defaultLocale = "{{ app()->getFallbackLocale() }}";
    const nameField = document.getElementById('name_' + defaultLocale);
    
    if (nameField) {
        nameField.addEventListener('input', function() {
            const nameValue = this.value;
            const slugField = document.getElementById('slug');
            
            // Only auto-generate slug if the user hasn't manually modified it
            if (!slugField.dataset.manuallyChanged) {
                slugField.value = nameValue
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-')     // Replace spaces with hyphens
                    .replace(/-+/g, '-');     // Replace multiple hyphens with single hyphen
            }
        });
    }

    document.getElementById('slug').addEventListener('input', function() {
        // Mark that the user has manually changed the slug
        this.dataset.manuallyChanged = 'true';
    });
    
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