@extends('admin.layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Booking Details</h1>
            <p class="text-gray-600">Booking #{{ $booking->id }}</p>
        </div>
        <div>
            <a href="{{ route('bookings.edit', $booking) }}"
               class="inline-block px-4 py-2 me-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-edit me-2"></i> Edit Booking
            </a>
            <a href="{{ route('bookings.index') }}"
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
        <!-- Booking Information Card -->
        <div class="col-span-2">
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Booking Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700 font-medium">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
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
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Client Details</h3>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Name:</span>
                                <span class="text-gray-800">{{ $booking->client_name }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Phone:</span>
                                <span class="text-gray-800">{{ $booking->client_phone }}</span>
                            </div>
                            @if($booking->bookingSource)
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Booking Source:</span>
                                <span class="text-gray-800">{{ $booking->bookingSource->name }}</span>
                            </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Hotel Details</h3>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Hotel:</span>
                                <span class="text-gray-800">{{ $booking->hotel->name }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Room Type:</span>
                                <span class="text-gray-800">{{ $booking->roomType ? $booking->roomType->name : 'Standard' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-600 font-medium">Number of Rooms:</span>
                                <span class="text-gray-800">{{ $booking->rooms_number }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Stay Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-2">
                                    <span class="text-gray-600 font-medium">Check-in Date:</span>
 
    <span class="text-gray-800">{{ $booking->enter_date->format('M d, Y') }}</span>
</div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Check-out Date:</span>
    <span class="text-gray-800">{{ $booking->leave_date->format('M d, Y') }}</span>
</div>
</div>
<div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Total Nights:</span>
    <span class="text-gray-800">{{ $nightsCount }}</span>
</div>
@if($booking->marketer)
<div class="mb-2">
    <span class="text-gray-600 font-medium">Marketer:</span>
    <span class="text-gray-800">{{ $booking->marketer->user->name }}</span>
</div>
@endif
</div>
</div>
</div>

@if($booking->note)
<div class="mt-6">
<h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Notes</h3>
<p class="text-gray-800">{{ $booking->note }}</p>
</div>
@endif
</div>
</div>

<!-- Financial Summary Card -->
<div class="bg-white rounded-lg shadow mb-6">
<div class="px-6 py-4 border-b border-gray-200">
<h2 class="text-lg font-semibold text-gray-800">Financial Summary</h2>
</div>
<div class="p-6">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Pricing Details</h3>
<div class="mb-2">
<span class="text-gray-600 font-medium">Client Price per Night:</span>
<span class="text-gray-800">${{ number_format($booking->client_sell_price, 2) }}</span>
</div>
<div class="mb-2">
<span class="text-gray-600 font-medium">Marketer Price per Night:</span>
<span class="text-gray-800">${{ number_format($booking->marketer_sell_price, 2) }}</span>
</div>
<div class="mb-2">
<span class="text-gray-600 font-medium">Hotel Cost per Night:</span>
<span class="text-gray-800">${{ number_format($booking->buying_price, 2) }}</span>
</div>
</div>

<div>
<h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Total Amounts</h3>
<div class="mb-2">
<span class="text-gray-600 font-medium">Total Client Price:</span>
<span class="text-gray-800 font-bold">${{ number_format($totalClientPrice, 2) }}</span>
</div>
<div class="mb-2">
<span class="text-gray-600 font-medium">Total Marketer Price:</span>
<span class="text-gray-800">${{ number_format($totalMarketerPrice, 2) }}</span>
</div>
<div class="mb-2">
<span class="text-gray-600 font-medium">Total Hotel Cost:</span>
<span class="text-gray-800">${{ number_format($totalBuyingPrice, 2) }}</span>
</div>
</div>
</div>

<div class="mt-6">
<h3 class="text-md font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-200">Profit Breakdown</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Marketer Profit:</span>
    <span class="text-gray-800">${{ number_format($marketerProfit, 2) }}</span>
</div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Admin Profit:</span>
    <span class="text-gray-800 font-bold">${{ number_format($adminProfit, 2) }}</span>
</div>
</div>
<div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Deposit Amount:</span>
    <span class="text-gray-800">${{ number_format($booking->deposit, 2) }}</span>
</div>
<div class="mb-2">
    <span class="text-gray-600 font-medium">Client Balance Due:</span>
    <span class="text-gray-800 font-bold {{ $clientRemainingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
        ${{ number_format($clientRemainingBalance, 2) }}
    </span>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Payments Card -->
<div class="bg-white rounded-lg shadow">
<div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
<h2 class="text-lg font-semibold text-gray-800">Payment History</h2>
@can('manage-payments')
<button type="button" onclick="openAddPaymentModal()"
class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
<i class="fas fa-plus me-2"></i> Add Payment
</button>
@endcan
</div>
<div class="p-6">
@if($booking->payments->count() > 0)
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200">
<thead class="bg-gray-50">
<tr>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
    @can('manage-payments')
    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
    @endcan
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
@foreach($booking->payments as $payment)
<tr>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
        {{ $payment->payment_date->format('M d, Y') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
        ${{ number_format($payment->amount, 2) }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
        @if($payment->payment_type == 'client_to_admin')
            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Client Payment</span>
        @elseif($payment->payment_type == 'admin_to_hotel')
            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Hotel Payment</span>
        @elseif($payment->payment_type == 'admin_to_marketer')
            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Marketer Payment</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
        {{ $payment->payment_method ?? 'N/A' }}
    </td>
    <td class="px-6 py-4 text-sm text-gray-800">
        {{ $payment->notes ?? 'N/A' }}
    </td>
    @can('manage-payments')
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <button type="button" 
                onclick="openDeletePaymentModal('{{ route('admin.payments.destroy', $payment) }}')"
                class="text-red-600 hover:text-red-900">
            <i class="fas fa-trash"></i> Delete
        </button>
    </td>
    @endcan
</tr>
@endforeach
</tbody>
</table>
</div>
@else
<div class="text-center py-4">
<p class="text-gray-500">No payments recorded yet.</p>
</div>
@endif

<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-blue-50 rounded-lg p-4">
<h4 class="text-blue-800 font-medium mb-2">Client Payments</h4>
<div class="text-blue-700 font-bold text-lg">${{ number_format($booking->payments->where('payment_type', 'client_to_admin')->sum('amount'), 2) }}</div>
<div class="text-blue-600 text-sm mt-1">Total paid by client</div>
</div>
<div class="bg-purple-50 rounded-lg p-4">
<h4 class="text-purple-800 font-medium mb-2">Hotel Payments</h4>
<div class="text-purple-700 font-bold text-lg">${{ number_format($booking->payments->where('payment_type', 'admin_to_hotel')->sum('amount'), 2) }}</div>
<div class="text-purple-600 text-sm mt-1">Total paid to hotel</div>
</div>
<div class="bg-yellow-50 rounded-lg p-4">
<h4 class="text-yellow-800 font-medium mb-2">Marketer Payments</h4>
<div class="text-yellow-700 font-bold text-lg">${{ number_format($booking->payments->where('payment_type', 'admin_to_marketer')->sum('amount'), 2) }}</div>
<div class="text-yellow-600 text-sm mt-1">Total paid to marketer</div>
</div>
</div>
</div>
</div>
</div>

<!-- Side Panel -->
<div class="col-span-1">
<!-- Balance Summary Card -->
<div class="bg-white rounded-lg shadow mb-6">
<div class="px-6 py-4 border-b border-gray-200">
<h2 class="text-lg font-semibold text-gray-800">Balance Summary</h2>
</div>
<div class="p-6">
<div class="mb-4">
<div class="flex justify-between items-center mb-1">
<span class="text-gray-700 font-medium">Client Balance:</span>
<span class="font-semibold {{ $clientRemainingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
${{ number_format($clientRemainingBalance, 2) }}
</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2">
@php
$clientPaidPercentage = $totalClientPrice > 0 ? 
    min(100, (($totalClientPrice - $clientRemainingBalance) / $totalClientPrice) * 100) : 100;
@endphp
<div class="bg-blue-600 h-2 rounded-full" style="width: {{ $clientPaidPercentage }}%"></div>
</div>
<div class="text-xs text-gray-500 mt-1">
{{ $clientPaidPercentage }}% paid of ${{ number_format($totalClientPrice, 2) }} total
</div>
</div>

<div class="mb-4">
<div class="flex justify-between items-center mb-1">
<span class="text-gray-700 font-medium">Hotel Balance:</span>
<span class="font-semibold {{ $hotelRemainingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
${{ number_format($hotelRemainingBalance, 2) }}
</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2">
@php
$hotelPaidPercentage = $totalBuyingPrice > 0 ? 
    min(100, (($totalBuyingPrice - $hotelRemainingBalance) / $totalBuyingPrice) * 100) : 100;
@endphp
<div class="bg-purple-600 h-2 rounded-full" style="width: {{ $hotelPaidPercentage }}%"></div>
</div>
<div class="text-xs text-gray-500 mt-1">
{{ $hotelPaidPercentage }}% paid of ${{ number_format($totalBuyingPrice, 2) }} total
</div>
</div>

@if($booking->marketer)
<div class="mb-4">
<div class="flex justify-between items-center mb-1">
<span class="text-gray-700 font-medium">Marketer Balance:</span>
<span class="font-semibold {{ $marketerRemainingBalance > 0 ? 'text-red-600' : 'text-green-600' }}">
${{ number_format($marketerRemainingBalance, 2) }}
</span>
</div>
<div class="w-full bg-gray-200 rounded-full h-2">
@php
$marketerPaidPercentage = $marketerProfit > 0 ? 
    min(100, (($marketerProfit - $marketerRemainingBalance) / $marketerProfit) * 100) : 100;
@endphp
<div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $marketerPaidPercentage }}%"></div>
</div>
<div class="text-xs text-gray-500 mt-1">
{{ $marketerPaidPercentage }}% paid of ${{ number_format($marketerProfit, 2) }} total
</div>
</div>
@endif
</div>
</div>

<!-- Quick Actions Card -->
<div class="bg-white rounded-lg shadow">
<div class="px-6 py-4 border-b border-gray-200">
<h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
</div>
<div class="p-6">
<div class="space-y-3">
<a href="{{ route('bookings.edit', $booking) }}"
class="w-full block text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
<i class="fas fa-edit me-2"></i> Edit Booking
</a>

@can('manage-payments')
<button type="button" onclick="openAddPaymentModal()"
class="w-full block text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
<i class="fas fa-dollar-sign me-2"></i> Record Payment
</button>
@endcan

@if($booking->status == 'pending')
<form action="{{ route('bookings.update', $booking) }}" method="POST">
@csrf
@method('PUT')
<input type="hidden" name="status" value="confirmed">
<input type="hidden" name="client_name" value="{{ $booking->client_name }}">
<input type="hidden" name="client_phone" value="{{ $booking->client_phone }}">
<input type="hidden" name="hotel_id" value="{{ $booking->hotel_id }}">
<input type="hidden" name="enter_date" value="{{ $booking->enter_date->format('Y-m-d') }}">
<input type="hidden" name="leave_date" value="{{ $booking->leave_date->format('Y-m-d') }}">
<input type="hidden" name="client_sell_price" value="{{ $booking->client_sell_price }}">
<input type="hidden" name="marketer_sell_price" value="{{ $booking->marketer_sell_price }}">
<input type="hidden" name="buying_price" value="{{ $booking->buying_price }}">
<input type="hidden" name="rooms_number" value="{{ $booking->rooms_number }}">
<button type="submit"
    class="w-full block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
<i class="fas fa-check me-2"></i> Confirm Booking
</button>
</form>
@endif

@if($booking->status != 'cancelled')
<form action="{{ route('bookings.update', $booking) }}" method="POST" 
onsubmit="return confirm('Are you sure you want to cancel this booking?');">
@csrf
@method('PUT')
<input type="hidden" name="status" value="cancelled">
<input type="hidden" name="client_name" value="{{ $booking->client_name }}">
<input type="hidden" name="client_phone" value="{{ $booking->client_phone }}">
<input type="hidden" name="hotel_id" value="{{ $booking->hotel_id }}">
<input type="hidden" name="enter_date" value="{{ $booking->enter_date->format('Y-m-d') }}">
<input type="hidden" name="leave_date" value="{{ $booking->leave_date->format('Y-m-d') }}">
<input type="hidden" name="client_sell_price" value="{{ $booking->client_sell_price }}">
<input type="hidden" name="marketer_sell_price" value="{{ $booking->marketer_sell_price }}">
<input type="hidden" name="buying_price" value="{{ $booking->buying_price }}">
<input type="hidden" name="rooms_number" value="{{ $booking->rooms_number }}">
<button type="submit"
    class="w-full block text-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
<i class="fas fa-times me-2"></i> Cancel Booking
</button>
</form>
@endif

@if($booking->status != 'completed' && $booking->status != 'cancelled')
<form action="{{ route('bookings.update', $booking) }}" method="POST">
@csrf
@method('PUT')
<input type="hidden" name="status" value="completed">
<input type="hidden" name="client_name" value="{{ $booking->client_name }}">
<input type="hidden" name="client_phone" value="{{ $booking->client_phone }}">
<input type="hidden" name="hotel_id" value="{{ $booking->hotel_id }}">
<input type="hidden" name="enter_date" value="{{ $booking->enter_date->format('Y-m-d') }}">
<input type="hidden" name="leave_date" value="{{ $booking->leave_date->format('Y-m-d') }}">
<input type="hidden" name="client_sell_price" value="{{ $booking->client_sell_price }}">
<input type="hidden" name="marketer_sell_price" value="{{ $booking->marketer_sell_price }}">
<input type="hidden" name="buying_price" value="{{ $booking->buying_price }}">
<input type="hidden" name="rooms_number" value="{{ $booking->rooms_number }}">
<button type="submit"
    class="w-full block text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
<i class="fas fa-check-circle me-2"></i> Mark as Completed
</button>
</form>
@endif
</div>
</div>
</div>
</div>
</div>

<!-- Add Payment Modal -->
@can('manage-payments')
<div id="addPaymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
<!-- Background overlay -->
<div class="fixed inset-0 bg-black opacity-50"></div>

<!-- Modal content -->
<div class="relative min-h-screen flex items-center justify-center p-4">
<div class="relative bg-white rounded-lg w-full max-w-md">
<div class="p-6">
<h3 class="text-lg font-medium text-gray-900 mb-4">Add Payment</h3>

<form id="paymentForm" action="{{ route('admin.payments.store') }}" method="POST">
@csrf
<input type="hidden" name="booking_id" value="{{ $booking->id }}">

<div class="mb-4">
<label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">Payment Type <span class="text-red-500">*</span></label>
<select
id="payment_type"
name="payment_type"
class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
required
>
<option value="client_to_admin">Payment from Client</option>
<option value="admin_to_hotel">Payment to Hotel</option>
@if($booking->marketer)
<option value="admin_to_marketer">Payment to Marketer</option>
@endif
</select>
</div>

<div class="mb-4">
<label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
<div class="relative">
<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
<input
    type="number"
    id="amount"
    name="amount"
    step="0.01"
    min="0.01"
    class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
    placeholder="0.00"
    required
/>
</div>
</div>

<div class="mb-4">
<label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
<input
type="date"
id="payment_date"
name="payment_date"
value="{{ date('Y-m-d') }}"
class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
required
/>
</div>

<div class="mb-4">
<label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
<input
type="text"
id="payment_method"
name="payment_method"
class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
placeholder="Cash, Credit Card, Bank Transfer, etc."
/>
</div>

<div class="mb-4">
<label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
<textarea
id="notes"
name="notes"
rows="2"
class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
placeholder="Additional information about this payment"
></textarea>
</div>

<div class="flex justify-end gap-x-3 mt-6">
<button type="button" 
    onclick="closeAddPaymentModal()"
    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
Cancel
</button>

<button type="submit" 
    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
Add Payment
</button>
</div>
</form>
</div>
</div>
</div>
</div>
@endcan

<!-- Delete Payment Modal -->
@can('manage-payments')
<div id="deletePaymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
<!-- Background overlay -->
<div class="fixed inset-0 bg-black opacity-50"></div>

<!-- Modal content -->
<div class="relative min-h-screen flex items-center justify-center p-4">
<div class="relative bg-white rounded-lg w-full max-w-md">
<div class="p-6">
<h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
<p class="text-sm text-gray-500 mb-6">
Are you sure you want to delete this payment? This action cannot be undone.
</p>

<div class="flex justify-end gap-x-3">
<button type="button" 
onclick="closeDeletePaymentModal()"
class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
Cancel
</button>

<form id="deletePaymentForm" method="POST" class="inline-block">
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
@endcan
@endsection

@section('js')
<script>
// Payment Modal Functions
@can('manage-payments')
const addPaymentModal = document.getElementById('addPaymentModal');
const deletePaymentModal = document.getElementById('deletePaymentModal');
const deletePaymentForm = document.getElementById('deletePaymentForm');

function openAddPaymentModal() {
addPaymentModal.classList.remove('hidden');
document.body.style.overflow = 'hidden';
}

function closeAddPaymentModal() {
addPaymentModal.classList.add('hidden');
document.body.style.overflow = 'auto';
}

function openDeletePaymentModal(deleteUrl) {
deletePaymentModal.classList.remove('hidden');
deletePaymentForm.action = deleteUrl;
document.body.style.overflow = 'hidden';
}

function closeDeletePaymentModal() {
deletePaymentModal.classList.add('hidden');
document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
addPaymentModal.addEventListener('click', function(e) {
if (e.target === addPaymentModal) {
closeAddPaymentModal();
}
});

deletePaymentModal.addEventListener('click', function(e) {
if (e.target === deletePaymentModal) {
closeDeletePaymentModal();
}
});

// Close modals with ESC key
document.addEventListener('keydown', function(e) {
if (e.key === 'Escape') {
if (!addPaymentModal.classList.contains('hidden')) {
closeAddPaymentModal();
}
if (!deletePaymentModal.classList.contains('hidden')) {
closeDeletePaymentModal();
}
}
});

// Auto-fill amount based on payment type
const paymentTypeSelect = document.getElementById('payment_type');
const amountInput = document.getElementById('amount');

paymentTypeSelect.addEventListener('change', function() {
const paymentType = this.value;

if (paymentType === 'client_to_admin') {
amountInput.value = {{ $clientRemainingBalance > 0 ? $clientRemainingBalance : 0 }};
} else if (paymentType === 'admin_to_hotel') {
amountInput.value = {{ $hotelRemainingBalance > 0 ? $hotelRemainingBalance : 0 }};
} else if (paymentType === 'admin_to_marketer') {
amountInput.value = {{ $marketerRemainingBalance > 0 ? $marketerRemainingBalance : 0 }};
}
});
@endcan
</script>
@endsection