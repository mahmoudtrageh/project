@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-5 flex-wrap">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-800">Pages Management</h1>
            <p class="text-gray-600">Manage your website pages</p>
        </div>

        <!-- Table -->
        <a href="{{ route('admin.pages.create') }}"
            class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-plus me-2"></i> Create New Page
        </a>
    </div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center max-md:flex-col gap-5">
            <h2 class="text-lg font-semibold text-gray-800">Pages</h2>
            <div class="flex items-center gap-6 flex-wrap max-md:justify-center">
                <!-- Search Box -->
                <div class="relative group">
                    <input type="text" placeholder="Search pages..."
                        class="w-64 px-4 py-2.5 pl-11 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200" />
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select
                        class="appearance-none w-40 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer hover:bg-white">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                    <i class="fas fa-chevron-down absolute ltr:right-4 rtl:left-4 top-3.5 text-gray-400 pointer-events-none transition-transform duration-200"></i>
                </div>

                <!-- Menu Filter -->
                <div class="relative">
                    <select
                        class="appearance-none w-40 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer hover:bg-white">
                        <option value="">All Pages</option>
                        <option value="1">Menu Pages</option>
                        <option value="0">Non-Menu Pages</option>
                    </select>
                    <i class="fas fa-chevron-down absolute ltr:right-4 rtl:left-4 top-3.5 text-gray-400 pointer-events-none transition-transform duration-200"></i>
                </div>
            </div>
        </div>

        <div class="responsive-table  max-w-[90vw]">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllCheckbox"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Page Info
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Menu Status
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order
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
                    @foreach($pages as $page)
                        <tr class="table-row hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox"
                                    class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    value="{{ $page->id }}" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($page->image)
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($page->image) }}"
                                            alt="{{ $page->getTranslation('title', app()->getLocale()) }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-file-alt text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ms-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $page->getTranslation('title', app()->getLocale()) }}
                                            <span class="text-xs text-gray-500 ml-2">({{ strtoupper(app()->getLocale()) }})</span>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $page->slug }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($page->getTranslation('content', app()->getLocale()), 50) }}
                                        </div>
                                        
                                        <!-- Optional: Show available translations as badges -->
                                        <div class="mt-1 flex space-x-1">
                                            @foreach(array_keys($page->getTranslations('title')) as $locale)
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
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $page->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($page->show_in_menu)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        In Menu
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Not in Menu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ $page->order }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ $page->published_at ? $page->published_at->format('M d, Y H:i') : 'Not Published' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.pages.edit', ['page' => $page->id]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ route('admin.pages.destroy', $page) }}')"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                            <button type="button" onclick="closeDeleteModal()"
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

        <div>
            {{ $pages->links() }}
        </div>
    </div>

    <!-- Delete Modal (Same as before) -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Modal content same as before -->
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
        deleteModal.addEventListener('click', function (e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection