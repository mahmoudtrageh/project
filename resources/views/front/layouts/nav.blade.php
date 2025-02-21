 <!-- Navbar -->
 <nav class="bg-white shadow-sm fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <span class="text-xl font-bold text-gray-800">{{settings()->get('site_name')}}</span>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="flex items-center md:hidden">
                <button
                    id="mobileMenuButton"
                    class="text-gray-600 hover:text-gray-900 focus:outline-none"
                >
                <i data-lucide="menu"></i>
                </button>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{route('home')}}" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="{{route('about')}}" class="text-gray-600 hover:text-gray-900">About</a>
                <a href="{{route('projects')}}" class="text-gray-600 hover:text-gray-900">Projects</a>
                <a href="{{route('blogs')}}" class="text-gray-600 hover:text-gray-900">Blog</a>
                <a href="{{route('contact')}}" class="text-gray-600 hover:text-gray-900">Contact</a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Hidden by Default) -->
    <div id="mobileMenu" class="hidden md:hidden bg-white">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Dropdown Button -->
            <button
                id="mobileDropdownButton"
                class="w-full flex justify-between items-center px-3 py-2 text-gray-600 hover:text-gray-900 focus:outline-none"
            >
                <span>Menu</span>
                <i data-lucide="chevron-down"></i>
            </button>

            <!-- Dropdown Links (Hidden by Default) -->
            <div id="mobileDropdown" class="hidden pl-4">
                <a href="{{route('home')}}" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Home</a>
                <a href="{{route('about')}}" class="block px-3 py-2 text-gray-600 hover:text-gray-900">About</a>
                <a href="{{route('projects')}}" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Projects</a>
                <a href="{{route('blogs')}}" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Blog</a>
                <a href="{{route('contact')}}" class="block px-3 py-2 text-gray-600 hover:text-gray-900">Contact</a>
            </div>
        </div>
    </div>
  </nav>