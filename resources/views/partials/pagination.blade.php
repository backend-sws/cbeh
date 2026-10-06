@php
    $paginator = $users ?? $records ?? $items ?? $data ?? $list ?? null;
@endphp

@if(isset($paginator) && method_exists($paginator, 'total') && $paginator->total() > 0)
@php
    // Retain all current query parameters across pagination links
    $paginator->appends(request()->query());

    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $total = $paginator->total();
    $perPage = (int) ($paginator->perPage() ?: 10);
    $firstItem = $paginator->firstItem() ?? 1;
    $lastItem = $paginator->lastItem() ?? $total;

    // Calculate sliding window with ellipsis
    $elements = [];
    if ($lastPage <= 7) {
        for ($i = 1; $i <= $lastPage; $i++) {
            $elements[] = $i;
        }
    } else {
        if ($currentPage <= 4) {
            for ($i = 1; $i <= 5; $i++) {
                $elements[] = $i;
            }
            $elements[] = '...';
            $elements[] = $lastPage;
        } elseif ($currentPage >= $lastPage - 3) {
            $elements[] = 1;
            $elements[] = '...';
            for ($i = $lastPage - 4; $i <= $lastPage; $i++) {
                $elements[] = $i;
            }
        } else {
            $elements[] = 1;
            $elements[] = '...';
            $elements[] = $currentPage - 1;
            $elements[] = $currentPage;
            $elements[] = $currentPage + 1;
            $elements[] = '...';
            $elements[] = $lastPage;
        }
    }
@endphp

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-3 px-2 border-t border-gray-100 mt-3">
    {{-- Left: Record Summary --}}
    <div class="text-xs sm:text-sm text-gray-600 order-2 sm:order-1 text-center sm:text-left">
        Showing <span class="font-semibold text-gray-900">{{ $firstItem }}</span>
        to <span class="font-semibold text-gray-900">{{ $lastItem }}</span>
        of <span class="font-semibold text-gray-900">{{ number_format($total) }}</span> results
    </div>

    {{-- Center: Pagination Controls --}}
    @if($lastPage > 1)
    <div class="order-1 sm:order-2 flex flex-col items-center">
        {{-- Mobile View: Compact Prev / Page / Next --}}
        <div class="flex items-center space-x-2 sm:hidden">
            @if ($currentPage <= 1)
                <span class="px-3 py-1.5 text-xs text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    &larr; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                    &larr; Prev
                </a>
            @endif

            <span class="text-xs font-semibold text-gray-700 px-2">
                Page {{ $currentPage }} of {{ $lastPage }}
            </span>

            @if ($currentPage >= $lastPage)
                <span class="px-3 py-1.5 text-xs text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    Next &rarr;
                </span>
            @else
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">
                    Next &rarr;
                </a>
            @endif
        </div>

        {{-- Desktop / Tablet View: Full Sliding Window --}}
        <div class="hidden sm:flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($currentPage <= 1)
                <span class="w-9 h-9 flex items-center justify-center text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed" title="Previous page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="w-9 h-9 flex items-center justify-center text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-purple-50 hover:text-[var(--main-color,#924AEF)] hover:border-purple-300 transition shadow-sm" title="Previous page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Numbered Page Links --}}
            @foreach ($elements as $element)
                @if ($element === '...')
                    <span class="w-8 h-9 flex items-center justify-center text-gray-400 select-none font-semibold">
                        &hellip;
                    </span>
                @elseif ($element == $currentPage)
                    <span class="w-9 h-9 flex items-center justify-center font-semibold text-white rounded-lg bg-[var(--main-color,#924AEF)] shadow-sm border border-[var(--main-color,#924AEF)]">
                        {{ $element }}
                    </span>
                @else
                    <a href="{{ $paginator->url($element) }}"
                        class="w-9 h-9 flex items-center justify-center text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-purple-50 hover:text-[var(--main-color,#924AEF)] hover:border-purple-300 transition shadow-sm">
                        {{ $element }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($currentPage >= $lastPage)
                <span class="w-9 h-9 flex items-center justify-center text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed" title="Next page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="w-9 h-9 flex items-center justify-center text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-purple-50 hover:text-[var(--main-color,#924AEF)] hover:border-purple-300 transition shadow-sm" title="Next page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Right: Rows Per Page Dropdown --}}
    <div class="flex items-center gap-2 order-3">
        <label for="perPageSelect" class="text-xs sm:text-sm text-gray-500 font-medium whitespace-nowrap">Per page:</label>
        <form method="GET" action="{{ url()->current() }}" class="inline-block m-0">
            {{-- Preserve all current GET query parameters except perPage & page --}}
            @foreach(request()->except('perPage', 'page') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $subValue)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $subValue }}">
                    @endforeach
                @elseif(!is_null($value) && $value !== '')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            @php
                $sizes = [5, 10, 20, 50, 100];
                if (!in_array($perPage, $sizes)) {
                    $sizes[] = $perPage;
                    sort($sizes);
                }
            @endphp
            <select id="perPageSelect" name="perPage" onchange="this.form.submit()"
                class="text-xs sm:text-sm border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-gray-700 font-medium focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none cursor-pointer transition shadow-sm hover:border-gray-400">
                @foreach($sizes as $size)
                    <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
@endif