@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Hotel Details</h1>
            <p class="text-gray-600">{{ $hotel->name }}</p>
        </div>
        <div>
            <a href="{{ route('hotels.edit', $hotel) }}"
               class="inline-block px-4 py-2 me-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-edit me-2"></i> Edit Hotel
            </a>
            <a href="{{ route('hotels.index') }}"
               class="inline-block px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Hotel Information Card -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Hotel Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">General Information</h3>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Hotel Name:</span>
                                <span class="text-gray-800">{{ $hotel->name }}</span>
                            </div>
                            @if($hotel->address)
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Address:</span>
                                <span class="text-gray-800">{{ $hotel->address }}</span>
                            </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Contact Details</h3>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Contact Person:</span>
                                <span class="text-gray-800">{{ $hotel->contact_person ?? 'Not specified' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Contact Phone:</span>
                                <span class="text-gray-800">{{ $hotel->contact_phone ?? 'Not specified' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Manager:</span>
                                <span class="text-gray-800">{{ $hotel->manager ? $hotel->manager->name : 'Not assigned' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Statistics Card -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Booking Statistics</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-blue-800 text-sm font-medium">Total Bookings</p>
                            <p class="text-blue-900 text-2xl font-bold">{{ $bookingsCount }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-green-800 text-sm font-medium">Active Bookings</p>
                            <p class="text-green-900 text-2xl font-bold">{{ $activeBookingsCount }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <p class="text-purple-800 text-sm font-medium">Total Revenue</p>
                            <p class="text-purple-900 text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>

                    <h3 class="text-md font-semibold text-gray-700 mb-3">Recent Bookings</h3>
                    
                    @if($recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
                                        {{ $booking->client_name }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
                                        {{ $booking->roomType ? $booking->roomType->name : 'Standard' }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
                                        {{ $booking->enter_date->format('M d') }} - {{ $booking->leave_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
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
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <a href="{{ route('bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-500">
                        No bookings found for this hotel.
                    </div>
                    @endif
                    
                    <div class="mt-4 text-right">
                        <a href="{{ route('bookings.index', ['hotel_id' => $hotel->id]) }}" class="text-blue-600 hover:text-blue-900">
                            View all bookings <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-span-1">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('hotels.edit', $hotel) }}"
                           class="w-full block text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-edit me-2"></i> Edit Hotel
                        </a>
                        
                        <a href="{{ route('bookings.create', ['hotel_id' => $hotel->id]) }}"
                           class="w-full block text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-plus me-2"></i> New Booking
                        </a>

                        <a href="{{ route('reports.hotels', ['hotel_id' => $hotel->id]) }}"
                           class="w-full block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i class="fas fa-chart-bar me-2"></i> View Reports
                        </a>
                        
                        <button type="button" 
                                onclick="openDeleteModal('{{ route('hotels.destroy', $hotel) }}')"
                                class="w-full block text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-trash me-2"></i> Delete Hotel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Financial Summary Card -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Financial Summary</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Total Revenue</p>
                            <p class="text-gray-900 text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Payments Received</p>
                            <p class="text-gray-900 text-xl font-bold">${{ number_format($totalRevenue - $hotelRemainingBalance, 2) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Outstanding Balance</p>
                            <p class="text-{{ $hotelRemainingBalance > 0 ? 'red' : 'green' }}-600 text-xl font-bold">${{ number_format($hotelRemainingBalance, 2) }}</p>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-gray-600 text-sm mb-2">Payment Status</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $paidPercentage = $totalRevenue > 0 ? 
                                        min(100, (($totalRevenue - $hotelRemainingBalance) / $totalRevenue) * 100) : 100;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $paidPercentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ round($paidPercentage) }}% paid</p>
                        </div>
                    </div>
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
                        Are you sure you want to delete this hotel? All associated bookings will remain in the system but will no longer be linked to this hotel.
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