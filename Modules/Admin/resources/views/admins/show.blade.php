@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admins.index') }}" class="flex items-center text-gray-600 hover:text-blue-600">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
        </div>

        <!-- User Profile Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32"></div>
            <div class="relative px-8 py-6">
                <div class="absolute -top-16 left-8">
                    <div class="h-32 w-32 rounded-full border-4 border-white bg-white overflow-hidden shadow-lg flex items-center justify-center text-gray-500">
                        @if($admin->image)
                            <img src="{{ asset('storage/'.$admin->image) }}" alt="{{ $admin->name }}" class="h-full w-full object-cover">
                        @else
                            <i class="fas fa-user text-6xl"></i>
                        @endif
                    </div>
                </div>
                <div class="mt-16 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $admin->name }}</h1>
                        <div class="flex items-center mt-2">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($admin->user_type === 'admin')
                                    bg-purple-100 text-purple-800
                                @elseif($admin->user_type === 'marketer')
                                    bg-dark-100 text-dark-800
                                @elseif($admin->user_type === 'hotel_manager')
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($admin->user_type) }}
                            </span>
                            <span class="ml-4 text-sm text-gray-500">
                                Joined {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-3">
                        <a href="{{ route('admins.edit', $admin) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                        @if($admin->id !== Auth::id())
                        <button type="button" 
                                onclick="openDeleteModal('{{ route('admins.destroy', $admin) }}')"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i> Delete User
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">User Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Email Address</h3>
                        <p class="text-gray-900">{{ $admin->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Phone Number</h3>
                        <p class="text-gray-900">{{ $admin->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Role</h3>
                        <p class="text-gray-900">{{ ucfirst($admin->user_type) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Created By</h3>
                        <p class="text-gray-900">{{ $admin->creator ? $admin->creator->name : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Management Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Financial Overview</h2>
            </div>
            <div class="p-6">
                @if($admin->user_type === 'marketer')
                    <!-- Marketer Financial Info -->
                    @php
                        $marketerProfile = \Modules\Booking\Models\Marketer::where('admin_id', $admin->id)->first();
                        $totalBookings = 0;
                        $totalCommission = 0;
                        $pendingPayment = 0;
                        
                        if ($marketerProfile) {
                            $bookings = \Modules\Booking\Models\Booking::where('marketer_id', $marketerProfile->id)
                                ->where('status', '!=', 'cancelled')
                                ->get();
                                
                            $totalBookings = $bookings->count();
                            
                            foreach ($bookings as $booking) {
                                $totalCommission += $booking->marketer_profit;
                            }
                            
                            $paid = \Modules\Booking\Models\Payment::where('payment_type', 'admin_to_marketer')
                                ->whereIn('booking_id', $bookings->pluck('id')->toArray())
                                ->sum('amount');
                                
                            $pendingPayment = $totalCommission - $paid;
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                       
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold text-gray-800">Total Earnings</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">${{ number_format($totalCommission, 2) }}</p>
                            <p class="text-sm mt-4 text-green-400">From {{ $totalBookings }} bookings</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Payment</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">${{ number_format($pendingPayment, 2) }}</p>
                            <p class="text-sm mt-4 text-blue-400">Awaiting settlement</p>
                        </div>
                    </div>

                    @php
        $marketerProfile = \Modules\Booking\Models\Marketer::where('admin_id', $admin->id)->first();
        $totalBookings = 0;
        $bookings = collect();
        
        if ($marketerProfile) {
            $bookings = \Modules\Booking\Models\Booking::with(['hotel', 'roomType'])
                ->where('marketer_id', $marketerProfile->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $totalBookings = $bookings->count();
        }
    @endphp

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Bookings by {{ $admin->name }}</h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                {{ $totalBookings }} Bookings
            </span>
        </div>
        
        <div class="p-6">
            @if($totalBookings > 0)
                <div class="overflow-x-auto bg-white rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $booking->booking_number }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->rooms_number }} {{ $booking->rooms_number > 1 ? 'rooms' : 'room' }} 
                                            | {{ $booking->roomType ? $booking->roomType->name : 'Standard' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $booking->hotel ? $booking->hotel->name : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $booking->client_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->client_phone }}
                                        </div>
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
                                        <div class="text-sm font-medium text-green-600">
                                            ${{ number_format($booking->marketer_profit, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $marketerProfile->commission_percentage ?? 0 }}% rate
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-600">No bookings have been made by this marketer yet.</p>
                </div>
            @endif
        </div>
    </div>

                    <!-- Payment History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h3>
                        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($marketerProfile)
                                        @php
                                            $payments = \Modules\Booking\Models\Payment::where('payment_type', 'admin_to_marketer')
                                                ->whereIn('booking_id', \Modules\Booking\Models\Booking::where('marketer_id', $marketerProfile->id)->pluck('id')->toArray())
                                                ->orderBy('payment_date', 'desc')
                                                ->get();
                                        @endphp
                                        
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $payment->payment_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                                    ${{ number_format($payment->amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $payment->payment_method }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($payment->booking)
                                                        <a href="{{ route('bookings.show', $payment->booking->id) }}" class="text-blue-600 hover:text-blue-900">
                                                            #{{ $payment->booking->id }}
                                                        </a>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $payment->notes ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    No payment records found
                                                </td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No marketer profile found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Make a Payment -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Make a Payment</h3>
                        <form action="{{ route('marketers.make-payment', $marketerProfile ? $marketerProfile->id : 0) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
                                    <input type="number" id="amount" name="amount" min="0.01" step="0.01" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter payment amount">
                                </div>
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                    <select id="payment_method" name="payment_method" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select a method</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Add payment details or notes"></textarea>
                            </div>
                            <div>
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Process Payment
                                </button>
                            </div>
                        </form>
                    </div>


                @elseif($admin->user_type === 'hotel_manager')
                    <!-- Hotel Manager Financial Info -->
                    @php
                        $hotels = \Modules\Booking\Models\Hotel::where('manager_id', $admin->id)->get();
                        $totalRevenue = 0;
                        $totalPayments = 0;
                        
                        foreach ($hotels as $hotel) {
                            $bookings = \Modules\Booking\Models\Booking::where('hotel_id', $hotel->id)
                                ->where('status', '!=', 'cancelled')
                                ->get();
                                
                            foreach ($bookings as $booking) {
                                $totalRevenue += $booking->total_buying_price;
                            }
                            
                            $payments = \Modules\Booking\Models\Payment::where('payment_type', 'admin_to_hotel')
                                ->whereIn('booking_id', $bookings->pluck('id')->toArray())
                                ->sum('amount');
                                
                            $totalPayments += $payments;
                        }
                        
                        $pendingPayment = $totalRevenue - $totalPayments;
                    @endphp

                    <!-- Managed Hotels -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Managed Hotels</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @forelse($hotels as $hotel)
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                <div class="p-6">
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $hotel->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $hotel->address }}</p>
                                    <div class="mt-4">
                                        <a href="{{ route('hotels.show', $hotel) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Hotel Details <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 bg-gray-50 rounded-xl p-6 text-center">
                                <p class="text-gray-600">No hotels are currently managed by this user.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Financial Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold text-gray-800">Total Hotels</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">{{ $hotels->count() }}</p>
                            <p class="text-sm mt-4 text-blue-400">Hotels under management</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold text-gray-800">Total Revenue</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">${{ number_format($totalRevenue, 2) }}</p>
                            <p class="text-sm mt-4 text-green-400">Generated from all hotels</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Payment</h3>
                            <p class="text-3xl font-bold mt-2 text-gray-800">${{ number_format($pendingPayment, 2) }}</p>
                            <p class="text-sm mt-4 text-purple-400">Awaiting settlement</p>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h3>
                        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $hotelIds = $hotels->pluck('id')->toArray();
                                        $bookingIds = \Modules\Booking\Models\Booking::whereIn('hotel_id', $hotelIds)->pluck('id')->toArray();
                                        $payments = \Modules\Booking\Models\Payment::where('payment_type', 'admin_to_hotel')
                                            ->whereIn('booking_id', $bookingIds)
                                            ->orderBy('payment_date', 'desc')
                                            ->get();
                                    @endphp
                                    
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($payment->booking && $payment->booking->hotel)
                                                    {{ $payment->booking->hotel->name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_method }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($payment->booking)
                                                    <a href="{{ route('bookings.show', $payment->booking->id) }}" class="text-blue-600 hover:text-blue-900">
                                                        #{{ $payment->booking->id }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->notes ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No payment records found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Make a Payment -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Make a Hotel Payment</h3>
                        <form action="{{ route('hotels.make-payment') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="hotel_id" class="block text-sm font-medium text-gray-700 mb-1">Hotel</label>
                                    <select id="hotel_id" name="hotel_id" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select a hotel</option>
                                        @foreach($hotels as $hotel)
                                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
                                    <input type="number" id="amount" name="amount" min="0.01" step="0.01" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter payment amount">
                                </div>
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                    <select id="payment_method" name="payment_method" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select a method</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-1">Booking Reference (Optional)</label>
                                <select id="booking_id" name="booking_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">General Payment (not tied to a booking)</option>
                                    @foreach($hotels as $hotel)
                                        @php
                                            $hotelBookings = \Modules\Booking\Models\Booking::where('hotel_id', $hotel->id)
                                                ->where('status', '!=', 'cancelled')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                        @endphp
                                        
                                        @foreach($hotelBookings as $booking)
                                            <option value="{{ $booking->id }}">
                                                {{ $hotel->name }} - #{{ $booking->id }} ({{ $booking->client_name }})
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Add payment details or notes"></textarea>
                            </div>
                            <div>
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Process Payment
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif($admin->user_type === 'admin' || $admin->user_type === 'super_admin')
                    <!-- Admin Financial Overview -->
                    @php
                        $totalBookings = \Modules\Booking\Models\Booking::where('admin_id', $admin->id)
                            ->where('status', '!=', 'cancelled')
                            ->count();
                            
                        $revenue = \Modules\Booking\Models\Booking::where('admin_id', $admin->id)
                            ->where('status', '!=', 'cancelled')
                            ->sum(DB::raw('client_sell_price * rooms_number * DATEDIFF(leave_date, enter_date)'));
                            
                        $profit = 0;
                        $bookings = \Modules\Booking\Models\Booking::where('admin_id', $admin->id)
                            ->where('status', '!=', 'cancelled')
                            ->get();
                            
                        foreach ($bookings as $booking) {
                            $profit += $booking->admin_profit;
                        }
                        
                        $profitMargin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold">Total Bookings</h3>
                            <p class="text-3xl font-bold mt-2">{{ $totalBookings }}</p>
                            <p class="text-sm mt-4 text-blue-100">Successful bookings</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold">Total Revenue</h3>
                            <p class="text-3xl font-bold mt-2">${{ number_format($revenue, 2) }}</p>
                            <p class="text-sm mt-4 text-green-100">Gross revenue from all bookings</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold">Total Profit</h3>
                            <p class="text-3xl font-bold mt-2">${{ number_format($profit, 2) }}</p>
                            <p class="text-sm mt-4 text-purple-100">Net profit after costs</p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
                            <h3 class="text-lg font-semibold">Profit Margin</h3>
                            <p class="text-3xl font-bold mt-2">{{ number_format($profitMargin, 2) }}%</p>
                            <p class="text-sm mt-4 text-red-100">Average profit margin</p>
                        </div>
                    </div>

                    <!-- Monthly Profit Chart -->
                    <div class="bg-white rounded-xl shadow p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Profit</h3>
                        <div class="w-full h-64">
                            <canvas id="monthlyProfitChart"></canvas>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Transactions</h3>
                        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $bookingIds = \Modules\Booking\Models\Booking::where('admin_id', $admin->id)->pluck('id')->toArray();
                                        $payments = \Modules\Booking\Models\Payment::whereIn('booking_id', $bookingIds)
                                            ->orderBy('payment_date', 'desc')
                                            ->limit(10)
                                            ->get();
                                    @endphp
                                    
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($payment->payment_type === 'client_to_admin')
                                                        bg-green-100 text-green-800
                                                    @elseif($payment->payment_type === 'admin_to_hotel')
                                                        bg-red-100 text-red-800
                                                    @elseif($payment->payment_type === 'admin_to_marketer')
                                                        bg-dark-100 text-dark-800
                                                    @endif">
                                                    @if($payment->payment_type === 'client_to_admin')
                                                        Income
                                                    @elseif($payment->payment_type === 'admin_to_hotel')
                                                        Hotel Payment
                                                    @elseif($payment->payment_type === 'admin_to_marketer')
                                                        Marketer Payment
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->notes ?? 'Payment transaction' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                                @if($payment->payment_type === 'client_to_admin')
                                                    text-green-600
                                                @else
                                                    text-red-600
                                                @endif">
                                                @if($payment->payment_type === 'client_to_admin')
                                                    +${{ number_format($payment->amount, 2) }}
                                                @else
                                                    -${{ number_format($payment->amount, 2) }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($payment->booking)
                                                    <a href="{{ route('bookings.show', $payment->booking->id) }}" class="text-blue-600 hover:text-blue-900">
                                                        #{{ $payment->booking->id }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No transaction records found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Client Financial Info -->
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <p class="text-gray-600">No financial information available for client users.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Users Created Section (for admin) -->
        @if($admin->user_type === 'admin' || $admin->user_type === 'super_admin')
            @php
                $createdUsers = \Modules\Admin\Models\Admin::where('created_by', $admin->id)->get();
            @endphp
            
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Users Created</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $createdUsers->count() }} Users
                    </span>
                </div>
                <div class="p-6">
                    @if($createdUsers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($createdUsers as $user)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-start space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $user->name }}</h4>
                                            <p class="text-xs text-gray-600">{{ $user->email }}</p>
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium
                                                @if($user->user_type === 'admin')
                                                    bg-purple-100 text-purple-800
                                                @elseif($user->user_type === 'marketer')
                                                    bg-dark-100 text-dark-800
                                                @elseif($user->user_type === 'hotel_manager')
                                                    bg-blue-100 text-blue-800
                                                @else
                                                    bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($user->user_type) }}
                                            </span>
                                            <div class="mt-2">
                                                <a href="{{ route('admins.show', $user) }}" class="text-blue-600 hover:text-blue-900 text-xs">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center">No users have been created by this admin.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-dark opacity-50"></div>

    <!-- Modal content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to delete this user? This action cannot be undone.
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Delete Modal Functions
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

    // Monthly Profit Chart (for Admin)
    @if($admin->user_type === 'admin' || $admin->user_type === 'super_admin')
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('monthlyProfitChart');
            
            if (chartElement) {
                // Get monthly data
                @php
                    $currentYear = date('Y');
                    $monthlyData = [];
                    
                    for ($month = 1; $month <= 12; $month++) {
                        $startDate = Carbon\Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
                        $endDate = Carbon\Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
                        
                        $bookings = \Modules\Booking\Models\Booking::where('admin_id', $admin->id)
                            ->where('status', '!=', 'cancelled')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->get();
                            
                        $monthProfit = 0;
                        foreach ($bookings as $booking) {
                            $monthProfit += $booking->admin_profit;
                        }
                        
                        $monthlyData[] = [
                            'month' => Carbon\Carbon::createFromDate($currentYear, $month, 1)->format('M'),
                            'profit' => $monthProfit
                        ];
                    }
                @endphp
                
                const labels = @json(array_column($monthlyData, 'month'));
                const profits = @json(array_column($monthlyData, 'profit'));
                
                new Chart(chartElement, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Monthly Profit',
                            data: profits,
                            backgroundColor: 'rgba(79, 70, 229, 0.6)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '$' + context.raw.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    @endif
</script>
@endsection