@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-5 flex-wrap">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Create Newsletter</h1>
            <p class="text-gray-600">Create a new newsletter to send to subscribers</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Newsletter Details</h2>
        </div>

        @if ($errors->any())
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-red-100 text-red-800" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('newsletters.store') }}">
            @csrf
            
            <div class="p-6">
                <div class="mb-6">
                    <ul class="flex flex-wrap border-b border-gray-200">
                        <li class="mr-2">
                            <a href="#content-tab" data-target="content-panel" 
                               class="active-tab inline-block py-2 px-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg">
                                Content
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#recipients-tab" data-target="recipients-panel" 
                               class="inactive-tab inline-block py-2 px-4 text-gray-600 hover:text-blue-600 rounded-t-lg">
                                Recipients
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#styling-tab" data-target="styling-panel" 
                               class="inactive-tab inline-block py-2 px-4 text-gray-600 hover:text-blue-600 rounded-t-lg">
                                Styling
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#social-tab" data-target="social-panel" 
                               class="inactive-tab inline-block py-2 px-4 text-gray-600 hover:text-blue-600 rounded-t-lg">
                                Social Links
                            </a>
                        </li>
                        {{-- <li>
                            <a href="#schedule-tab" data-target="schedule-panel" 
                               class="inactive-tab inline-block py-2 px-4 text-gray-600 hover:text-blue-600 rounded-t-lg">
                                Schedule
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <!-- Content Panel -->
                <div id="content-panel" class="tab-panel">
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Newsletter Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="mt-1 text-sm text-gray-500">This will be the subject of the email.</p>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Newsletter Content</label>
                        <textarea id="editor" name="content" rows="15"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            You can use this placeholder: <code>@{{ email }}</code>
                        </p>
                    </div>                    

                    <div class="mb-6">
                        <button type="button" id="previewBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </button>
                    </div>
                </div>

                <!-- Recipients Panel -->
                <div id="recipients-panel" class="tab-panel hidden">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Recipients</label>
                        <div class="mb-2 flex space-x-2">
                            <button type="button" id="selectAllBtn" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm">
                                <i class="fas fa-check-square mr-1"></i> Select All
                            </button>
                            <button type="button" id="deselectAllBtn" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm">
                                <i class="fas fa-square mr-1"></i> Deselect All
                            </button>
                        </div>
                        
                        <div class="border border-gray-300 rounded-md p-3" style="max-height: 400px; overflow-y: auto;">
                            @forelse($subscribers as $subscriber)
                                <div class="mb-2 last:mb-0">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="recipients[]" value="{{ $subscriber->id }}" 
                                               class="recipient-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                               {{ in_array($subscriber->id, old('recipients', [])) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $subscriber->name ?: $subscriber->email }} ({{ $subscriber->email }})</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No active subscribers found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Styling Panel -->
                <div id="styling-panel" class="tab-panel hidden">
                    <div class="mb-6">
                        <label for="custom_css" class="block text-sm font-medium text-gray-700 mb-1">Custom CSS</label>
                        <textarea id="custom_css" name="custom_css" rows="12" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">{{ old('custom_css') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Add custom CSS to style your newsletter.</p>
                    </div>
                </div>

                <!-- Social Links Panel -->
                <div id="social-panel" class="tab-panel hidden">
                    <div id="social-links-container">
                        <div class="flex -mx-2 mb-4 social-row">
                            <div class="px-2 w-1/3">
                                <input type="text" name="social_links[platform][]" placeholder="Platform (e.g. Facebook)" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="px-2 w-2/3 flex">
                                <input type="url" name="social_links[url][]" placeholder="URL" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button" class="remove-social-btn ml-2 px-2 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-social-btn" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-1"></i> Add Social Media Link
                    </button>
                </div>

                <!-- Schedule Panel -->
                <div id="schedule-panel" class="tab-panel hidden">
                    <div class="mb-6">
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Schedule Sending Time (Optional)</label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to save as draft without scheduling.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-between">
                <a href="{{ route('newsletters.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <div>
                    <button type="submit" name="action" value="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-1"></i> Save as Draft
                    </button>
                    <button type="submit" name="action" value="send" class="ml-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-paper-plane mr-1"></i> Send Now
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Initialize tabs
    document.querySelectorAll('.inactive-tab, .active-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.inactive-tab, .active-tab').forEach(t => {
                t.classList.remove('active-tab', 'text-blue-600', 'border-b-2', 'border-blue-600');
                t.classList.add('inactive-tab', 'text-gray-600');
            });
            
            // Add active class to clicked tab
            this.classList.remove('inactive-tab', 'text-gray-600');
            this.classList.add('active-tab', 'text-blue-600', 'border-b-2', 'border-blue-600');
            
            // Show target panel
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.remove('hidden');
        });
    });

  
    // Social links functionality
    const socialContainer = document.getElementById('social-links-container');
    
    document.getElementById('add-social-btn').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'flex -mx-2 mb-4 social-row';
        row.innerHTML = `
            <div class="px-2 w-1/3">
                <input type="text" name="social_links[platform][]" placeholder="Platform (e.g. Facebook)" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="px-2 w-2/3 flex">
                <input type="url" name="social_links[url][]" placeholder="URL" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" class="remove-social-btn ml-2 px-2 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        socialContainer.appendChild(row);
    });
    
    // Remove social link row
    socialContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-social-btn') || e.target.closest('.remove-social-btn')) {
            const button = e.target.classList.contains('remove-social-btn') ? e.target : e.target.closest('.remove-social-btn');
            const row = button.closest('.social-row');
            
            // Don't remove if it's the only row
            if (document.querySelectorAll('.social-row').length > 1) {
                row.remove();
            } else {
                // Clear inputs instead
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => input.value = '');
            }
        }
    });

    // Select/Deselect All functionality
    document.getElementById('selectAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.recipient-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    });
    
    document.getElementById('deselectAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.recipient-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    // Preview button functionality
    document.getElementById('previewBtn').addEventListener('click', function() {
        // Submit form data to preview endpoint
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('newsletters.preview-draft') }}';
        form.target = '_blank';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add title, content, and CSS
        const title = document.createElement('input');
        title.type = 'hidden';
        title.name = 'title';
        title.value = document.getElementById('title').value;
        form.appendChild(title);
        
        const content = document.createElement('input');
        content.type = 'hidden';
        content.name = 'content';
        content.value = document.querySelector('.ck-editor__editable').ckeditorInstance.getData();
        form.appendChild(content);
        
        const css = document.createElement('input');
        css.type = 'hidden';
        css.name = 'custom_css';
        css.value = document.getElementById('custom_css').value;
        form.appendChild(css);
        
        // Append to body, submit, and remove
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
</script>
@endsection