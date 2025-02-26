@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">FAQ Management</h1>
        <p class="text-gray-600">Edit FAQ</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
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
                        <!-- Question Field -->
                        <div class="mb-6">
                            <label for="question_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Question ({{ strtoupper($locale) }})</label>
                            <input
                                type="text"
                                id="question_{{ $locale }}"
                                name="question[{{ $locale }}]"
                                value="{{ old('question.'.$locale, $faq->getTranslation('question', $locale, false) ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question.'.$locale) border-red-500 @enderror"
                                placeholder="Enter question in {{ strtoupper($locale) }}"
                                {{ $locale == app()->getFallbackLocale() ? 'required' : '' }}
                            />
                            @error('question.'.$locale)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Answer Field -->
                        <div class="mb-6">
                            <label for="editor_{{ $locale }}" class="block text-sm font-medium text-gray-700 mb-2">Answer ({{ strtoupper($locale) }})</label>
                            <textarea
                                id="editor_{{ $locale }}"
                                name="answer[{{ $locale }}]"
                                class="editor-instance w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answer.'.$locale) border-red-500 @enderror"
                                rows="10"
                                placeholder="Enter answer in {{ strtoupper($locale) }}"
                                {{ $locale == app()->getFallbackLocale() ? 'required' : '' }}
                            >{{ old('answer.'.$locale, $faq->getTranslation('answer', $locale, false) ?? '') }}</textarea>
                            @error('answer.'.$locale)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
                            
                <div class="mb-6">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="is_published"
                            name="is_published"
                            value="1"
                            {{ old('is_published', $faq->is_published) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <label for="is_published" class="ms-2 block text-sm text-gray-700">Publish FAQ</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Check to make this FAQ visible on your website</p>
                    @error('is_published')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Update FAQ
                    </button>
            
                    <a href="{{ route('admin.faqs.index') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
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
@endpush