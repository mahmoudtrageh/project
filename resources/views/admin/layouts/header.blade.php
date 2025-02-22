<header class="shadow-xs bg-white border border-b border-b-gray-200 sticky top-0 z-[100]">
    <div class="flex items-center px-4 py-3">
        <button id="openSidebar" class="md:hidden text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-bars text-sm"></i>
        </button>
        
        <div class="flex items-center max-md:justify-between gap-3 md:flex-1 px-2 sm:px-4 justify-between">
            <!-- Search Bar -->
            <div class="relative md:flex-1 max-w-42 sm:max-w-xs md:ms-4">
                <input type="text" placeholder="{{ __('admin.search') }}" 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200">
                <i class="fas fa-search absolute {{ Session::get('locale') === 'ar' ? 'left-3' : 'right-3' }} top-4 text-gray-400"></i>
            </div>
            
            <div class="flex items-center gap-x-2 md:gap-x-4">
                <!-- Theme Switcher -->
                {{-- <div class="relative">
                    <button
                        id="themeToggleBtn"
                        class="relative inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-200 hover:bg-gray-300 transition-colors duration-200 focus:outline-none"
                        onclick="toggleTheme()"
                    >
                        <i id="themeIcon" class="fas fa-sun text-gray-700"></i>
                    </button>
                </div> --}}
                <!-- Language Switcher -->
                <div class="relative flex-shrink-0">
                    <form action="{{ route('language.switch') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="relative inline-flex items-center h-8 rounded-full bg-gray-200 w-16 transition-colors duration-200 focus:outline-none hover:bg-gray-300 {{ Session::get('locale') === 'ar' ? 'bg-blue-600 hover:bg-blue-700' : '' }}">
                            <input type="hidden" name="locale" value="{{ Session::get('locale') === 'ar' ? 'en' : 'ar' }}">
                            <span class="absolute font-medium text-xs text-gray-700 {{ Session::get('locale') === 'ar' ? 'left-2' : 'right-2' }}">{{ Session::get('locale') === 'ar' ? 'AR' : 'EN' }}</span>
                            <span class="absolute w-6 h-6 rounded-full bg-white shadow transform transition-transform duration-200 {{ Session::get('locale') === 'ar' ? '-translate-x-1' : 'translate-x-1' }}"></span>
                        </button>
                    </form>
                </div>
                
                <!-- Notification Dropdown -->
                <div class="relative">
                    <button 
                        id="notificationBtn" 
                        class="relative text-white hover:text-gray-200 focus:outline-hidden transition-colors duration-200"
                    >
                        <i class="fas fa-bell text-xl"></i>
                        @if(contacts()->count() > 0)
                            <span class="absolute top-0 {{ Session::get('locale') === 'ar' ? 'left-0 -translate-x-1/2' : 'right-0 translate-x-1/2' }} -translate-y-1/2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                {{ contacts()->count() }}
                            </span>
                        @endif
                    </button>
                    <div 
                        id="notificationMenu" 
                        class="hidden absolute {{ Session::get('locale') === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white rounded-lg shadow-lg py-2 z-50"
                    >
                        @forelse(contacts() as $contact)
                            <a 
                                href="{{ route('admin.contact.index', $contact->id) }}" 
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                            >
                                <i class="fas fa-envelope {{ Session::get('locale') === 'ar' ? 'ml-2' : 'mr-2' }} text-blue-500"></i> 
                                New message from {{ $contact->name }}
                            </a>
                        @empty
                            <p class="px-4 py-2 text-gray-500">No new notifications.</p>
                        @endforelse
                    </div>
                </div>
            
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button 
                        id="userMenuBtn" 
                        class="flex items-center focus:outline-hidden transition-colors duration-200"
                    >
                        @if(auth()->user()->image)
                            <img 
                                class="w-8 h-8 rounded-full object-cover" 
                                src="{{ asset('storage/' . auth()->user()->image) }}" 
                                alt="Profile Picture"
                            >
                        @else
                            <img 
                                src="https://placehold.co/40x40" 
                                alt="User" 
                                class="w-8 h-8 rounded-full"
                            >
                        @endif
                        <span class="ms-2 text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down ms-2 text-gray-400"></i>
                    </button>
                    
                    <div 
                        id="userMenu" 
                        class="hidden absolute {{ Session::get('locale') === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg py-3 z-[99]"
                    >
                        <a 
                            href="{{ route('admin.profile') }}" 
                            class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                        >
                            <i class="fas fa-user text-blue-500"></i> Profile
                        </a>
                        <form action="{{ route('admin.logout') }}" method="post" class="w-full">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full flex items-center gap-2 text-start px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200 appearance-none bg-transparent border-0 cursor-pointer"
                            >
                                <i class="fas fa-sign-out-alt text-red-500"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
{{-- <script>
function toggleTheme() {
    const html = document.documentElement;
    const themeIcon = document.getElementById('themeIcon');
    const isDark = html.classList.contains('dark');

    if (isDark) {
        html.classList.remove('dark');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
        localStorage.setItem('theme', 'dark');
    }
}

// Initialize theme from localStorage
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
    document.getElementById('themeIcon').classList.remove('fa-sun');
    document.getElementById('themeIcon').classList.add('fa-moon');
} else {
    document.documentElement.classList.remove('dark');
    document.getElementById('themeIcon').classList.remove('fa-moon');
    document.getElementById('themeIcon').classList.add('fa-sun');
}
</script> --}}