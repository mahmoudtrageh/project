<!-- Sidebar -->
<aside id="sidebar"
    class="sidebar-transition bg-white text-gray-800 sidebar-expanded fixed min-h-screen z-[999] shadow-xl border-r border-gray-100 transform transition-transform duration-300 ease-in-out {{ Session::get('locale') === 'ar' ? 'right-0 translate-x-full md:translate-x-0' : 'left-0 -translate-x-full md:translate-x-0' }}">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
     <span class="text-xl font-bold gradient-text sidebar-text">{{ __('Admin Panel') }}</span>
     <button id="toggleSidebar"
         class="text-gray-600 hidden md:block hover:bg-gray-100 p-2 rounded-lg transition-all duration-200">
         <i id="toggleIcon" class="fas fa-chevron-left text-sm"></i>
     </button>
     <button id="closeSidebar"
         class="md:hidden text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200">
         <i class="fas fa-times text-sm"></i>
     </button>
 </div>

 <nav class="mt-6 px-3">
     <!-- Dashboard Link (visible to all) -->
     <a href="{{ route('dashboard') }}"
         class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : '' }}">
         <i class="fas fa-tachometer-alt w-5 h-5 sidebar-icon {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         <span class="ms-3 font-medium sidebar-text">{{ trans('dashboard') }}</span>
     </a>

     <!-- Admin and Super Admin Only Sections -->
     @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
         <!-- Admins Dropdown -->
         <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admins.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-users-gear w-5 h-5 sidebar-icon {{ request()->routeIs('admins.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('Admins') }}</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admins.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admins.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admins.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Admins</a>
                <a href="{{ route('admins.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admins.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New admin</a>
            </div>
        </div>

        <!-- Booking Source Dropdown -->
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('booking-source.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-tag w-5 h-5 sidebar-icon {{ request()->routeIs('booking-source.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('Booking Source') }}</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('booking-source.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('booking-source.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('booking-source.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Booking Source</a>
                <a href="{{ route('booking-source.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('booking-source.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New source</a>
            </div>
        </div>

        <!-- Reports Dropdown -->
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-chart-bar w-5 h-5 sidebar-icon {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('Reports') }}</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('reports.bookings') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('reports.bookings') ? 'bg-blue-100 text-blue-600' : '' }}">Bookings Report</a>
                <a href="{{ route('reports.hotels') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('reports.hotels') ? 'bg-blue-100 text-blue-600' : '' }}">Hotels Report</a>
                <a href="{{ route('reports.marketers') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('reports.marketers') ? 'bg-blue-100 text-blue-600' : '' }}">Marketers Report</a>
                <a href="{{ route('reports.financial') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('reports.financial') ? 'bg-blue-100 text-blue-600' : '' }}">Financial Report</a>
            </div>
        </div>
        
     @endif

    <!-- Hotels Section - Visible to Admin, Super Admin and Hotel Managers -->
    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() || Auth::user()->isHotelManager())
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('hotels.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-hotel w-5 h-5 sidebar-icon {{ request()->routeIs('hotels.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('Hotels') }}</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('hotels.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('hotels.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('hotels.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Hotels</a>
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <a href="{{ route('hotels.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('hotels.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New hotel</a>
                @endif
            </div>
        </div>
    @endif

    <!-- Bookings Section - Visible to Admin, Super Admin and Marketers -->
    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() || Auth::user()->isMarketer())
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('bookings.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-calendar-check w-5 h-5 sidebar-icon {{ request()->routeIs('bookings.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('Bookings') }}</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('bookings.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('bookings.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('bookings.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Bookings</a>
                <a href="{{ route('bookings.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('bookings.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New booking</a>
            </div>
        </div>
    @endif

    @if(Auth::user()->isSuperAdmin())
    <!-- Languages Dropdown -->
    <div class="dropdown relative mt-2">
        <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.languages.*') ? 'bg-blue-100 text-blue-600' : '' }}">
            <div class="flex items-center">
                <i class="fa-solid fa-language w-5 h-5 sidebar-icon {{ request()->routeIs('admin.languages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                <span class="ms-3 font-medium sidebar-text">{{ __('languages') }}</span>
            </div>
            <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.languages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
        </div>
        <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
            <a href="{{ route('admin.languages.index') }}"
                class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.languages.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Languages</a>
            <a href="{{ route('admin.languages.create') }}"
                class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.languages.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New language</a>
        </div>
    </div>

    <!-- Settings Link -->
    <a href="{{ route('admin.settings') }}"
        class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-600' : '' }}">
        <i class="fa-solid fa-sliders w-5 h-5 sidebar-icon {{ request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-400' }}"></i>
        <span class="ms-3 font-medium sidebar-text">{{ __('settings') }}</span>
    </a>
    @endif
 </nav>
</aside>