@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">{{__('Language Management')}}</h1>
        <p class="text-gray-600">{{__('Create New Language')}}</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('lang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{__('Name')}}</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="{{__('Enter language name (e.g. English, French)')}}"
                        required
                    />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Code Field -->
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">{{__('Code')}}</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                        placeholder="{{__('Enter language code (e.g. en, fr)')}}"
                        required
                    />
                    <p class="text-xs text-gray-500 mt-1">{{__('The language code is used for translation files and language selection.')}}</p>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Icon Field -->
                <div class="mb-6">
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">{{__('Language Flag/Icon')}}</label>
                    <input
                        type="file"
                        id="icon"
                        name="icon"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror"
                        accept="image/*"
                    />
                    <p class="text-xs text-gray-500 mt-1">{{__('Upload a flag or icon representing this language (recommended size: 64x64 pixels)')}}</p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Status and RTL Fields -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="status"
                                name="status"
                                value="1"
                                {{ old('status', 1) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label for="status" class="ms-2 block text-sm text-gray-700">{{__('Active')}}</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ms-6">{{__('Enable this language for users to select')}}</p>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="rtl"
                                name="rtl"
                                value="1"
                                {{ old('rtl') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label for="rtl" class="ms-2 block text-sm text-gray-700">{{__('RTL Support')}}</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ms-6">{{__('Enable right-to-left text direction for this language')}}</p>
                        @error('rtl')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            
                <!-- Default Language -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="is_default"
                            name="status"
                            value="1"
                            {{ old('is_default') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <label for="is_default" class="ms-2 block text-sm text-gray-700">{{__('Set as Default Language')}}</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ms-6">{{__('This will set the language as system default. There can be only one default language.')}}</p>
                    @error('is_default')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Submit Buttons -->
                <div class="flex justify-between mt-8">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        {{__('Create Language')}}
                    </button>
            
                    <a href="{{ route('admin.languages.index') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        {{__('Cancel')}}
                    </a>
                </div>
            </form>           
        </div>
    </div>
@endsection

@section('js')
<script>
    // You can add any client-side validation or preview functionality here
    document.getElementById('icon').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Optional: Add code to show an image preview
        }
    });
</script>
@endsection