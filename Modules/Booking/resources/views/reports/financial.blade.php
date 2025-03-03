@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Financial Reports</h1>
    <p class="text-gray-600">Track revenue, costs, and profitability</p>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Report Filters</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('reports.financial') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Year Filter -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select
                        id="year"
                        name="year"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $filters['year'] == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Generate Report
                </button>
                
                <a href="{{ route('reports.export', ['type' => 'financial', 'year' => $filters['year']]) }}" 
                   class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-download me-2"></i> Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Annual Summary -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Annual Summary ({{ $filters['year'] }})</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="text-blue-800 text-sm font-medium mb-2">Total Client Revenue</h3>
                <div class="text-blue-900 text-2xl font-bold">${{ number_format($report['annual_totals']['client_revenue'], 2) }}</div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-4">
                <h3 class="text-red-800 text-sm font-medium mb-2">Total Hotel Costs</h3>
                <div class="text-red-900 text-2xl font-bold">${{ number_format($report['annual_totals']['hotel_costs'], 2) }}</div>
            </div>
            
            <div class="bg-yellow-50 rounded-lg p-4">
                <h3 class="text-yellow-800 text-sm font-medium mb-2">Total Marketer Profit</h3>
                <div class="text-yellow-900 text-2xl font-bold">${{ number_format($report['annual_totals']['marketer_profit'], 2) }}</div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <h3 class="text-green-800 text-sm font-medium mb-2">Total Admin Profit</h3>
                <div class="text-green-900 text-2xl font-bold">${{ number_format($report['annual_totals']['admin_profit'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Data -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Monthly Breakdown</h2>
    </div>
    
    <div class="overflow-x-auto max-lg:max-w-[90vw]">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Month
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client Revenue
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hotel Costs
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Marketer Profit
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admin Profit
                            </th>
                            <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profit Margin
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['monthly_summary'] as $month)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $month['month'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($month['client_revenue'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($month['hotel_costs'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($month['marketer_profit'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                {{ $month['admin_profit'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($month['admin_profit'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $profitMargin = $month['client_revenue'] > 0 ? 
                                        (($month['admin_profit'] / $month['client_revenue']) * 100) : 0;
                                @endphp
                                
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="
                                        {{ $profitMargin > 0 ? 'bg-green-600' : 'bg-red-600' }} 
                                        h-2.5 rounded-full" 
                                        style="width: {{ abs(min(max($profitMargin, -100), 100)) }}%">
                                    </div>
                                </div>
                                <div class="text-xs {{ $profitMargin > 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                                    {{ number_format($profitMargin, 1) }}%
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection