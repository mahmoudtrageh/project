 <!-- Sidebar -->
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
        <a href="{{route('dashboard')}}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
            <i class="fas fa-home w-6"></i>
            <span class="sidebar-text ml-2">Dashboard</span>
        </a>
        
        <!-- Dropdown Example -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fa-solid fa-diagram-project"></i>
                    <span class="sidebar-text ml-2">Projects</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 hidden">
                <a href="{{route('admin.projects.index')}}" class="block px-4 py-2 hover:bg-gray-700">All</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-700">Add New Project</a>
            </div>
        </div>

        <!-- Add more dropdowns as needed -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fa-solid fa-pen-nib"></i>
                    <span class="sidebar-text ml-2">Blogs</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 hidden">
                <a href="{{route('admin.blogs.index')}}" class="block px-4 py-2 hover:bg-gray-700">All</a>
                <a href="{{route('admin.blogs.create')}}" class="block px-4 py-2 hover:bg-gray-700">Add New Blog</a>
            </div>
        </div>

        <!-- Add more dropdowns as needed -->
        <div class="dropdown relative">
            <div class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                <div class="flex items-center">
                    <i class="fa-solid fa-newspaper"></i>
                    <span class="sidebar-text ml-2">Pages</span>
                </div>
                <i class="fas fa-chevron-down text-xs dropdown-arrow"></i>
            </div>
            <div class="dropdown-content bg-gray-800 text-gray-300 rounded-lg py-2 hidden">
                <a href="{{route('admin.pages.index')}}" class="block px-4 py-2 hover:bg-gray-700">All</a>
                <a href="{{route('admin.pages.create')}}" class="block px-4 py-2 hover:bg-gray-700">Add New Page</a>
            </div>
        </div>
      
        <a href="{{route('admin.settings')}}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
            <i class="fas fa-cog w-6"></i>
            <span class="sidebar-text ml-2">Settings</span>
        </a>
    </nav>
</aside>