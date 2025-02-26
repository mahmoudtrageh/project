@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-5 flex-wrap">
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-800">{{__('Languages Management')}}</h1>
            <p class="text-gray-600">{{__('Manage your website languages')}}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('admin.languages.create') }}"
                class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-plus me-2"></i> Create New Language
            </a>
            <a class="inline-block px-4 py-2 mb-4 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
               href="{{route('translations.index')}}">
                <i class="fas fa-language me-2"></i> {{__('Translations')}}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center max-md:flex-col gap-5">
            <h2 class="text-lg font-semibold text-gray-800">{{__('Languages')}}</h2>
            <div class="flex items-center gap-6 flex-wrap max-md:justify-center">
                <!-- Search Box -->
                <div class="relative group">
                    <input type="text" placeholder="{{__('Search languages...')}}"
                        class="w-64 px-4 py-2.5 pl-11 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200" />
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select
                        class="appearance-none w-40 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer hover:bg-white">
                        <option value="">{{__('All Status')}}</option>
                        <option value="1">{{__('Active')}}</option>
                        <option value="0">{{__('Inactive')}}</option>
                    </select>
                    <i class="fas fa-chevron-down absolute ltr:right-4 rtl:left-4 top-3.5 text-gray-400 pointer-events-none transition-transform duration-200"></i>
                </div>

                <!-- RTL Filter -->
                <div class="relative">
                    <select
                        class="appearance-none w-40 px-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-200 cursor-pointer hover:bg-white">
                        <option value="">{{__('All Types')}}</option>
                        <option value="1">{{__('RTL')}}</option>
                        <option value="0">{{__('LTR')}}</option>
                    </select>
                    <i class="fas fa-chevron-down absolute ltr:right-4 rtl:left-4 top-3.5 text-gray-400 pointer-events-none transition-transform duration-200"></i>
                </div>
            </div>
        </div>

        <div class="responsive-table max-w-[90vw]">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllCheckbox"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('Name')}}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('Icon')}}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('Code')}}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('Status')}}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('RTL')}}
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{__('Actions')}}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($languages as $key => $language)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox"
                                    class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    value="{{ $language->id }}" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $key + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a class="text-indigo-600 hover:text-indigo-900 font-medium" href="#">
                                    {{$language->name}}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                     src="{{ asset('storage/'.$language->icon) }}" 
                                     alt="{{ $language->name }} icon">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{$language->code}}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $language->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $language->status ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $language->rtl ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $language->rtl ? __('RTL') : __('LTR') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.languages.edit', $language->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">
                                         <i class="fas fa-edit"></i> Edit
                                     </a>
                                    <button type="button" onclick="openDeleteModal('{{ route('admin.languages.destroy', $language) }}')"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                      
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black opacity-50"></div>

            <!-- Modal content -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-lg w-full max-w-md">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Are you sure you want to delete this project? This action cannot be undone.
                        </p>

                        <div class="flex justify-end gap-x-3">
                            <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>

                            <form id="deleteForm" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div>
            {{ $languages->links() }}
        </div>
    </div>
@endsection

@section('js')
<script>
    // Select all functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
    });

    const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(deleteUrl) {
            deleteModal.classList.remove('hidden');
            deleteForm.action = deleteUrl;
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        deleteModal.addEventListener('click', function (e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
</script>
@endsection