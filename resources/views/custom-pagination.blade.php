@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="px-6 py-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
                {{-- Results information --}}
                <div class="text-sm text-gray-600">
                    Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} 
                    of {{ $paginator->total() }} results
                </div>

                <div class="flex space-x-2">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <button disabled class="px-3 py-1 border rounded text-gray-400">Previous</button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" 
                           class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">Previous</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="px-3 py-1">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="px-3 py-1 border rounded bg-blue-600 text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" 
                                       class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" 
                           class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100">Next</a>
                    @else
                        <button disabled class="px-3 py-1 border rounded text-gray-400">Next</button>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif