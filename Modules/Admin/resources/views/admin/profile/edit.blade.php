@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Profile Settings</h1>
            <p class="mt-2 text-lg text-gray-600">Manage your account preferences and personal information</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
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
                                    <span class="text-white text-sm">Change Photo</span>
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
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                        Save Changes
                    </button>
                </div>
            </form>
            </div>
        </div>

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
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
