@extends('admin.layouts.app')

@section('content')

<div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-blue-500 bg-opacity-10">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Blogs</h3>
                        <p class="text-2xl font-semibold">{{getTotalRecords('blog')}}</p>
                    </div>
                </div>
                {{-- <div class="mt-4 text-green-600 text-sm">
                    <i class="fas fa-arrow-up"></i>
                    <span>12.5% increase</span>
                </div> --}}
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-green-500 bg-opacity-10">
                        <i class="fas fa-shopping-cart text-green-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Pages</h3>
                        <p class="text-2xl font-semibold">{{getTotalRecords('page')}}</p>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-purple-500 bg-opacity-10">
                        <i class="fas fa-dollar-sign text-purple-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Contacts</h3>
                        <p class="text-2xl font-semibold">{{getTotalRecords('contact')}}</p>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white rounded-lg shadow p-6 card-animation hover-scale">
                <div class="flex items-center">
                    <div class="p-3 size-11 flex items-center justify-center rounded-full bg-yellow-500 bg-opacity-10">
                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ms-4">
                        <h3 class="text-gray-500 text-sm">Total Categories</h3>
                        <p class="text-2xl font-semibold">{{getTotalRecords('category')}}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Analytics</h3>
                <div class="h-80" id="salesChart"></div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Overview</h3>
                <div class="h-80" id="revenueChart"></div>
            </div>
        </div> --}}

        <!-- Table -->
        <div class="bg-white rounded-lg shadow max-sm:max-w-[89vw]">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h2 class="text-lg font-semibold text-gray-800">Recent Blogs</h2>
            </div>

            <div class="max-w-[97vw]">
                <div class="responsive-table min-w-full">
                    <div class="overflow-x-auto">
                        <table class="w-full max-w-full whitespace-nowrap">
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
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Blog Info</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Outsource URL</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Featured</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Published At</th>
                                <th class="px-6 py-4 text-start text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($blogs as $blog)
                            <tr class="table-row hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <label class="inline-flex items-center hover:bg-gray-100 p-1 rounded-lg transition-colors duration-200">
                                        <input
                                            type="checkbox"
                                            class="row-checkbox form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 transition duration-150 ease-in-out focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            value="{{ $blog->id }}"
                                        />
                                    </label>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($blog->image) }}" alt="{{ $blog->getTranslation('title', app()->getLocale()) }}">
                                        <div class="ms-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $blog->getTranslation('title', app()->getLocale()) }}
                                                <span class="text-xs text-gray-500 ml-2">({{ app()->getLocale() }})</span>
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $blog->slug }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($blog->getTranslation('content', app()->getLocale()), 50) }}
                                            </div>
                                            
                                            <!-- Optional: Show available translations as badges -->
                                            <div class="mt-1 flex space-x-1">
                                                @foreach(array_keys($blog->getTranslations('title')) as $locale)
                                                    @if($locale != app()->getLocale())
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ strtoupper($locale) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $blog->category ? $blog->category->name : 'Uncategorized' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        @if($blog->outsource_url)
                                            <a href="{{ $blog->outsource_url }}" class="text-blue-600 hover:text-blue-800 hover:underline" target="_blank">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                {{ Str::limit($blog->outsource_url, 30) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">No external URL</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full 
                                        {{ $blog->status === 'published' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-yellow-50 text-yellow-700 border border-yellow-100' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $blog->status === 'published' ? 'bg-green-600' : 'bg-yellow-600' }}"></span>
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($blog->featured)
                                        <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                            Featured
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $blog->published_at ? $blog->published_at->format('M d, Y H:i') : 'Not Published' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                       
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-1 rounded-lg transition-colors duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium text-gray-900">{{ $blogs->firstItem() }}</span> to <span class="font-medium text-gray-900">{{ $blogs->lastItem() }}</span> of <span class="font-medium text-gray-900">{{ $blogs->total() }}</span> results
                    </div>
                    <div class="inline-flex items-center gap-2">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>


        @yield('js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Sales Chart
            // Function to update chart dimensions
            function updateChartDimensions() {
                if (salesChart && revenueChart) {
                    salesChart.updateOptions({
                        chart: {
                            width: '100%'
                        }
                    });
                    revenueChart.updateOptions({
                        chart: {
                            width: '100%'
                        }
                    });
                }
            }

            // Sales Chart Configuration
            var salesOptions = {
                series: [{
                    name: 'Sales',
                    data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    width: '100%',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
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
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    labels: {
                        style: {
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                responsive: [{
                    breakpoint: 1024,
                    options: {
                        chart: {
                            height: 280
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 240
                        }
                    }
                },
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 200
                        },
                        xaxis: {
                            labels: {
                                rotate: -90
                            }
                        }
                    }
                }]
            };

            var salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
            salesChart.render();

            // Revenue Chart Configuration
            var revenueOptions = {
                series: [44, 55, 41, 17, 15],
                chart: {
                    type: 'donut',
                    height: 320,
                    width: '100%',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                labels: ['Direct', 'Affiliate', 'Sponsored', 'E-mail', 'Other'],
                colors: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0
                    }
                },
                responsive: [{
                    breakpoint: 1024,
                    options: {
                        chart: {
                            height: 280
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 240
                        }
                    }
                },
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 200
                        },
                        legend: {
                            position: 'bottom',
                            fontSize: '12px',
                            itemMargin: {
                                horizontal: 6,
                                vertical: 0
                            }
                        }
                    }
                }]
            };

            var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // Handle window resize
            window.addEventListener('resize', updateChartDimensions);
            
            // Initial update
            updateChartDimensions();
        </script>

@endsection     

