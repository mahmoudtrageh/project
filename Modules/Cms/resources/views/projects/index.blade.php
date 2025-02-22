@extends('admin.layouts.app')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Projects Dashboard</h1>
            <p class="text-lg text-gray-600">Manage and track all your projects in one place</p>
        </div>

        <!-- Main Content -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{route('admin.projects.create')}}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
            >
                <i class="fas fa-plus mr-2"></i> Create Project
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-900">Recent Projects</h2>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Box -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Search projects..."
                            class="w-64 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200"
                        />
                        <i class="fas fa-search absolute right-3 top-3.5 text-gray-400"></i>
                    </div>

                    <!-- Delete All Button -->
                    <button
                        id="deleteAllButton"
                        class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 shadow-sm"
                        onclick="deleteSelectedRows()"
                    >
                        <i class="fas fa-trash-alt mr-2"></i> Delete Selected
                    </button>

                    <!-- Export Dropdown -->
                    <div class="relative">
                        <button
                            id="exportDropdownButton"
                            class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 shadow-sm"
                        >
                            <i class="fas fa-download mr-2"></i> Export <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <div
                            id="exportDropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-10 border border-gray-100 transform transition-all duration-200"
                        >
                            <a
                                href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                onclick="exportTable('csv')"
                            >
                                <i class="fas fa-file-csv mr-2"></i> Export as CSV
                            </a>
                            <a
                                href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                onclick="exportTable('excel')"
                            >
                                <i class="fas fa-file-excel mr-2"></i> Export as Excel
                            </a>
                            <a
                                href="#"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                onclick="exportTable('pdf')"
                            >
                                <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="responsive-table overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/75 backdrop-blur-sm sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Project Info
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client Name
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                URL 
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Featured
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completion Date
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                         </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($projects as $project)
                        <tr class="table-row hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="checkbox"
                                    class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    value="{{ $project->id }}"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-lg object-cover" 
                                         src="{{ Storage::url($project->image) }}" 
                                         alt="{{ $project->title }}">
                                    <div class="ms-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $project->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($project->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->client_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($project->url)
                                    <a href="{{ $project->url }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-external-link-alt mr-1"></i> View Site
                                    </a>
                                @else
                                    <span class="text-gray-400">No URL</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full transition-colors duration-200
                                    {{ $project->status === 'published' ? 'bg-green-100 text-green-800 ring-1 ring-green-600/20' : 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $project->status === 'published' ? 'bg-green-600' : 'bg-yellow-600' }} mr-1.5"></span>
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->is_featured)
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 ring-1 ring-blue-600/20 transition-colors duration-200">
                                        <i class="fas fa-star text-blue-600 mr-1.5 text-xs"></i> Featured
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $project->completion_date->format('M d, Y') }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.projects.edit', $project) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-all duration-200 mr-2">
                                    <i class="fas fa-edit mr-1.5"></i> Edit
                                </a>
                                <button type="button" 
                                        onclick="openDeleteModal('{{ route('admin.projects.destroy', $project) }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-all duration-200">
                                    <i class="fas fa-trash mr-1.5"></i> Delete
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

            <div>
                {{ $projects->links() }}
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