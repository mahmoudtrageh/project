@extends('admin.layouts.app')

@section('content')
  <div class="flex items-center justify-between gap-5 flex-wrap">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Blog Management</h1>
        <p class="text-gray-600">Manage your blog posts</p>
    </div>

    <!-- Table -->
    <a href="{{ route('admin.blogs.create') }}"
        class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-plus me-2"></i> Create Blog Post
    </a>
  </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Blog Posts</h2>
            <div class="flex items-center gap-x-6">
                <!-- Search Box -->
                <div class="relative w-full sm:w-48">
                    <input type="text" placeholder="Search blogs..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-search absolute ltr:right-3 rtl:left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Status Filter -->
                <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>

                <!-- Export Dropdown -->
                <div class="relative w-full sm:w-auto">
                    <button id="exportDropdownButton"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Export <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-csv mr-2"></i> Export as CSV
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-excel mr-2"></i> Export as Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto max-lg:max-w-[90vw]">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAllCheckbox"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Blog Info
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Featured
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Published At
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($blogs as $blog)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        value="{{ $blog->id }}" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($blog->image) }}"
                                            alt="{{ $blog->getTranslation('title', app()->getLocale()) }}">
                                        <div class="ms-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $blog->getTranslation('title', app()->getLocale()) }}
                                                <span class="text-xs text-gray-500 ml-2">({{ app()->getLocale() }})</span>
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $blog->slug }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($blog->getTranslation('content', app()->getLocale()), 50) }}
                                            </div>
                                            
                                            <!-- Optional: Show available translations as badges -->
                                            <div class="mt-1 flex space-x-1">
                                                @foreach(array_keys($blog->getTranslations('title')) as $locale)
                                                    @if($locale != app()->getLocale())
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ strtoupper($locale) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $blog->category ? $blog->category->name : 'Uncategorized' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $blog->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($blog->featured)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Featured
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $blog->published_at ? $blog->published_at->format('M d, Y H:i') : 'Not Published' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('admin.blogs.destroy', $blog) }}')"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            {{ $blogs->links() }}
        </div>
    </div>

     <!-- Delete Modal -->
     <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black opacity-50"></div>

        <!-- Modal content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg w-full max-w-md">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Are you sure you want to delete this project? This action cannot be undone.
                    </p>

                    <div class="flex justify-end gap-x-3">
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>

                        <form id="deleteForm" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');

    function openDeleteModal(deleteUrl) {
        deleteModal.classList.remove('hidden');
        deleteForm.action = deleteUrl;
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        // Restore body scrolling
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>

@endsection