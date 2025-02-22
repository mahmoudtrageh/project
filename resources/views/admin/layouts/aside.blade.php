<aside id="sidebar" class="sidebar-transition bg-gradient-to-br from-white to-gray-50 text-gray-800 sidebar-expanded fixed md:relative min-h-screen z-50 shadow-xl border-r border-gray-100">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <span class="text-xl font-bold sidebar-text bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Admin Panel</span>
        <button id="toggleSidebar" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200 hidden md:block">
            <i id="toggleIcon" class="fas fa-chevron-left text-sm"></i>
        </button>
        <button id="closeSidebar" class="md:hidden text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    
    <nav class="mt-6 px-3">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : '' }}">
            <i class="fas fa-home w-5 h-5 sidebar-icon {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="sidebar-text ms-3 font-medium">Dashboard</span>
        </a>
        
        <!-- Projects Dropdown -->
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-diagram-project w-5 h-5 sidebar-icon {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="sidebar-text ms-3 font-medium">Projects</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admin.projects.index') }}" class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.projects.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Projects</a>
                <a href="#" class="block px-6 py-2 hover:bg-blue-50 text-gray-700">Add New Project</a>
            </div>
        </div>

        <!-- Blogs Dropdown -->
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-pen w-5 h-5 sidebar-icon {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="sidebar-text ms-3 font-medium">Blogs</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admin.blogs.index') }}" class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Blogs</a>
                <a href="{{ route('admin.blogs.create') }}" class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New Blog</a>
            </div>
        </div>

        <!-- Pages Dropdown -->
        <div class="dropdown relative mt-2">
            <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-newspaper w-5 h-5 sidebar-icon {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="sidebar-text ms-3 font-medium">Pages</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admin.pages.index') }}" class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Pages</a>
                <a href="{{ route('admin.pages.create') }}" class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New Page</a>
            </div>
        </div>

        <!-- Contacts Link -->
        <a href="{{ route('admin.contact.index') }}" class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.contact.index') ? 'bg-blue-100 text-blue-600' : '' }}">
            <i class="fa-regular fa-envelope w-5 h-5 sidebar-icon {{ request()->routeIs('admin.contact.index') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="sidebar-text ms-3 font-medium">Contacts</span>
        </a>

        <!-- Settings Link -->
        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-600' : '' }}">
            <i class="fas fa-cog w-5 h-5 sidebar-icon {{ request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="sidebar-text ms-3 font-medium">Settings</span>
        </a>
    </nav>
</aside>