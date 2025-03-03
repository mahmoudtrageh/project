@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Booking Management</h1>
        <p class="text-gray-600">Edit Booking #{{ $booking->id }}</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Client Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Client Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Name -->
                        <div>
                            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="client_name"
                                name="client_name"
                                value="{{ old('client_name', $booking->client_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_name') border-red-500 @enderror"
                                placeholder="Enter client's full name"
                                required
                            />
                            @error('client_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Client Phone -->
                        <div>
                            <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Client Phone <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="client_phone"
                                name="client_phone"
                                value="{{ old('client_phone', $booking->client_phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_phone') border-red-500 @enderror"
                                placeholder="Enter client's phone number"
                                required
                            />
                            @error('client_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Booking Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <!-- Hotel -->
                        <div>
                            <label for="hotel_id" class="block text-sm font-medium text-gray-700 mb-2">Hotel <span class="text-red-500">*</span></label>
                            <select
                                id="hotel_id"
                                name="hotel_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hotel_id') border-red-500 @enderror"
                                required
                            >
                                <option value="">Select Hotel</option>
                                @foreach($hotels as $id => $name)
                                    <option value="{{ $id }}" {{ old('hotel_id', $booking->hotel_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                            <select
                                id="room_type_id"
                                name="room_type_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('room_type_id') border-red-500 @enderror"
                            >
                                <option value="">Select Room Type</option>
                                @foreach($roomTypes as $id => $name)
                                    <option value="{{ $id }}" {{ old('room_type_id', $booking->room_type_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Number of Rooms -->
                        <div>
                            <label for="rooms_number" class="block text-sm font-medium text-gray-700 mb-2">Number of Rooms <span class="text-red-500">*</span></label>
                            <input
                                type="number"
                                id="rooms_number"
                                name="rooms_number"
                                value="{{ old('rooms_number', $booking->rooms_number) }}"
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('rooms_number') border-red-500 @enderror"
                                required
                            />
                            @error('rooms_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <!-- Check-in Date -->
                        <div>
                            <label for="enter_date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date <span class="text-red-500">*</span></label>
                            <input
                                type="date"
                                id="enter_date"
                                name="enter_date"
                                value="{{ old('enter_date', $booking->enter_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('enter_date') border-red-500 @enderror"
                                required
                            />
                            @error('enter_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check-out Date -->
                        <div>
                            <label for="leave_date" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date <span class="text-red-500">*</span></label>
                            <input
                                type="date"
                                id="leave_date"
                                name="leave_date"
                                value="{{ old('leave_date', $booking->leave_date->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('leave_date') border-red-500 @enderror"
                                required
                            />
                            @error('leave_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Booking Source -->
                    <div class="mb-4">
                        <label for="booking_source_id" class="block text-sm font-medium text-gray-700 mb-2">Booking Source</label>
                        <select
                            id="booking_source_id"
                            name="booking_source_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('booking_source_id') border-red-500 @enderror"
                        >
                            <option value="">Select Booking Source</option>
                            @foreach($bookingSources as $id => $name)
                                <option value="{{ $id }}" {{ old('booking_source_id', $booking->booking_source_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('booking_source_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marketer -->
                    <div class="mb-4">
                        <label for="marketer_id" class="block text-sm font-medium text-gray-700 mb-2">Marketer</label>
                        <select
                            id="marketer_id"
                            name="marketer_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('marketer_id') border-red-500 @enderror"
                        >
                            <option value="">Select Marketer</option>
                            @foreach($marketers as $id => $name)
                                <option value="{{ $id }}" {{ old('marketer_id', $booking->marketer_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marketer_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Note -->
                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                        <textarea
                            id="note"
                            name="note"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('note') border-red-500 @enderror"
                            placeholder="Any special requests or additional information"
                        >{{ old('note', $booking->note) }}</textarea>
                        @error('note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Pricing Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <!-- Client Sell Price -->
                        <div>
                            <label for="client_sell_price" class="block text-sm font-medium text-gray-700 mb-2">Client Price per Night <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                <input
                                    type="number"
                                    id="client_sell_price"
                                    name="client_sell_price"
                                    value="{{ old('client_sell_price', $booking->client_sell_price) }}"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_sell_price') border-red-500 @enderror"
                                    placeholder="0.00"
                                    required
                                />
                            </div>
                            @error('client_sell_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Marketer Sell Price -->
                        <div>
                            <label for="marketer_sell_price" class="block text-sm font-medium text-gray-700 mb-2">Marketer Price per Night <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                <input
                                    type="number"
                                    id="marketer_sell_price"
                                    name="marketer_sell_price"
                                    value="{{ old('marketer_sell_price', $booking->marketer_sell_price) }}"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('marketer_sell_price') border-red-500 @enderror"
                                    placeholder="0.00"
                                    required
                                />
                            </div>
                            @error('marketer_sell_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buying Price -->
                        <div>
                            <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-2">Hotel Cost per Night <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                <input
                                    type="number"
                                    id="buying_price"
                                    name="buying_price"
                                    value="{{ old('buying_price', $booking->buying_price) }}"
                                    step="0.01"
                                    min="0"
                                    class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('buying_price') border-red-500 @enderror"
                                    placeholder="0.00"
                                    required
                                />
                            </div>
                            @error('buying_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deposit is not editable after creation -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deposit Amount</label>
                        <div class="relative bg-gray-100">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                            <input
                                type="number"
                                value="{{ $booking->deposit }}"
                                step="0.01"
                                min="0"
                                class="w-full pl-8 px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                                disabled
                            />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Deposit cannot be changed after booking creation. Use payments to add additional amounts.</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Status</h2>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Booking Status <span class="text-red-500">*</span></label>
                        <select
                            id="status"
                            name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                            required
                        >
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Update Booking
                    </button>

                    <div>
                        <a href="{{ route('bookings.show', $booking) }}" 
                        class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 me-2">
                            View Details
                        </a>
                        
                        <a href="{{ route('bookings.index') }}" 
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validate that leave_date is after enter_date
        const enterDateInput = document.getElementById('enter_date');
        const leaveDateInput = document.getElementById('leave_date');

        leaveDateInput.addEventListener('change', function() {
            const enterDate = new Date(enterDateInput.value);
            const leaveDate = new Date(this.value);

            if (leaveDate <= enterDate) {
                alert('Check-out date must be after check-in date');
                this.value = '{{ $booking->leave_date->format('Y-m-d') }}'; // Reset to original value
            }
        });
    });
</script>
@endsection