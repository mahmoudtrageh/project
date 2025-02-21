<aside id="sidebar" class="sidebar-transition bg-gray-800 text-white sidebar-expanded fixed md:relative min-h-screen z-50 shadow-lg">
    <div class="flex items-center justify-between p-4">
        <span class="text-xl font-semibold sidebar-text">Admin Panel</span>
        <button id="toggleSidebar" class="text-white hover:bg-gray-700 p-2 rounded hidden md:block">
            <i id="toggleIcon" class="fas fa-chevron-left"></i>
        </button>
        <button id="closeSidebar" class="md:hidden text-white hover:bg-gray-700 p-2 rounded">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="mt-4">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : '' }}">
            <i class="fas fa-home w-6"></i>
            <span class="sidebar-text ml-2">Dashboard</span>
        </a>
        
        <!-- Projects Dropdown -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-gray-900 text-white' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-diagram-project"></i>
                    <span class="sidebar-text ml-2">Projects</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 mt-1 hidden transition-all duration-300 ease-in-out transform origin-top">
                <a href="{{ route('admin.projects.index') }}" class="block px-8 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.projects.index') ? 'bg-gray-900 text-white' : '' }}">All Projects</a>
                <a href="#" class="block px-8 py-2 hover:bg-gray-700">Add New Project</a>
            </div>
        </div>

        <!-- Blogs Dropdown -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ request()->routeIs('admin.blogs.*') ? 'bg-gray-900 text-white' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-pen-nib"></i>
                    <span class="sidebar-text ml-2">Blogs</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 mt-1 hidden transition-all duration-300 ease-in-out transform origin-top">
                <a href="{{ route('admin.blogs.index') }}" class="block px-8 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.blogs.index') ? 'bg-gray-900 text-white' : '' }}">All Blogs</a>
                <a href="{{ route('admin.blogs.create') }}" class="block px-8 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.blogs.create') ? 'bg-gray-900 text-white' : '' }}">Add New Blog</a>
            </div>
        </div>

        <!-- Pages Dropdown -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-900 text-white' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-newspaper"></i>
                    <span class="sidebar-text ml-2">Pages</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 mt-1 hidden transition-all duration-300 ease-in-out transform origin-top">
                <a href="{{ route('admin.pages.index') }}" class="block px-8 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.pages.index') ? 'bg-gray-900 text-white' : '' }}">All Pages</a>
                <a href="{{ route('admin.pages.create') }}" class="block px-8 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.pages.create') ? 'bg-gray-900 text-white' : '' }}">Add New Page</a>
            </div>
        </div>

        <!-- Contacts Link -->
        <a href="{{ route('admin.contact.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.contact.index') ? 'bg-gray-900 text-white' : '' }}">
            <i class="fas fa-envelope w-6"></i>
            <span class="sidebar-text ml-2">Contacts</span>
        </a>

        <!-- Settings Link -->
        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.settings') ? 'bg-gray-900 text-white' : '' }}">
            <i class="fas fa-cog w-6"></i>
            <span class="sidebar-text ml-2">Settings</span>
        </a>
    </nav>
</aside>