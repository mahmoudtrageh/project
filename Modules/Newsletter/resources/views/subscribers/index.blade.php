@extends('admin.layouts.app')

@section('content')
  <div class="flex items-center justify-between gap-5 flex-wrap">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Subscriber Management</h1>
        <p class="text-gray-600">Manage your newsletter subscribers</p>
    </div>

    <!-- Import Button -->
    <button type="button"
        onclick="openImportModal()"
        class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-file-import me-2"></i> Import Subscribers
    </button>
  </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Subscribers</h2>
            <div class="flex items-center gap-x-6">
               
                <!-- Status Filter -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('subscribers.index') }}" 
                       class="px-3 py-2 rounded-lg {{ request('filter') == '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All
                    </a>
                    <a href="{{ route('subscribers.index', ['filter' => 'active']) }}" 
                       class="px-3 py-2 rounded-lg {{ request('filter') == 'active' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Active
                    </a>
                    <a href="{{ route('subscribers.index', ['filter' => 'inactive']) }}" 
                       class="px-3 py-2 rounded-lg {{ request('filter') == 'inactive' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Inactive
                    </a>
                </div>

               
            </div>
        </div>

        @if (session('success'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-green-100 text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-red-100 text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

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
                                    Email
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Source
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subscribed
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Last Sent
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        value="{{ $subscriber->id }}" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $subscriber->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->source ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M j, Y') : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->last_sent_at ? $subscriber->last_sent_at->format('M j, Y') : 'Never' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(!$subscriber->is_active)
                                        <form action="{{ route('subscribers.reactivate', $subscriber) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-redo"></i> Reactivate
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('subscribers.destroy', $subscriber) }}')"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No subscribers found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            {{ $subscribers->links() }}
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black opacity-50"></div>

        <!-- Modal content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg w-full max-w-md">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Import Subscribers</h3>
                        <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('subscribers.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
                            <input type="file" id="file" name="file" accept=".csv,.txt" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">
                                The CSV file should contain at least an 'email' column, and optionally a 'name' column.
                            </p>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-md mb-4">
                            <p class="text-sm text-blue-800 font-medium mb-1">Note:</p>
                            <ul class="text-xs text-blue-700 list-disc list-inside">
                                <li>Skip invalid email addresses</li>
                                <li>Skip already active subscribers</li>
                                <li>Reactivate previously unsubscribed emails</li>
                            </ul>
                        </div>

                        <div class="flex justify-end gap-x-3 mt-6">
                            <button type="button" 
                                    onclick="closeImportModal()"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                        Are you sure you want to delete this subscriber? This action cannot be undone.
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
    // Export Dropdown
    const exportDropdownButton = document.getElementById('exportDropdownButton');
    const exportDropdown = document.getElementById('exportDropdown');

    if (exportDropdownButton && exportDropdown) {
        exportDropdownButton.addEventListener('click', function() {
            exportDropdown.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!exportDropdownButton.contains(e.target) && !exportDropdown.contains(e.target)) {
                exportDropdown.classList.add('hidden');
            }
        });
    }

    // Select all checkboxes
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Import modal functionality
    const importModal = document.getElementById('importModal');
    
    // Define the openImportModal function in the global scope
    window.openImportModal = function() {
        importModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.closeImportModal = function() {
        importModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close import modal when clicking outside
    importModal.addEventListener('click', function(e) {
        if (e.target === importModal) {
            closeImportModal();
        }
    });

    // Delete modal functionality
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

    // Close delete modal when clicking outside
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!importModal.classList.contains('hidden')) {
                closeImportModal();
            }
            if (!deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });
</script>
@endsection