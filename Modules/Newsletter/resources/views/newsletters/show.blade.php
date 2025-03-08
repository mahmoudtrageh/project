@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between gap-5 flex-wrap">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Newsletter Details</h1>
            <p class="text-gray-600">View newsletter information and analytics</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('newsletters.index') }}" class="inline-block px-4 py-2 mb-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
            
            <a href="{{ route('newsletters.preview', $newsletter) }}" target="_blank"
                class="inline-block px-4 py-2 mb-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                <i class="fas fa-eye me-2"></i> Preview
            </a>
            
            @if($newsletter->status === 'draft' || $newsletter->status === 'scheduled')
                <a href="{{ route('newsletters.edit', $newsletter) }}"
                    class="inline-block px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
            @endif
            
            @if($newsletter->status === 'draft')
                <form action="{{ route('newsletters.send', $newsletter) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 mb-4 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-paper-plane me-2"></i> Send Now
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Newsletter Info -->
        <div class="bg-white rounded-lg shadow col-span-2">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Newsletter Information</h2>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Title</div>
                    <div class="w-full md:w-3/4 text-gray-900">{{ $newsletter->title }}</div>
                </div>
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Status</div>
                    <div class="w-full md:w-3/4">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($newsletter->status === 'sent')
                                bg-green-100 text-green-800
                            @elseif($newsletter->status === 'draft')
                                bg-gray-100 text-gray-800
                            @elseif($newsletter->status === 'scheduled')
                                bg-blue-100 text-blue-800
                            @elseif($newsletter->status === 'sending')
                                bg-yellow-100 text-yellow-800
                            @elseif($newsletter->status === 'failed')
                                bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($newsletter->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Created At</div>
                    <div class="w-full md:w-3/4 text-gray-900">{{ $newsletter->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Scheduled For</div>
                    <div class="w-full md:w-3/4 text-gray-900">
                        {{ $newsletter->scheduled_at ? $newsletter->scheduled_at->format('F j, Y \a\t g:i A') : 'Not scheduled' }}
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Sent At</div>
                    <div class="w-full md:w-3/4 text-gray-900">
                        {{ $newsletter->sent_at ? $newsletter->sent_at->format('F j, Y \a\t g:i A') : 'Not sent yet' }}
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row mb-6">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Recipients</div>
                    <div class="w-full md:w-3/4 text-gray-900">
                        {{ $newsletter->recipients->count() }} subscribers
                    </div>
                </div>
                
                @if(!empty($newsletter->social_links))
                    <div class="flex flex-col md:flex-row mb-6">
                        <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Social Links</div>
                        <div class="w-full md:w-3/4">
                            <ul class="space-y-2">
                                @foreach($newsletter->social_links as $platform => $url)
                                    <li>
                                        <span class="font-medium">{{ ucfirst($platform) }}:</span> 
                                        <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline">{{ $url }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/4 font-medium text-gray-700 mb-2 md:mb-0">Custom CSS</div>
                    <div class="w-full md:w-3/4">
                        @if($newsletter->custom_css)
                            <div class="relative">
                                <button type="button" id="toggleCssBtn" class="absolute right-2 top-2 px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs">
                                    Show/Hide
                                </button>
                                <pre id="cssContent" class="bg-gray-50 p-4 rounded-md text-sm font-mono overflow-x-auto max-h-60 hidden">{{ $newsletter->custom_css }}</pre>
                                <div id="cssPlaceholder" class="py-2 text-gray-500 italic">Custom CSS is present. Click "Show/Hide" to view.</div>
                            </div>
                        @else
                            <span class="text-gray-500 italic">No custom CSS</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Analytics -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Analytics</h2>
            </div>
            
            <div class="p-6">
                @if($newsletter->analytics && $newsletter->status === 'sent')
                    <div class="space-y-6">
                        <!-- Delivery Stats -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Delivery</h3>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Total Recipients:</span>
                                <span class="font-medium">{{ $newsletter->analytics->total_recipients }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Successful Deliveries:</span>
                                <span class="font-medium text-green-600">{{ $newsletter->analytics->successful_deliveries }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Failed Deliveries:</span>
                                <span class="font-medium text-red-600">{{ $newsletter->analytics->failed_deliveries }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Delivery Rate:</span>
                                <span class="font-medium">{{ $newsletter->analytics->delivery_rate }}%</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-3 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $newsletter->analytics->delivery_rate }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Engagement Stats -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Engagement</h3>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Open Count:</span>
                                <span class="font-medium">{{ $newsletter->analytics->open_count }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Open Rate:</span>
                                <span class="font-medium">{{ $newsletter->analytics->open_rate }}%</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm">Click Count:</span>
                                <span class="font-medium">{{ $newsletter->analytics->click_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Click Rate:</span>
                                <span class="font-medium">{{ $newsletter->analytics->click_rate }}%</span>
                            </div>
                            
                            <!-- Progress Bars -->
                            <div class="mt-3">
                                <div class="text-xs text-gray-500 mb-1">Open Rate</div>
                                <div class="bg-gray-200 rounded-full h-2.5 overflow-hidden mb-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $newsletter->analytics->open_rate }}%"></div>
                                </div>
                                
                                <div class="text-xs text-gray-500 mb-1">Click Rate</div>
                                <div class="bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $newsletter->analytics->click_rate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-60 text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Analytics will be available after the newsletter is sent.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recipients Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Recipients</h2>
            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $newsletter->recipients->count() }} subscribers</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sent At
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Opened At
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($newsletter->recipients as $recipient)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $recipient->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $recipient->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $recipient->pivot->status === 'sent' ? 'bg-green-100 text-green-800' : ($recipient->pivot->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($recipient->pivot->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $recipient->pivot->sent_at ? \Carbon\Carbon::parse($recipient->pivot->sent_at)->format('M j, Y g:i A') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $recipient->pivot->opened_at ? \Carbon\Carbon::parse($recipient->pivot->opened_at)->format('M j, Y g:i A') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No recipients selected.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Newsletter Content -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Newsletter Content</h2>
        </div>
        
        <div class="p-6">
            <div class="bg-gray-50 p-6 rounded-lg prose max-w-none">
                {!! $newsletter->content !!}
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Toggle CSS visibility
    const toggleCssBtn = document.getElementById('toggleCssBtn');
    const cssContent = document.getElementById('cssContent');
    const cssPlaceholder = document.getElementById('cssPlaceholder');
    
    if (toggleCssBtn) {
        toggleCssBtn.addEventListener('click', function() {
            cssContent.classList.toggle('hidden');
            cssPlaceholder.classList.toggle('hidden');
        });
    }
</script>
@endsection