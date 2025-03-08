@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-5 flex-wrap">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Newsletter</h1>
            <p class="text-gray-600">Update newsletter content and settings</p>
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

        <form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}">
            @csrf
            @method('PUT')
            
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
                        <li>
                            <a href="#schedule-tab" data-target="schedule-panel" 
                               class="inactive-tab inline-block py-2 px-4 text-gray-600 hover:text-blue-600 rounded-t-lg">
                                Schedule
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Content Panel -->
                <div id="content-panel" class="tab-panel">
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Newsletter Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $newsletter->title) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="mt-1 text-sm text-gray-500">This will be the subject of the email.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-between">
                <a href="{{ route('admin.newsletters.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <div>
                    @if($newsletter->status === 'draft' || $newsletter->status === 'scheduled')
                        <button type="submit" name="action" value="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        
                        @if($newsletter->status === 'draft')
                            <button type="submit" name="action" value="send" class="ml-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-paper-plane mr-1"></i> Send Now
                            </button>
                        @endif
                    @else
                        <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg cursor-not-allowed">
                            <i class="fas fa-lock mr-1"></i> Cannot Edit ({{ ucfirst($newsletter->status) }})
                        </div>
                    @endif
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

    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
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
</script>
@endsection