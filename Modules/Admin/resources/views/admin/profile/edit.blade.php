@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Profile Settings</h1>
            <p class="mt-2 text-lg text-gray-600">Manage your account preferences and personal information</p>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <!-- Profit Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class=" text-sm font-medium">Total Profit</p>
                        <h3 class="text-3xl font-bold mt-1">${{ number_format($totalProfit, 2) }}</h3>
                    </div>
                    <div class="rounded-full bg-opacity-20 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm opacity-80">
                    <span>Lifetime earnings from all bookings</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class=" text-sm font-medium">This Month</p>
                        <h3 class="text-3xl font-bold mt-1 ">${{ number_format($profitHistory[date('n')-1]['profit'], 2) }}</h3>
                    </div>
                    <div class="rounded-full bg-white bg-opacity-20 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm  opacity-80">
                    <span>From {{ $profitHistory[date('n')-1]['bookings_count'] }} bookings this month</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 ">
                <div class="flex items-center justify-between">
                    <div>
                        <p class=" text-sm font-medium">Average Profit</p>
                        @php
                            $totalBookings = array_sum(array_column($profitHistory, 'bookings_count'));
                            $avgProfit = $totalBookings > 0 ? $totalProfit / $totalBookings : 0;
                        @endphp
                        <h3 class="text-3xl font-bold mt-1 ">${{ number_format($avgProfit, 2) }}</h3>
                    </div>
                    <div class="rounded-full bg-white bg-opacity-20 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm  opacity-80">
                    <span>Per booking average</span>
                </div>
            </div>
            
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Recent Profitable Bookings</h2>
                <p class="text-sm text-gray-600 mt-1">Your latest booking transactions</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stay Period</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->client_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->client_phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->hotel->name ?? 'Unknown Hotel' }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->roomType->name ?? 'Standard Room' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->enter_date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">to {{ $booking->leave_date->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($booking->total_client_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $booking->admin_profit > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($booking->admin_profit, 2) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No recent bookings found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif


        
@if(auth()->user()->isMarketer())
<!-- Profit Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class=" text-sm font-medium">Total Profit</p>
                <h3 class="text-3xl font-bold mt-1">${{ number_format($totalProfit, 2) }}</h3>
            </div>
            <div class="rounded-full bg-opacity-20 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm opacity-80">
            <span>Lifetime earnings from all bookings</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class=" text-sm font-medium">This Month</p>
                <h3 class="text-3xl font-bold mt-1 ">${{ number_format($profitHistory[date('n')-1]['profit'], 2) }}</h3>
            </div>
            <div class="rounded-full bg-white bg-opacity-20 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm  opacity-80">
            <span>From {{ $profitHistory[date('n')-1]['bookings_count'] }} bookings this month</span>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class=" text-sm font-medium">Average Profit</p>
                @php
                    $totalBookings = array_sum(array_column($profitHistory, 'bookings_count'));
                    $avgProfit = $totalBookings > 0 ? $totalProfit / $totalBookings : 0;
                @endphp
                <h3 class="text-3xl font-bold mt-1 ">${{ number_format($avgProfit, 2) }}</h3>
            </div>
            <div class="rounded-full bg-white bg-opacity-20 p-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm  opacity-80">
            <span>Per booking average</span>
        </div>
    </div>

