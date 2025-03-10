@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <!-- Admin Stats -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-building text-blue-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Hotels</h3>
                        <p class="text-2xl font-semibold">{{ $stats['total_hotels'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-calendar-check text-green-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Bookings</h3>
                        <p class="text-2xl font-semibold">{{ $stats['total_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-purple-500 bg-opacity-10">
                        <i class="fas fa-users text-purple-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Marketers</h3>
                        <p class="text-2xl font-semibold">{{ $stats['total_marketers'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-yellow-500 bg-opacity-10">
                        <i class="fas fa-dollar-sign text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Profit</h3>
                        <p class="text-2xl font-semibold">${{ number_format($stats['admin_profit'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isMarketer())
            <!-- Marketer Stats -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Bookings</h3>
                        <p class="text-2xl font-semibold">{{ $stats['total_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-calendar-alt text-green-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Active Bookings</h3>
                        <p class="text-2xl font-semibold">{{ $stats['active_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-purple-500 bg-opacity-10">
                        <i class="fas fa-dollar-sign text-purple-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Profit</h3>
                        <p class="text-2xl font-semibold">${{ number_format($stats['marketer_profit'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

           
        @elseif(auth()->user()->isHotelManager())
            <!-- Hotel Manager Stats -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Bookings</h3>
                        <p class="text-2xl font-semibold">{{ $stats['total_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-calendar-alt text-green-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Active Bookings</h3>
                        <p class="text-2xl font-semibold">{{ $stats['active_bookings'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-purple-500 bg-opacity-10">
                        <i class="fas fa-dollar-sign text-purple-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Hotel Revenue</h3>
                        <p class="text-2xl font-semibold">${{ number_format($stats['hotel_revenue'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

           
        @endif
    </div>


    <!-- Recent Transactions -->
    @if(isset($recentBookings) && count($recentBookings) > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Bookings</h3>
            <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stay Period</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->client_name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->client_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $booking->hotel->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->roomType->name ?? 'Standard Room' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->enter_date)->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">to {{ \Carbon\Carbon::parse($booking->leave_date)->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                ${{ number_format($booking->total_client_price, 2) }}
                            @elseif(auth()->user()->isMarketer())
                                ${{ number_format($booking->total_client_price, 2) }}
                            @elseif(auth()->user()->isHotelManager())
                                ${{ number_format($booking->total_buying_price, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full 
                                @if($booking->status === 'confirmed')
                                    bg-green-50 text-green-700 border border-green-100
                                @elseif($booking->status === 'pending')
                                    bg-yellow-50 text-yellow-700 border border-yellow-100
                                @elseif($booking->status === 'cancelled')
                                    bg-red-50 text-red-700 border border-red-100
                                @elseif($booking->status === 'completed')
                                    bg-blue-50 text-blue-700 border border-blue-100
                                @endif
                            ">
                                <span class="h-1.5 w-1.5 rounded-full 
                                    @if($booking->status === 'confirmed')
                                        bg-green-600
                                    @elseif($booking->status === 'pending')
                                        bg-yellow-600
                                    @elseif($booking->status === 'cancelled')
                                        bg-red-600
                                    @elseif($booking->status === 'completed')
                                        bg-blue-600
                                    @endif
                                "></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-1 rounded-lg transition-colors duration-200">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@endsection

@section('js')

@endsection