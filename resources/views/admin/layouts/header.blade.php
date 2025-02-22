<header class="shadow-xs bg-white border border-b border-b-gray-200">
    <div class="flex items-center px-4 py-3">
        <button id="openSidebar" class="md:hidden text-white hover:text-gray-200">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="flex items-center flex-1 px-4 justify-between">
            <!-- Search Bar -->
            <div class="relative flex-1 max-w-xs {{ Session::get('locale') === 'ar' ? 'ml-4' : 'mr-4' }}">
                <input type="text" placeholder="Search..." 
                       class="w-full px-4 py-2 rounded-lg border focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute {{ Session::get('locale') === 'ar' ? 'left-3' : 'right-3' }} top-4 text-gray-400"></i>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="relative">
                    <form action="{{ route('language.switch') }}" method="POST" class="inline-block">
                        @csrf
                        <div class="relative">
                            <select 
                                name="locale" 
                                onchange="this.form.submit()" 
                                class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 {{ Session::get('locale') === 'ar' ? 'pl-8' : 'pr-8' }} rounded-lg shadow-sm leading-tight focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            >
                                @foreach(Config::get('app.available_locales') as $locale)
                                    <option 
                                        value="{{ $locale }}" 
                                        {{ Session::get('locale') == $locale ? 'selected' : '' }}
                                    >
                                        {{ strtoupper($locale) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 {{ Session::get('locale') === 'ar' ? 'left-0' : 'right-0' }} flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
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
                        <span class="ms-2 text-gray-700 hidden md:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down ms-2 text-gray-400"></i>
                    </button>
                    
                    <div 
                        id="userMenu" 
                        class="hidden absolute {{ Session::get('locale') === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white rounded-lg shadow-lg py-3 z-50"
                    >
                        <a 
                            href="{{ route('admin.profile') }}" 
                            class="block flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200"
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