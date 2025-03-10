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
        <!-- Enhanced Filter Section -->
        <div class="p-6 border-b border-gray-100">
            <form action="{{ route('bookings.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Box -->
                    <div class="col-span-1 lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" placeholder="Booking #, name or phone..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <span class="absolute ltr:right-3 rtl:left-3 top-2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Booking Status</label>
                        <select id="status" name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Date Range Selector with Improved UI -->
                    <div class="col-span-1">
                        <label for="enter_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                id="enter_date" 
                                name="enter_date" 
                                value="{{ request('enter_date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <span class="absolute right-3 top-2 text-gray-400">
                                <i class="fas fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <label for="leave_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                id="leave_date" 
                                name="leave_date" 
                                value="{{ request('leave_date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <span class="absolute right-3 top-2 text-gray-400">
                                <i class="fas fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Secondary Filters Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <!-- Hotel Filter -->
                    <div class="col-span-1">
                        <label for="hotel_id" class="block text-sm font-medium text-gray-700 mb-1">Hotel</label>
                        <select id="hotel_id" name="hotel_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Hotels</option>
                                            @foreach($hotels as $id => $name)
                                @if(is_object($name))
                                    <option value="{{ $name->id }}" {{ request('hotel_id') == $name->id ? 'selected' : '' }}>
                                        {{ $name->name }}
                                    </option>
                                @else
                                    <option value="{{ $id }}" {{ request('hotel_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Room Type Filter -->
                    <div class="col-span-1">
                        <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                        <select id="room_type_id" name="room_type_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Room Types</option>
                            @if(isset($roomTypes) && is_iterable($roomTypes))
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}" {{ request('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                        {{ $roomType->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <!-- Date Range Presets -->
                    <div class="col-span-1">
                        <label for="date_preset" class="block text-sm font-medium text-gray-700 mb-1">Date Presets</label>
                        <select id="date_preset" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="applyDatePreset(this.value)">
                            <option value="">Custom Range</option>
                            <option value="today">Today</option>
                            <option value="tomorrow">Tomorrow</option>
                            <option value="this_week">This Week</option>
                            <option value="next_week">Next Week</option>
                            <option value="this_month">This Month</option>
                            <option value="next_month">Next Month</option>
                            <option value="past_bookings">Past Bookings</option>
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                    
                    <button type="button" 
                            onclick="clearFilters()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <i class="fas fa-times me-2"></i> Clear All
                    </button>
                    
                    <div class="ml-auto relative">
                        <button type="button"
                                id="exportDropdownButton"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-download me-2"></i> Export <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <div id="exportDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10">
                            <a href="{{ route('reports.export', ['type' => 'bookings', 'format' => 'csv'] + request()->all()) }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-csv mr-2"></i> Export as CSV
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'bookings', 'format' => 'excel'] + request()->all()) }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-excel mr-2"></i> Export as Excel
                            </a>
                            <a href="{{ route('reports.export', ['type' => 'bookings', 'format' => 'pdf'] + request()->all()) }}" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bookings Table Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">
                Bookings 
                @if(request()->anyFilled(['search', 'status', 'enter_date', 'leave_date', 'hotel_id', 'room_type_id']))
                    <span class="text-sm font-normal text-gray-500">(Filtered Results)</span>
                @endif
            </h2>
            
            <div class="text-sm text-gray-500">
                Showing {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings
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
                                                {{ $booking->booking_number }}
                                            </div>
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

                            @if($bookings->count() === 0)
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="font-medium">No bookings found</p>
                                        <p class="mt-1">Try adjusting your search or filter to find what you're looking for.</p>
                                        <button type="button" 
                                               onclick="clearFilters()"
                                               class="mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                            Clear All Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            {{ $bookings->appends(request()->query())->links() }}
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
    
    // Date picker elements
    const enterDateInput = document.getElementById('enter_date');
    const leaveDateInput = document.getElementById('leave_date');
    const datePresetSelect = document.getElementById('date_preset');

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
    
    // Clear all filter fields
    function clearFilters() {
        // Reset all form fields except the _token
        const form = document.getElementById('filterForm');
        const elements = form.elements;
        
        for (let i = 0; i < elements.length; i++) {
            const element = elements[i];
            if (element.name !== '_token') {
                if (element.type === 'text' || element.type === 'date') {
                    element.value = '';
                } else if (element.type === 'select-one') {
                    element.selectedIndex = 0;
                }
            }
        }
        
        // Submit the form with cleared filters
        form.submit();
    }
    
    // Date preset functionality
    function applyDatePreset(preset) {
        const today = new Date();
        let startDate = null;
        let endDate = null;
        
        switch(preset) {
            case 'today':
                startDate = today;
                endDate = today;
                break;
                
            case 'tomorrow':
                startDate = new Date(today);
                startDate.setDate(today.getDate() + 1);
                endDate = startDate;
                break;
                
            case 'this_week':
                startDate = new Date(today);
                const firstDayOfWeek = today.getDate() - today.getDay();
                startDate.setDate(firstDayOfWeek);
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                break;
                
            case 'next_week':
                startDate = new Date(today);
                const firstDayOfNextWeek = today.getDate() - today.getDay() + 7;
                startDate.setDate(firstDayOfNextWeek);
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                break;
                
            case 'this_month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
                
            case 'next_month':
                startDate = new Date(today.getFullYear(), today.getMonth() + 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 2, 0);
                break;
                
            case 'past_bookings':
                endDate = new Date(today);
                endDate.setDate(today.getDate() - 1);
                break;
        }
        
        // Format dates as YYYY-MM-DD
        if (startDate) {
            enterDateInput.value = formatDate(startDate);
        }
        
        if (endDate) {
            leaveDateInput.value = formatDate(endDate);
        }
    }
    
    // Format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Date validation - ensure leave_date is after enter_date
    enterDateInput.addEventListener('change', function() {
        if (leaveDateInput.value && this.value > leaveDateInput.value) {
            leaveDateInput.value = this.value;
        }
    });
    
    leaveDateInput.addEventListener('change', function() {
        if (enterDateInput.value && this.value < enterDateInput.value) {
            enterDateInput.value = this.value;
        }
    });
    
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all checkbox state when individual rows are clicked
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckboxState();
        });
    });
    
    function updateSelectAllCheckboxState() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectAllCheckbox.checked = checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
    }
</script>
@endsection