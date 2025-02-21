@extends('admin.layouts.app')

@section('content')

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Create Project</p>
        </div>

        <div class="container">        
            <!-- Form Container -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{route('admin.projects.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter project title"
                        required
                    />
                    </div>
            
                    <!-- Slug Field -->
                    <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter project slug"
                        required
                    />
                    <p class="text-xs text-gray-500 mt-1">The slug must be unique and URL-friendly.</p>
                    </div>
            
                    <!-- Description Field -->
                    <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="5"
                        placeholder="Enter project description"
                        required
                    ></textarea>
                    </div>
            
                    <!-- Client Name Field -->
                    <div class="mb-6">
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input
                        type="text"
                        id="client_name"
                        name="client_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter client name"
                    />
                    </div>
            
                    <!-- Image Field -->
                    <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Project Image</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        accept="image/*"
                        required
                    />
                    </div>
            
                    <!-- Completion Date Field -->
                    <div class="mb-6">
                    <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                    <input
                        type="date"
                        id="completion_date"
                        name="completion_date"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                    </div>
            
                    <!-- URL Field -->
                    <div class="mb-6">
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Project URL</label>
                    <input
                        type="url"
                        id="url"
                        name="url"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter project URL"
                    />
                    </div>
            
                    <!-- Is Featured Field -->
                    <div class="mb-6">
                    <label for="is_featured" class="block text-sm font-medium text-gray-700 mb-2">Is Featured?</label>
                    <div class="flex items-center">
                        <input
                        type="checkbox"
                        id="is_featured"
                        name="is_featured"
                        value="1"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-600">Check this box to feature the project.</span>
                    </div>
                    </div>
            
                    <!-- Status Field -->
                    <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    </div>
            
                    <div class="flex justify-between">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            Add Project
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