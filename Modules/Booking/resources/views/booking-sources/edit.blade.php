@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Booking Source Management</h1>
        <p class="text-gray-600">Edit Booking Source</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('booking-source.update', $bookingSource) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Booking Source Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Booking Source Information</h2>
                    
                    <!-- Booking Source Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Booking Source Name <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $bookingSource->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Enter booking source name (e.g. Direct, Booking.com, Expedia)"
                            required
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Booking Source Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="Enter booking source description or notes (e.g. commission rates, contact details)"
                        >{{ old('description', $bookingSource->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Usage Information -->
                @if($bookingSource->bookings_count > 0)
                <div class="mb-6">
                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Usage Information</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This booking source is currently used in {{ $bookingSource->bookings_count }} booking(s). Changes will affect existing bookings.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Update Booking Source
                    </button>

                    <a href="{{ route('booking-source.index') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection