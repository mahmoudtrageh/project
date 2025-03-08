@extends('admin.layouts.app')

@section('content')
  <div class="flex items-center justify-between gap-5 flex-wrap">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Newsletter Management</h1>
        <p class="text-gray-600">Manage your newsletters and subscribers</p>
    </div>

    <!-- Table -->
    <a href="{{ route('newsletters.create') }}"
        class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-plus me-2"></i> Create Newsletter
    </a>
  </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Newsletters</h2>
            
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
                                    Newsletter Info
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Recipients
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Analytics
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Scheduled/Sent At
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($newsletters as $newsletter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        value="{{ $newsletter->id }}" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ms-0">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $newsletter->title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit(strip_tags($newsletter->content), 50) }}
                                            </div>
                                            @if($newsletter->custom_css)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Custom CSS
                                                </span>
                                            @endif
                                            @if(!empty($newsletter->social_links))
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Social Links
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $newsletter->recipients->count() }}
                                        <span class="text-xs text-gray-500">subscribers</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($newsletter->status === 'sent')
                                            bg-green-100 text-green-800
                                        @elseif($newsletter->status === 'draft')
                                            bg-gray-100 text-gray-800
                                        @elseif($newsletter->status === 'scheduled')
                                            bg-blue-100 text-blue-800
                                        @elseif($newsletter->status === 'sending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($newsletter->status === 'failed')
                                            bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($newsletter->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($newsletter->analytics && $newsletter->status === 'sent')
                                        <div class="text-xs text-gray-500">
                                            <div class="mb-1">
                                                Open rate: <span class="font-medium">{{ $newsletter->analytics->open_rate }}%</span>
                                            </div>
                                            <div>
                                                Click rate: <span class="font-medium">{{ $newsletter->analytics->click_rate }}%</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">No data available</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if($newsletter->status === 'sent')
                                            <i class="far fa-paper-plane mr-1"></i> 
                                            {{ $newsletter->sent_at ? $newsletter->sent_at->format('M d, Y H:i') : 'N/A' }}
                                        @elseif($newsletter->status === 'scheduled')
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $newsletter->scheduled_at ? $newsletter->scheduled_at->format('M d, Y H:i') : 'N/A' }}
                                        @else
                                            Not scheduled
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($newsletter->status === 'draft' || $newsletter->status === 'scheduled')
                                        <a href="{{ route('newsletters.edit', $newsletter) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('newsletters.show', $newsletter) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                    @if($newsletter->status === 'draft')
                                        <form method="POST" action="{{ route('newsletters.send', $newsletter) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-paper-plane"></i> Send
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('newsletters.preview', $newsletter) }}" 
                                       target="_blank"
                                       class="text-purple-600 hover:text-purple-900 mr-3">
                                        <i class="fas fa-desktop"></i> Preview
                                    </a>
                                    
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('newsletters.destroy', $newsletter) }}')"
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
            {{ $newsletters->links() }}
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
                        Are you sure you want to delete this newsletter? This action cannot be undone.
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

</script>
@endsection