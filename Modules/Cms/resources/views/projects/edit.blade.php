@extends('admin.layouts.app')

@section('content')

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Create Project</p>
        </div>

        <div class="container">        
            <!-- Form Container -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $project->title) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="Enter project title"
                            required
                        />
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <!-- Slug Field -->
                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            value="{{ old('slug', $project->slug) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                            placeholder="Enter project slug"
                        />
                        <p class="text-xs text-gray-500 mt-1">The slug will be automatically generated from the title, but you can modify it.</p>
                        @error('slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            rows="5"
                            placeholder="Enter project description"
                            required
                        >{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-6">
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                        <input
                            type="text"
                            id="client_name"
                            name="client_name"
                            value="{{ old('client_name', $project->client_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_name') border-red-500 @enderror"
                            placeholder="Enter client name"
                        />
                        @error('client_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Project Image</label>
                        @if($project->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($project->image) }}" alt="Current Image" class="h-32 w-auto rounded">
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
                
                    <div class="mb-6">
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                        <input
                            type="date"
                            id="completion_date"
                            name="completion_date"
                            value="{{ old('completion_date', $project->completion_date->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('completion_date') border-red-500 @enderror"
                            required
                        />
                        @error('completion_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Project URL</label>
                        <input
                            type="url"
                            id="url"
                            name="url"
                            value="{{ old('url', $project->url) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('url') border-red-500 @enderror"
                            placeholder="Enter project URL"
                        />
                        @error('url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-6">
                        <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-2">Is Featured?</label>
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_featured"
                                name="is_featured"
                                value="1"
                                {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <span class="ms-2 text-sm text-gray-600">Check this box to feature the project.</span>
                        </div>
                        @error('is_featured')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required
                        >
                            <option value="draft" {{ old('status', $project->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $project->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                
                    <div class="flex justify-between">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Update Project
                        </button>
                
                        <a href="{{ route('admin.projects.index') }}" 
                           class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
      
@endsection