@extends('admin.layouts.app')

@section('content')


<div class="container mx-auto p-10">
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Form Header -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profile</h2>
            <form action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Profile Picture Section -->
                <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex items-center gap-x-4">
                    <div class="shrink-0">
                    @if(auth()->user()->image)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ asset('storage/'.auth()->user()->image)}}" alt="Profile Picture">
                    @else
                        <img class="h-16 w-16 rounded-full object-cover" src="https://placehold.co/150" alt="Profile Picture">
                    @endif
                    </div>
                    <div>
                    <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-500 mt-1">JPEG, PNG, or GIF (Max 5MB)</p>
                    </div>
                </div>
                </div>
        
                <!-- Name Section -->
                <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{auth()->user()->name}}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your full name" value="John Doe" />
                </div>
        
                <!-- Email Section -->
                <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{auth()->user()->email}}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email" value="johndoe@example.com" />
                </div>
        
                <!-- Save Button -->
                <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Changes
                </button>
                </div>
        
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h2>
            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                    <!-- Current Password -->
                    <div class="mb-6">
                    <label for="current-password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your current password" />
                    </div>
                
                    <!-- New Password -->
                    <div class="mb-6">
                    <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="new-password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your new password" />
                    </div>
                
                    <!-- Confirm New Password -->
                    <div class="mb-6">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm your new password" />
                    </div>
                
                    <!-- Save Button -->
                    <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Change Password
                    </button>
                    </div>
        </div>
    </div>

</div>
@endsection
