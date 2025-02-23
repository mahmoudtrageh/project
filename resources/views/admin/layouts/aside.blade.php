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
            <i
                class="fas fa-tachometer-alt w-5 h-5  sidebar-icon {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ms-3 font-medium  sidebar-text">{{ __('admin.dashboard') }}</span>
        </a>

        <!-- Projects Dropdown -->
        <div class="dropdown relative mt-2">
            <div
                class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.projects.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i
                        class="fa-solid fa-diagram-project w-5 h-5 sidebar-icon {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('admin.projects') }}</span>
                </div>
                <i
                    class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.projects.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div
                class="dropdown-content bg-white rounded-xl shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform">
                <a href="{{ route('admin.projects.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.projects.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i class="fas fa-list-ul w-5 text-gray-400 inline-block"></i>
                    <span>All Projects</span>
                </a>
                <a href="#" class="block px-6 py-2 hover:bg-blue-50 text-gray-700">
                    <i class="fas fa-plus w-5 text-gray-400 inline-block"></i>
                    <span>{{ __('admin.add_new_project') }}</span>
                </a>
            </div>
        </div>

        <!-- Blogs Dropdown -->
        <div class="dropdown relative mt-2">
            <div
                class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i
                        class="fa-solid fa-pen w-5 h-5  sidebar-icon {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium  sidebar-text">{{ __('admin.blogs') }}</span>
                </div>
                <i
                    class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div
                class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admin.blogs.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.index') ? 'bg-blue-100 text-blue-600' : '' }}">All
                    Blogs</a>
                <a href="{{ route('admin.blogs.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.blogs.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add
                    New Blog</a>
            </div>
        </div>

        <!-- Pages Dropdown -->
        <div class="dropdown relative mt-2">
            <div
                class="flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-xl cursor-pointer transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                <div class="flex items-center">
                    <i
                        class="fa-solid fa-file-lines w-5 h-5  sidebar-icon {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    <span class="ms-3 font-medium sidebar-text">{{ __('admin.pages') }}</span>
                </div>
                <i
                    class="fas fa-chevron-down text-xs dropdown-arrow transition-transform duration-200 {{ request()->routeIs('admin.pages.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            </div>
            <div
                class="dropdown-content bg-white rounded-lg shadow-lg py-2 mt-2 hidden transition-all duration-200 ease-in-out transform origin-top">
                <a href="{{ route('admin.pages.index') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.index') ? 'bg-blue-100 text-blue-600' : '' }}">All
                    Pages</a>
                <a href="{{ route('admin.pages.create') }}"
                    class="block px-6 py-2 hover:bg-blue-50 text-gray-700 {{ request()->routeIs('admin.pages.create') ? 'bg-blue-100 text-blue-600' : '' }}">Add
                    New Page</a>
            </div>
        </div>

        <!-- Contacts Link -->
        <a href="{{ route('admin.contact.index') }}"
            class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.contact.index') ? 'bg-blue-100 text-blue-600' : '' }}">
            <i
                class="fa-regular fa-envelope w-5 h-5 sidebar-icon {{ request()->routeIs('admin.contact.index') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ms-3 font-medium sidebar-text">{{ __('admin.contacts') }}</span>
        </a>

        <!-- Settings Link -->
        <a href="{{ route('admin.settings') }}"
            class="flex items-center px-4 py-3 mt-2 text-gray-700 hover:bg-blue-50 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-600' : '' }}">
            <i
                class="fa-solid fa-sliders w-5 h-5 sidebar-icon {{ request()->routeIs('admin.settings') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span class="ms-3 font-medium sidebar-text">{{ __('admin.settings') }}</span>
        </a>
    </nav>

    <style>
        /* Sidebar Styles */
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .sidebar-text {
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }

        .sidebar-collapsed {
            width: 3.5rem !important;
        }

        .sidebar-expanded {
            width: 16rem !important;
        }

        .dropdown-content {
            display: none;
            transition: all 0.3s ease;
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-outside {
            position: absolute;
            {{ Session::get('locale') === 'ar' ? 'right' : 'left' }}
            : 5rem;
            top: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 12rem;
        }

        [dir="rtl"] .fa-chevron-left {
            transform: rotate(180deg);
        }

        [dir="rtl"] .fa-chevron-right {
            transform: rotate(180deg);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .dropdown-arrow {
            transition: transform 0.2s ease;
        }

        .dropdown-content {
            transform: scaleY(0);
            transform-origin: top;
        }

        .dropdown-content.show {
            transform: scaleY(1);
        }
    </style>

    <script>
        // Sidebar Toggle
        const mainLayout = document.getElementById('mainLayout');
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const sidebarIcons = document.querySelectorAll('.sidebar-icon');
        const dropdownArrows = document.querySelectorAll('.dropdown-arrow');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        let isExpanded = true;

        function toggleSidebar() {
            isExpanded = !isExpanded;

            if (isExpanded) {
                mainLayout.classList.add("md:ps-[250px]");
                mainLayout.classList.remove("md:ps-[50px]");
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                sidebarTexts.forEach(text => text.style.display = 'inline');
                dropdownArrows.forEach(arrow => arrow.style.display = 'inline');
            } else {
                mainLayout.classList.add("md:ps-[50px]");
                mainLayout.classList.remove("md:ps-[250px]");
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                sidebarTexts.forEach(text => text.style.display = 'none');
                sidebarIcons.forEach(icon => icon.classList.add('-ms-2'));
                dropdownArrows.forEach(arrow => arrow.style.display = 'none');
            }
        }

        toggleBtn?.addEventListener('click', toggleSidebar);

        // Mobile sidebar controls
        openSidebarBtn?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        });

        closeSidebarBtn?.addEventListener('click', () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('ltr:-translate-x-full');
        });

        // Dropdown functionality
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const dropdownContent = dropdown.querySelector('.dropdown-content');

            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCollapsed = document.getElementById('sidebar').classList.contains('sidebar-collapsed');

                if (isCollapsed) {
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.toggle('dropdown-outside');

                    document.querySelectorAll('.dropdown-content').forEach(content => {
                        if (content !== dropdownContent) {
                            content.classList.remove('show', 'dropdown-outside');
                        }
                    });
                } else {
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.remove('dropdown-outside');
                }

                const arrow = this.querySelector('.dropdown-arrow');
                arrow.classList.toggle('rotate-180');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show', 'dropdown-outside');
            });
            document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
                arrow.classList.remove('rotate-180');
            });
        });

        const mediaQuery = window.matchMedia('(max-width: 768px)');

        function handleMobileChange(e) {
            const isRTL = document.dir === 'rtl';
            if (e.matches) {
                // Mobile view
                if (!isRTL) {
                    sidebar.classList.add('-translate-x-full');
                }
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                sidebarTexts.forEach(text => text.style.display = 'inline');
                dropdownArrows.forEach(arrow => arrow.style.display = 'inline');
                isExpanded = true;
            } else {
                // Desktop view
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                if (!isExpanded) {
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('sidebar-expanded');
                    sidebarTexts.forEach(text => text.style.display = 'none');
                    dropdownArrows.forEach(arrow => arrow.style.display = 'none');
                }
            }
        }

        mediaQuery.addListener(handleMobileChange);
        handleMobileChange(mediaQuery);

        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const dropdownContent = dropdown.querySelector('.dropdown-content');

            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCollapsed = document.getElementById('sidebar').classList.contains('sidebar-collapsed');

                if (isCollapsed) {
                    // Position the dropdown next to the sidebar
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.toggle('dropdown-outside');

                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-content').forEach(content => {
                        if (content !== dropdownContent) {
                            content.classList.remove('show', 'dropdown-outside');
                        }
                    });
                } else {
                    // Normal dropdown behavior when sidebar is expanded
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.remove('dropdown-outside');
                }

                // Toggle arrow rotation
                const arrow = this.querySelector('.dropdown-arrow');
                arrow.classList.toggle('rotate-180');
            });
        });

        // // Close dropdowns when clicking outside
        // document.addEventListener('click', () => {
        //     document.querySelectorAll('.dropdown-content').forEach(content => {
        //         content.classList.remove('show', 'dropdown-outside');
        //     });
        //     document.querySelectorAll('.dropdown-arrow').forEach(arrow => {
        //         arrow.classList.remove('rotate-180');
        //     });
        // });
    </script>

</aside>