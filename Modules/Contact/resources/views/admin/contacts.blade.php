@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
            <p class="text-lg text-gray-600">Manage Your Contacts</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Contacts</h2>
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Search Box -->
                        <div class="relative">
                            <input
                                type="text"
                                placeholder="Search contacts..."
                                class="w-64 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200"
                            />
                            <i class="fas fa-search absolute ltr:right-3 rtl:left-3 top-4 text-gray-400"></i>
                        </div>

                        <!-- Delete All Button -->
                        <button
                            id="deleteAllButton"
                            class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 flex items-center gap-2"
                            onclick="deleteSelectedRows()"
                        >
                            <i class="fas fa-trash-alt"></i>
                            Delete Selected
                        </button>

                        <!-- Export Dropdown -->
                        <div class="relative">
                            <button
                                id="exportDropdownButton"
                                class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 flex items-center gap-2"
                            >
                                <i class="fas fa-download"></i>
                                Export <i class="fas fa-chevron-down ms-2"></i>
                            </button>
                            <div
                                id="exportDropdown"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100"
                            >
                                <a
                                    href="#"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                                    onclick="exportTable('csv')"
                                >
                                    <i class="fas fa-file-csv mr-2 text-gray-400"></i> Export as CSV
                                </a>
                                <a
                                    href="#"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                                    onclick="exportTable('excel')"
                                >
                                    <i class="fas fa-file-excel mr-2 text-gray-400"></i> Export as Excel
                                </a>
                                <a
                                    href="#"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                                    onclick="exportTable('pdf')"
                                >
                                    <i class="fas fa-file-pdf mr-2 text-gray-400"></i> Export as PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-y border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-colors duration-200">
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">Message</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contacts as $contact)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="checkbox"
                                    class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-colors duration-200"
                                    value="{{ $contact->id }}"
                                />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $contact->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $contact->phone ?? 'NA' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $contact->subject }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 truncate max-w-xs">{{ $contact->message }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Toggle export dropdown
    const exportDropdownButton = document.getElementById('exportDropdownButton');
    const exportDropdown = document.getElementById('exportDropdown');

    if (exportDropdownButton && exportDropdown) {
        exportDropdownButton.addEventListener('click', () => {
            exportDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!exportDropdownButton.contains(e.target) && !exportDropdown.contains(e.target)) {
                exportDropdown.classList.add('hidden');
            }
        });
    }

    // Handle select all checkbox
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }
</script>
@endsection