<!DOCTYPE html>
<html lang="{{ Session::get('locale', 'en') }}" dir="{{ Session::get('locale') === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    @yield('css')

    <style>
        /* Custom Styles */
        body {
            direction:
                {{ Session::get('locale') === 'ar' ? 'rtl' : 'ltr' }}
            ;
        }

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

        /* RTL specific styles */
        [dir="rtl"] .fa-chevron-left {
            transform: rotate(180deg);
        }

        [dir="rtl"] .fa-chevron-right {
            transform: rotate(180deg);
        }

        [dir="rtl"] .ml-2 {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        [dir="rtl"] .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        [dir="rtl"] .mr-4 {
            margin-right: 0;
            margin-left: 1rem;
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

        .sidebar-transition {
            transition: width 0.3s ease;
        }

        .sidebar-collapsed {
            width: 64px;
        }

        .sidebar-collapsed .sidebar-text {
            display: none;
        }

        .sidebar-collapsed .dropdown-content {
            display: none !important;
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

        .bg-gray-900 {
            background-color: #1a202c;
        }

        .bg-gray-900:hover {
            background-color: #2d3748;
        }

        /* Indentation for submenu items */
        .dropdown-content a {
            padding-left: 2rem;
            /* Adjust as needed */
        }

        /* Smooth transition for dropdown */
        .dropdown-content {
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .dropdown-content.show {
            opacity: 1;
            transform: scaleY(1);
        }
    </style>

</head>

<body class="bg-gray-100 {{ Session::get('locale') === 'ar' ? 'font-cairo' : 'font-poppins' }} "
    dir="{{ Session::get('locale') === 'ar' ? 'rtl' : 'ltr' }}">

    <div class="min-h-screen flex md:ps-[250px]" id="mainLayout">

        @include('admin.layouts.aside')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Top Navigation -->
            @include('admin.layouts.header')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <div class="container mx-auto">

                    @yield('content')

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

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
                // mainLayout.classList.add("md:ps-[250px]");
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                sidebarTexts.forEach(text => text.style.display = 'inline');
                dropdownArrows.forEach(arrow => arrow.style.display = 'inline');
            } else {
                // mainLayout.classList.add("md:ps-[50px]");
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                sidebarTexts.forEach(text => text.style.display = 'none');
                sidebarIcons.forEach(icon => icon.classList.add('-ms-2'));
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
            sidebar.classList.add('ltr:-translate-x-full');
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

        document.querySelectorAll('.dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', function () {
                const dropdownContent = this.querySelector('.dropdown-content');
                const dropdownArrow = this.querySelector('.dropdown-arrow');
                dropdownArrow.classList.toggle('rotate-180');
            });
        });
    </script>

    @yield('js')

    <script>

        CKEDITOR.replace('editor', {
            height: 300,
            versionCheck: false, // Disable version check to hide the warning
            filebrowserUploadUrl: "{{route('upload.image', ['_token' => csrf_token()])}}",
            filebrowserUploadMethod: 'form',
        });
    </script>
</body>

</html>