
<!-- Sidebar -->
<aside id="sidebar"
    class="sidebar-transition bg-white text-gray-800 sidebar-expanded fixed min-h-screen z-[999] shadow-xl border-r border-gray-100 transform transition-transform duration-300 ease-in-out {{ Session::get('locale') === 'ar' ? 'right-0 translate-x-full md:translate-x-0' : 'left-0 -translate-x-full md:translate-x-0' }}">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
     <span class="text-xl font-bold gradient-text sidebar-text">{{ __('admin.admin_panel') }}</span>
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
     <!-- Dashboard Link -->
     <a href="{{ route('dashboard') }}"
         class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : '' }}">
         <i class="fas fa-tachometer-alt w-5 h-5  sidebar-icon {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         <span class="ms-3 font-medium  sidebar-text">{{ __('admin.dashboard') }}</span>
     </a>

     <!-- Projects Dropdown -->
     <div class="dropdown relative mt-2">
         <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-blue-100 text-blue-600' : '' }}">
             <div class="flex items-center">
                 <i class="fa-solid fa-diagram-project w-5 h-5  sidebar-icon {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                 <span class="ms-3 font-medium  sidebar-text">{{ __('admin.projects') }}</span>
             </div>
             <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         </div>
         <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
             <a href="{{ route('admin.projects.index') }}"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.projects.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Projects</a>
             <a href="#"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700">{{ __('admin.add_new_project') }}</a>
         </div>
     </div>

     <!-- Blogs Dropdown -->
     <div class="dropdown relative mt-2">
         <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-600' : '' }}">
             <div class="flex items-center">
                 <i class="fa-solid fa-pen w-5 h-5  sidebar-icon {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                 <span class="ms-3 font-medium  sidebar-text">{{ __('admin.blogs') }}</span>
             </div>
             <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         </div>
         <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
             <a href="{{ route('admin.blogs.index') }}"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Blogs</a>
             <a href="{{ route('admin.blogs.create') }}"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New Blog</a>
         </div>
     </div>

     <!-- Pages Dropdown -->
     <div class="dropdown relative mt-2">
         <div class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-blue-100 text-blue-600' : '' }}">
             <div class="flex items-center">
                 <i class="fa-solid fa-file-lines w-5 h-5  sidebar-icon {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                 <span class="ms-3 font-medium sidebar-text">{{ __('admin.pages') }}</span>
             </div>
             <i class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         </div>
         <div class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
             <a href="{{ route('admin.pages.index') }}"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.index') ? 'bg-blue-100 text-blue-600' : '' }}">All Pages</a>
             <a href="{{ route('admin.pages.create') }}"
                 class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add New Page</a>
         </div>
     </div>

     <!-- Contacts Link -->
     <a href="{{ route('admin.contact.index') }}"
         class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.contact.index') ? 'bg-blue-100 text-blue-600' : '' }}">
         <i class="fa-regular fa-envelope w-5 h-5 sidebar-icon {{ request()->routeIs('admin.contact.index') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         <span class="ms-3 font-medium sidebar-text">{{ __('admin.contacts') }}</span>
     </a>

     <!-- Settings Link -->
     <a href="{{ route('admin.settings') }}"
         class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-600' : '' }}">
         <i class="fa-solid fa-sliders w-5 h-5 sidebar-icon {{ request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-400' }}"></i>
         <span class="ms-3 font-medium sidebar-text">{{ __('admin.settings') }}</span>
     </a>
 </nav>
</aside>