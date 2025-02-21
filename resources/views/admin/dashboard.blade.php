@extends('admin.layouts.app')

@section('content')

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


@endsection
