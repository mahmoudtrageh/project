<header class="gradient-header shadow-sm">
    <div class="flex items-center px-4 py-3">
        <button id="openSidebar" class="md:hidden text-white hover:text-gray-200">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="flex items-center flex-1 px-4 justify-between">
            <!-- Search Bar -->
            <div class="relative flex-1 max-w-xs mr-4">
                <input type="text" placeholder="Search..." 
                       class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute ltr:right-3 rtl:left-3 top-3 text-gray-400"></i>
            </div>
            
            <div class="flex">
                <!-- Notification Dropdown -->
                <div class="relative mr-4">
                    <button id="notificationBtn" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">3</span>
                    </button>
                    <div id="notificationMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg py-2">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-envelope mr-2"></i> New message
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-plus mr-2"></i> New user registered
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Server issues
                        </a>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="userMenuBtn" class="flex items-center focus:outline-none">
                        @if(auth()->user()->image)
                            <img class="w-8 h-8 rounded-full" src="{{ asset('storage/'.auth()->user()->image)}}" alt="Profile Picture">
                        @else
                            <img src="https://placehold.co/40x40" alt="User" class="w-8 h-8 rounded-full">
                        @endif
                        <span class="ms-2 text-white hidden md:block">{{auth()->user()->name}}</span>
                        <i class="fas fa-chevron-down ms-2 text-gray-200"></i>
                    </button>
                    
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                        <a href="{{route('admin.profile')}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <form action="{{ route('admin.logout') }}" method="post" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-start px-4 py-2 text-gray-700 hover:bg-gray-100 appearance-none bg-transparent border-0 cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>