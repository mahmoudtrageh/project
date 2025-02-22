@extends('admin.layouts.app')

@section('content')
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-lg shadow-sm p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <h3 class="text-gray-500 text-sm">Total Users</h3>
                        <p class="text-2xl font-semibold">1,234</p>
                    </div>
                </div>
                <div class="mt-4 text-green-600 text-sm">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% increase</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-lg shadow-sm p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <h3 class="text-gray-500 text-sm">Total Orders</h3>
                        <p class="text-2xl font-semibold">856</p>
                    </div>
                </div>
                <div class="mt-4 text-green-600 text-sm">
                    <i class="fas fa-arrow-up"></i>
                    <span>8.2% increase</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-lg shadow-sm p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                        <i class="fas fa-dollar-sign text-purple-500 text-xl"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <h3 class="text-gray-500 text-sm">Total Revenue</h3>
                        <p class="text-2xl font-semibold">$45,678</p>
                    </div>
                </div>
                <div class="mt-4 text-green-600 text-sm">
                    <i class="fas fa-arrow-up"></i>
                    <span>15.3% increase</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white rounded-lg shadow-sm p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <h3 class="text-gray-500 text-sm">Average Rating</h3>
                        <p class="text-2xl font-semibold">4.8</p>
                    </div>
                </div>
                <div class="mt-4 text-green-600 text-sm">
                    <i class="fas fa-arrow-up"></i>
                    <span>2.1% increase</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Analytics</h3>
                <div class="h-80" id="salesChart"></div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Overview</h3>
                <div class="h-80" id="revenueChart"></div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                <!-- Timeline Item 1 -->
                <div class="flex items-start">
                    <div class="p-2 rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-user-plus text-blue-500"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <p class="text-sm font-medium text-gray-900">New user registered</p>
                        <p class="text-sm text-gray-500">2 minutes ago</p>
                    </div>
                </div>
                <!-- Timeline Item 2 -->
                <div class="flex items-start">
                    <div class="p-2 rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-shopping-bag text-green-500"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <p class="text-sm font-medium text-gray-900">New order placed</p>
                        <p class="text-sm text-gray-500">15 minutes ago</p>
                    </div>
                </div>
                <!-- Timeline Item 3 -->
                <div class="flex items-start">
                    <div class="p-2 rounded-full bg-yellow-500 bg-opacity-10">
                        <i class="fas fa-star text-yellow-500"></i>
                    </div>
                    <div class="{{ Session::get('locale') === 'ar' ? 'me-4' : 'ms-4' }}">
                        <p class="text-sm font-medium text-gray-900">New review received</p>
                        <p class="text-sm text-gray-500">1 hour ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                <div class="flex items-center gap-x-4">
                    <!-- Search Box -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Search orders..."
                             class="w-64 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200"
                        />
                        <i class="fas fa-search absolute ltr:right-3 rtl:left-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Delete All Button -->
                    <button
                        id="deleteAllButton"
                        class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 focus:outline-hidden focus:ring-2 focus:ring-red-500"
                        onclick="deleteSelectedRows()"
                    >
                        Delete All
                    </button>

                    <!-- Export Dropdown -->
                    <div class="relative">
                        <button
                            id="exportDropdownButton"
                            class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                        >
                            Export <i class="fas fa-chevron-down ms-2"></i>
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

            <div class="responsive-table overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <label class="inline-flex items-center hover:bg-gray-50 p-1 rounded-lg transition-colors duration-200">
                                    <input
                                        type="checkbox"
                                        id="selectAllCheckbox"
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        onclick="toggleSelectAll()"
                                    />
                                </label>
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <!-- Sample row -->
                        <tr class="table-row hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <label class="inline-flex items-center hover:bg-gray-100 p-1 rounded-lg transition-colors duration-200">
                                    <input
                                        type="checkbox"
                                        class="row-checkbox form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    />
                                </label>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#12345</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100" src="https://placehold.co/32" alt="">
                                    <div class="ms-4">
                                        <div class="text-sm font-semibold text-gray-900">John Smith</div>
                                        <div class="text-sm text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full bg-green-50 text-green-700 border border-green-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                    Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">$459.00</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-1 rounded-lg transition-colors duration-200">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-red-600 hover:text-red-700 hover:bg-red-50 p-1 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium text-gray-900">1</span> to <span class="font-medium text-gray-900">10</span> of <span class="font-medium text-gray-900">97</span> results
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                            <i class="fas fa-chevron-left me-2 text-xs"></i>
                            Previous
                        </button>
                        <div class="inline-flex -space-x-px overflow-hidden rounded-lg border border-gray-300">
                            <button class="px-4 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">1</button>
                            <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">2</button>
                            <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">3</button>
                        </div>
                        <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                            Next
                            <i class="fas fa-chevron-right ms-2 text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @yield('js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Sales Chart
            var salesOptions = {
                series: [{
                    name: 'Sales',
                    data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3B82F6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
                }
            };

            var salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
            salesChart.render();

            // Revenue Chart
            var revenueOptions = {
                series: [44, 55, 41, 17, 15],
                chart: {
                    type: 'donut',
                    height: 320
                },
                labels: ['Direct', 'Affiliate', 'Sponsored', 'E-mail', 'Other'],
                colors: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();
        </script>

@endsection

        <!-- Initialize Charts -->
     
{{-- @endsection --}}