</div>

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
@endif
        

        @if(auth()->user()->isHotelManager())
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

        @endif

        <!-- Profile Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Personal Information</h2>
                <form action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <div class="relative w-32 h-32 rounded-full overflow-hidden ring-4 ring-white shadow-lg">
                                @if(auth()->user()->image)
                                    <img class="h-full w-full object-cover" src="{{ asset('storage/'.auth()->user()->image)}}" alt="Profile Picture">
                                @else
                                    <img class="h-full w-full object-cover" src="https://placehold.co/150" alt="Profile Picture">
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span class=" text-sm">Change Photo</span>
                                </div>
                            </div>
                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"/>
                        </div>
                        <p class="text-sm text-gray-500">JPEG, PNG, or GIF (Max 5MB)</p>
                    </div>
        
                    <!-- Name Section -->
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{auth()->user()->name}}" class="peer w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-transparent transition-all duration-200" placeholder=" " />
                        <label for="name" class="absolute ltr:left-2 rtl:right-2 -top-2.5 bg-white px-2 text-sm text-gray-600 transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 ltr:peer-placeholder-shown:left-4 rtl:peer-placeholder-shown:right-4 peer-focus:-top-2.5 ltr:peer-focus:left-2 rtl:peer-focus:right-2 peer-focus:text-sm peer-focus:text-blue-600">Full Name</label>
                    </div>
            
                    <!-- Email Section -->
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{auth()->user()->email}}" class="peer w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-transparent transition-all duration-200" placeholder=" " />
                        <label for="name" class="absolute ltr:left-2 rtl:right-2 -top-2.5 bg-white px-2 text-sm text-gray-600 transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 ltr:peer-placeholder-shown:left-4 rtl:peer-placeholder-shown:right-4 peer-focus:-top-2.5 ltr:peer-focus:left-2 rtl:peer-focus:right-2 peer-focus:text-sm peer-focus:text-blue-600">Email address</label>
                    </div>
            
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600  font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mt-8">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Security Settings</h2>
                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <!-- Current Password -->
                    <div class="relative">
                        <input type="password" name="current_password" id="current-password" class="peer w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-transparent transition-all duration-200" placeholder=" " />
                        <label for="current-password" class="absolute rtl:right-2 ltr:left-2 -top-2.5 bg-white px-2 text-sm text-gray-600 transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-placeholder-shown:left-4 peer-focus:-top-2.5 peer-focus:left-2 peer-focus:text-sm peer-focus:text-blue-600">Current Password</label>
                    </div>
                
                    <!-- New Password -->
                    <div class="relative">
                        <input type="password" id="new-password" name="password" class="peer w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-transparent transition-all duration-200" placeholder=" " />
                        <label for="new-password" class="absolute rtl:right-2 ltr:left-2 -top-2.5 bg-white px-2 text-sm text-gray-600 transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-placeholder-shown:left-4 peer-focus:-top-2.5 peer-focus:left-2 peer-focus:text-sm peer-focus:text-blue-600">New Password</label>
                    </div>
                
                    <!-- Confirm New Password -->
                    <div class="relative">
                        <input type="password" id="confirm-password" name="password_confirmation" class="peer w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-transparent transition-all duration-200" placeholder=" " />
                        <label for="confirm-password" class="absolute rtl:right-2 ltr:left-2 -top-2.5 bg-white px-2 text-sm text-gray-600 transition-all duration-200 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-placeholder-shown:left-4 peer-focus:-top-2.5 peer-focus:left-2 peer-focus:text-sm peer-focus:text-blue-600">Confirm New Password</label>
                    </div>
                
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600  font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Make sure this section is properly yielded in your main layout -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Wait for the DOM to be fully loaded
    window.onload = function() {
        // Check if the chart element exists
        const chartElement = document.getElementById('profitChart');
        if (!chartElement) {
            console.log('Chart element not found - this is expected for hotel managers');
            return;
        }

        try {
            // Prepare the data for the chart
            const profitData = @json(isset($profitHistory) ? array_map(function($month) { return $month['profit']; }, $profitHistory) : []);
            const monthLabels = @json(isset($profitHistory) ? array_map(function($month) { return $month['month']; }, $profitHistory) : []);
            
            // Get the chart context
            const ctx = chartElement.getContext('2d');
            if (!ctx) {
                console.error('Could not get 2D context for chart');
                return;
            }
            
            // Create the chart
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Monthly Profit',
                        data: profitData,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                                    return '$' + context.raw.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    }
                }
            });
            
            console.log('Chart initialized successfully');
        } catch (error) {
            console.error('Error initializing chart:', error);
        }
    };
</script>
@endsection