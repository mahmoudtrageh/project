@extends('admin.layouts.app')

@section('content')
  <div class="flex items-center justify-between gap-5 flex-wrap">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Booking Management</h1>
        <p class="text-gray-600">Manage hotel bookings</p>
    </div>

    <!-- Table -->
    <a href="{{ route('bookings.create') }}"
        class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-plus me-2"></i> Create Booking
    </a>
  </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Bookings</h2>
            <div class="flex items-center gap-x-6">
                <!-- Search Box -->
                <div class="relative w-full sm:w-48">
                    <form action="{{ route('bookings.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Search bookings..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button type="submit" class="absolute ltr:right-3 rtl:left-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Status Filter -->
                <select onchange="window.location.href='{{ route('bookings.index') }}?status='+this.value" 
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>

                <!-- Export Dropdown -->
                <div class="relative w-full sm:w-auto">
                    <button id="exportDropdownButton"
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Export <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                        <a href="{{ route('reports.export', 'bookings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
                                    Booking Info
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hotel
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        value="{{ $booking->id }}" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ms-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $booking->client_name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $booking->client_phone }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $booking->rooms_number }} {{ $booking->rooms_number > 1 ? 'rooms' : 'room' }} 
                                                | {{ $booking->roomType ? $booking->roomType->name : 'Standard' }}
                                            </div>
                                            @if($booking->note)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="font-semibold">Note:</span> {{ Str::limit($booking->note, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $booking->hotel->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($booking->status === 'confirmed')
                                            bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'cancelled')
                                            bg-red-100 text-red-800
                                        @elseif($booking->status === 'completed')
                                            bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $booking->enter_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        to {{ $booking->leave_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ({{ $booking->nights_count }} nights)
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        ${{ number_format($booking->total_client_price, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ${{ number_format($booking->client_sell_price, 2) }}/night
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('bookings.edit', $booking) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('bookings.destroy', $booking) }}')"
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
            {{ $bookings->links() }}
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
                        Are you sure you want to delete this booking? This action cannot be undone.
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
    const exportDropdownButton = document.getElementById('exportDropdownButton');
    const exportDropdown = document.getElementById('exportDropdown');

    // Delete Modal Functions
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

    // Export Dropdown
    exportDropdownButton.addEventListener('click', function() {
        exportDropdown.classList.toggle('hidden');
    });

    // Close export dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!exportDropdownButton.contains(e.target) && !exportDropdown.contains(e.target)) {
            exportDropdown.classList.add('hidden');
        }
    });
</script>
@endsection