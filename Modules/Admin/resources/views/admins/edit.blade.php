@extends('admin.layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
        <p class="text-gray-600">Edit User</p>
    </div>

    <div class="container">
        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admins.update', $admin) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $admin->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                placeholder="Enter full name"
                                required
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $admin->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="Enter email address"
                                required
                            />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $admin->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                placeholder="Enter phone number"
                            />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Type -->
                        <div>
                            <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">User Role <span class="text-red-500">*</span></label>
                            <select
                                id="user_type"
                                name="user_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_type') border-red-500 @enderror"
                                required
                                onchange="toggleMarketerFields()"
                            >
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('user_type', $admin->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="marketer" {{ old('user_type', $admin->user_type) == 'marketer' ? 'selected' : '' }}>Marketer</option>
                                <option value="hotel_manager" {{ old('user_type', $admin->user_type) == 'hotel_manager' ? 'selected' : '' }}>Hotel Manager</option>
                                <option value="client" {{ old('user_type', $admin->user_type) == 'client' ? 'selected' : '' }}>Client</option>
                            </select>
                            @error('user_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Marketer Information (Conditional) -->
                <div id="marketerFields" class="mb-6 {{ $admin->user_type != 'marketer' ? 'hidden' : '' }}">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Marketer Information</h2>
                    
                    <div class="mb-4">
                        <label for="commission_percentage" class="block text-sm font-medium text-gray-700 mb-2">Commission Percentage (%)</label>
                        <input
                            type="number"
                            id="commission_percentage"
                            name="commission_percentage"
                            value="{{ old('commission_percentage', $admin->isMarketer() ? $admin->marketerProfile->commission_percentage : 0) }}"
                            step="0.01"
                            min="0"
                            max="100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('commission_percentage') border-red-500 @enderror"
                            placeholder="Enter commission percentage"
                        />
                        @error('commission_percentage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">This is the percentage of the booking price that the marketer receives as commission.</p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="active"
                                name="active"
                                value="1"
                                {{ old('active', $admin->isMarketer() && $admin->marketerProfile->active ? 'checked' : '') }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label for="active" class="ml-2 block text-sm text-gray-700">Active Marketer</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">If unchecked, this marketer will not be available for selection when creating bookings.</p>
                    </div>
                </div>

                <!-- Password Information -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Password</h2>
                    <p class="text-sm text-gray-500 mb-4">Leave password fields empty if you don't want to change the password.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="Enter new password"
                            />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Confirm new password"
                            />
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Update User
                    </button>

                    <a href="{{ route('admins.index') }}" 
                       class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    function toggleMarketerFields() {
        const userType = document.getElementById('user_type').value;
        const marketerFields = document.getElementById('marketerFields');
        
        if (userType === 'marketer') {
            marketerFields.classList.remove('hidden');
        } else {
            marketerFields.classList.add('hidden');
        }
    }
    
    // Call the function on page load to handle form with validation errors
    document.addEventListener('DOMContentLoaded', function() {
        toggleMarketerFields();
    });
</script>
@endsection