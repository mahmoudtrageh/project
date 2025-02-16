<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
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
            left: 5rem;
            top: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 12rem;
        }

        /* Table Responsiveness */
        .responsive-table {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        .responsive-table table {
            min-width: 600px;
            width: 100%;
        }

        @media (max-width: 640px) {
            .responsive-table {
                overflow-x: scroll;
            }

            .responsive-table table {
                min-width: 100%;
            }

            .responsive-table th,
            .responsive-table td {
                white-space: nowrap;
            }
        }

        /* Prevent horizontal scrollbar on the body */
        body {
            overflow-x: hidden;
        }

        /* Gradient Header */
        .gradient-header {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
        }

        /* Hover Effects */
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }

        /* Card Animations */
        .card-animation {
            transition: all 0.3s ease;
        }

        .card-animation:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Alternating Row Colors */
        .table-row:nth-child(odd) {
            background-color: #f9fafb;
        }

        .table-row:nth-child(even) {
            background-color: #ffffff;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
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
                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
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
                        <a href="#" class="block px-4 py-2 hover:bg-gray-700">All</a>
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
                        <a href="#" class="block px-4 py-2 hover:bg-gray-700">All</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Add New Blog</a>
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
                        <a href="#" class="block px-4 py-2 hover:bg-gray-700">All</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Add New Page</a>
                    </div>
                </div>
              
                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <i class="fas fa-cog w-6"></i>
                    <span class="sidebar-text ml-2">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
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
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
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
                                    <img src="https://placehold.co/40x40" alt="User" class="w-8 h-8 rounded-full">
                                    <span class="ml-2 text-white hidden md:block">John Doe</span>
                                    <i class="fas fa-chevron-down ml-2 text-gray-200"></i>
                                </button>
                                
                                <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i> Settings
                                    </a>
                                    <form action="{{ route('admin.logout') }}" method="post" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 appearance-none bg-transparent border-0 cursor-pointer">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="container mx-auto">
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
                        <p class="text-gray-600">Welcome back, John Doe</p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Stat Card 1 -->
                        <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                    <i class="fas fa-users text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                                    <p class="text-2xl font-semibold">1,234</p>
                                </div>
                            </div>
                            <div class="mt-4 text-green-600 text-sm">
                                <i class="fas fa-arrow-up"></i>
                                <span>12.5% increase</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                    <i class="fas fa-users text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                                    <p class="text-2xl font-semibold">1,234</p>
                                </div>
                            </div>
                            <div class="mt-4 text-green-600 text-sm">
                                <i class="fas fa-arrow-up"></i>
                                <span>12.5% increase</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                    <i class="fas fa-users text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                                    <p class="text-2xl font-semibold">1,234</p>
                                </div>
                            </div>
                            <div class="mt-4 text-green-600 text-sm">
                                <i class="fas fa-arrow-up"></i>
                                <span>12.5% increase</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                    <i class="fas fa-users text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-gray-500 text-sm">Total Users</h3>
                                    <p class="text-2xl font-semibold">1,234</p>
                                </div>
                            </div>
                            <div class="mt-4 text-green-600 text-sm">
                                <i class="fas fa-arrow-up"></i>
                                <span>12.5% increase</span>
                            </div>
                        </div>

                        <!-- Add more stat cards... -->
                    </div>

                   <!-- Table -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                            <div class="flex items-center space-x-4">
                                <!-- Search Box -->
                                <div class="relative">
                                    <input
                                        type="text"
                                        placeholder="Search orders..."
                                        class="w-48 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Delete All Button -->
                                <button
                                    id="deleteAllButton"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    onclick="deleteSelectedRows()"
                                >
                                    Delete All
                                </button>

                                <!-- Export Dropdown -->
                                <div class="relative">
                                    <button
                                        id="exportDropdownButton"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        Export <i class="fas fa-chevron-down ml-2"></i>
                                    </button>
                                    <div
                                        id="exportDropdown"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-10"
                                    >
                                        <a
                                            href="#"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                            onclick="exportTable('csv')"
                                        >
                                            <i class="fas fa-file-csv mr-2"></i> Export as CSV
                                        </a>
                                        <a
                                            href="#"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                            onclick="exportTable('excel')"
                                        >
                                            <i class="fas fa-file-excel mr-2"></i> Export as Excel
                                        </a>
                                        <a
                                            href="#"
                                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                            onclick="exportTable('pdf')"
                                        >
                                            <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="responsive-table">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input
                                                type="checkbox"
                                                id="selectAllCheckbox"
                                                class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                                onclick="toggleSelectAll()"
                                            />
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Sample row -->
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="checkbox"
                                                class="row-checkbox form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                            />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">#12345</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-8 w-8 rounded-full" src="https://placehold.co/32" alt="">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">John Smith</div>
                                                    <div class="text-sm text-gray-500">john@example.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            $459.00
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                        </td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    Showing 1 to 10 of 97 results
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">Previous</button>
                                    <button class="px-3 py-1 border rounded bg-blue-600 text-white">1</button>
                                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">2</button>
                                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">3</button>
                                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>    
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const toggleIcon = document.getElementById('toggleIcon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const dropdownArrows = document.querySelectorAll('.dropdown-arrow');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        let isExpanded = true;
    
        function toggleSidebar() {
            isExpanded = !isExpanded;
            
            if (isExpanded) {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                sidebarTexts.forEach(text => text.style.display = 'inline');
                dropdownArrows.forEach(arrow => arrow.style.display = 'inline');
            } else {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                sidebarTexts.forEach(text => text.style.display = 'none');
                dropdownArrows.forEach(arrow => arrow.style.display = 'none');
            }
        }
    
        toggleBtn.addEventListener('click', toggleSidebar);
    
        // Mobile sidebar controls
        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        });
    
        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
        });
    
        // User Menu Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');
    
        userMenuBtn.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });
    
        // Notification Menu Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationMenu = document.getElementById('notificationMenu');
    
        notificationBtn.addEventListener('click', () => {
            notificationMenu.classList.toggle('hidden');
        });
    
        // Close menus when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
            if (!notificationBtn.contains(e.target) && !notificationMenu.contains(e.target)) {
                notificationMenu.classList.add('hidden');
            }
        });
    
        // Dropdown functionality
        const dropdowns = document.querySelectorAll('.dropdown');
    
        dropdowns.forEach(dropdown => {
            const dropdownContent = dropdown.querySelector('.dropdown-content');
            dropdown.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent closing when clicking inside the dropdown
                if (!isExpanded) {
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.add('dropdown-outside');
                } else {
                    dropdownContent.classList.toggle('show');
                    dropdownContent.classList.remove('dropdown-outside');
                }
            });
        });
    
        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            dropdowns.forEach(dropdown => {
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                dropdownContent.classList.remove('show');
            });
        });
    
        // Handle responsiveness
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        
        function handleMobileChange(e) {
            if (e.matches) {
                // Mobile view
                sidebar.classList.add('-translate-x-full');
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

         // Toggle Select All Checkboxes
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');

            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Delete Selected Rows
        function deleteSelectedRows() {
            const rowCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            if (rowCheckboxes.length === 0) {
                alert('No rows selected!');
                return;
            }

            if (confirm('Are you sure you want to delete the selected rows?')) {
                rowCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    row.remove();
                });
            }
        }

        // Toggle Export Dropdown
        const exportDropdownButton = document.getElementById('exportDropdownButton');
        const exportDropdown = document.getElementById('exportDropdown');

        exportDropdownButton.addEventListener('click', () => {
            exportDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!exportDropdownButton.contains(e.target)) {
                exportDropdown.classList.add('hidden');
            }
        });

        // Export Table Functionality
        function exportTable(format) {
            alert(`Exporting table as ${format.toUpperCase()}...`);
        }
    </script>

    </body>

    </html>
