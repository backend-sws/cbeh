@extends('layouts.admin')

@section('title', 'Medicine')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex card bg-white p-6 flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Medicine Inventory</h1>
                    <p class="text-gray-600 mt-1">Manage your pharmacy inventory</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <a href="{{ route('admin.medicine.add') }}"
                        class="flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="ph ph-plus-circle text-white"></i>
                        <span>Add Medicine</span>
                    </a>
                </div>
            </div>

            <!-- Active Filter Display -->
            @if (request('stock_filter'))
                <div class="mb-4 px-4 py-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="ph ph-funnel-simple text-blue-600 mr-2"></i>
                            <span class="text-blue-700 font-medium">
                                Active Filter:
                                @if (request('stock_filter') == 'low_stock')
                                    Low Stock Items (Below 20)
                                @elseif(request('stock_filter') == 'critical_stock')
                                    Critical Stock Items (Below 10)
                                @elseif(request('stock_filter') == 'out_of_stock')
                                    Out of Stock Items
                                @endif
                            </span>
                        </div>
                        <a href="{{ route('admin.medicine.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="ph ph-x-circle mr-1"></i> Clear Filter
                        </a>
                    </div>
                </div>
            @endif

            <!-- Summary Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Card 1: Total Medicines -->
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Medicines</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['total_medicines'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $medicines->total() }} matching</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="ph ph-pill text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Available Strips -->
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Available Strips</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['available_quantity'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">In stock (packs/strips)</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="ph ph-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Total Pieces -->
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pieces</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['total_quantity'] }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Total individual units</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="ph ph-package text-2xl"></i>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->role == 'Admin')
                    <!-- Card 4: Total Purchase Value -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Purchase Value</p>
                                <h3 class="text-2xl font-bold text-gray-800">Rs
                                    {{ number_format($summary['total_purchase_value'], 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Inventory cost</p>
                            </div>
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="ph ph-arrow-down-left text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5: Total Selling Value -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Selling Value</p>
                                <h3 class="text-2xl font-bold text-gray-800">Rs
                                    {{ number_format($summary['total_selling_value'], 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Revenue potential</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="ph ph-arrow-up-right text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6: Expected Profit -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Expected Profit</p>
                                <h3 class="text-2xl font-bold text-gray-800">Rs
                                    {{ number_format($summary['expected_profit'], 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $summary['total_quantity'] }} units</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="ph ph-currency-dollar text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 7: Avg Profit per Unit -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Avg Profit/Unit</p>
                                <h3 class="text-2xl font-bold text-gray-800">Rs
                                    {{ number_format($summary['avg_profit_per_unit'], 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Per item profit</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="ph ph-trend-up text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Card 8: Low Stock Items -->
                <div class="bg-white rounded-lg shadow p-4 cursor-pointer hover:shadow-md transition-shadow"
                    onclick="applyStockFilter('low_stock')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['low_stock_count'] }}</h3>
                            <p class="text-xs text-red-500 mt-1">
                                <i class="ph ph-warning"></i> {{ $summary['out_of_stock_count'] }} out of stock
                            </p>
                        </div>
                        <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                            <i class="ph ph-warning text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 9: Critical Stock -->
                <div class="bg-white rounded-lg shadow p-4 cursor-pointer hover:shadow-md transition-shadow"
                    onclick="applyStockFilter('critical_stock')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Critical Stock</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['critical_stock_count'] ?? 0 }}</h3>
                            <p class="text-xs text-red-500 mt-1">Below 10 remaining</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="ph ph-skull text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 10: Out of Stock -->
                <div class="bg-white rounded-lg shadow p-4 cursor-pointer hover:shadow-md transition-shadow"
                    onclick="applyStockFilter('out_of_stock')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Out of Stock</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $summary['out_of_stock_count'] }}</h3>
                            <p class="text-xs text-red-500 mt-1">Zero quantity available</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="ph ph-x-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 rounded-t-lg">
                <form method="GET" action="{{ route('admin.medicine.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Medicine/Brand/Category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medicine</label>
                        <select name="medicine_filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Medicines</option>
                            @foreach ($all_medicines as $med)
                                <option value="{{ $med->id }}"
                                    {{ request('medicine_filter') == $med->id ? 'selected' : '' }}>
                                    {{ $med->medicine }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $from_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $to_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Hidden stock filter -->
                    @if (request('stock_filter'))
                        <input type="hidden" name="stock_filter" value="{{ request('stock_filter') }}">
                    @endif

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            <i class="ph ph-funnel mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.medicine.index') }}"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="card bg-white p-6 rounded-b-lg">
                <!-- Alerts -->
                <div class="mb-6">
                    <x-alert type="success" :message="session('success')" />
                    <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
                    <div class="msg-container"></div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Medicine
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Company
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Strip Size
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Available Strips
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Pieces
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Buying Price
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Selling Price
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($medicines as $medicine)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.medicine.edit', $medicine->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                                {{ $medicine->medicine }}
                                            </a>
                                            @if($medicine->unit)
                                                <span class="ml-1 text-xs text-gray-500 capitalize">({{ $medicine->unit }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $medicine->brandRelation->brand ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                {{ $medicine->strip_quantity ?? 1 }} pcs/strip
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($medicine->available_quantity > 10)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $medicine->available_quantity }} Strips
                                                </span>
                                            @elseif($medicine->available_quantity > 0)
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    {{ $medicine->available_quantity }} Strips
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $medicine->available_quantity }} Strips
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                            {{ $medicine->total_quantity }} Pcs
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-purple-700 font-medium">
                                            Rs {{ number_format($medicine->buying_price ?? 0, 2) }}
                                            <div class="text-[10px] text-gray-400 italic mt-0.5">Per strip</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-purple-700 font-medium">
                                            Rs {{ number_format($medicine->selling_price ?? 0, 2) }}
                                            <div class="text-[10px] text-gray-400 italic mt-0.5">Per strip</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('admin.medicine.edit', $medicine->id) }}"
                                                    class="p-2 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <button onclick="toggleMedicine(true, this, 'delete')"
                                                    data-url="{{ route('admin.medicine.delete', $medicine->id) }}"
                                                    class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No medicines found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($medicines->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0">
                                <div class="text-sm text-gray-700">
                                    Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of
                                    {{ $medicines->total() }} results
                                </div>
                                <div class="flex space-x-1">
                                    <!-- Previous Page Link -->
                                    @if ($medicines->onFirstPage())
                                        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                            &laquo; Previous
                                        </span>
                                    @else
                                        <a href="{{ $medicines->previousPageUrl() }}{{ request('stock_filter') ? '&stock_filter=' . request('stock_filter') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('medicine_filter') ? '&medicine_filter=' . request('medicine_filter') : '' }}{{ request('from_date') ? '&from_date=' . request('from_date') : '' }}{{ request('to_date') ? '&to_date=' . request('to_date') : '' }}"
                                            class="px-3 py-1 bg-white text-gray-700 rounded-md hover:bg-gray-50 border border-gray-300 transition-colors">
                                            &laquo; Previous
                                        </a>
                                    @endif

                                    <!-- Page Numbers -->
                                    @foreach ($medicines->getUrlRange(max(1, $medicines->currentPage() - 2), min($medicines->lastPage(), $medicines->currentPage() + 2)) as $page => $url)
                                        @if ($page == $medicines->currentPage())
                                            <span
                                                class="px-3 py-1 bg-purple-600 text-white rounded-md border border-purple-600">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}{{ request('stock_filter') ? '&stock_filter=' . request('stock_filter') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('medicine_filter') ? '&medicine_filter=' . request('medicine_filter') : '' }}{{ request('from_date') ? '&from_date=' . request('from_date') : '' }}{{ request('to_date') ? '&to_date=' . request('to_date') : '' }}"
                                                class="px-3 py-1 bg-white text-gray-700 rounded-md hover:bg-gray-50 border border-gray-300 transition-colors">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    <!-- Next Page Link -->
                                    @if ($medicines->hasMorePages())
                                        <a href="{{ $medicines->nextPageUrl() }}{{ request('stock_filter') ? '&stock_filter=' . request('stock_filter') : '' }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('medicine_filter') ? '&medicine_filter=' . request('medicine_filter') : '' }}{{ request('from_date') ? '&from_date=' . request('from_date') : '' }}{{ request('to_date') ? '&to_date=' . request('to_date') : '' }}"
                                            class="px-3 py-1 bg-white text-gray-700 rounded-md hover:bg-gray-50 border border-gray-300 transition-colors">
                                            Next &raquo;
                                        </a>
                                    @else
                                        <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                            Next &raquo;
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="toggleMedicine" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Confirm Deletion</h2>
                <button onclick="toggleMedicine(false)" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <form class="form-class" id="medicine_delete_form" method="post" action="">
                @csrf
                @method('DELETE')
                <div class="p-6">
                    <div class="flex items-center justify-center text-red-500 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-4xl mr-3"></i>
                        <p class="text-lg font-medium">Are you sure you want to delete this medicine?</p>
                    </div>
                    <p class="text-gray-600 text-center">This action cannot be undone. All related data will be permanently
                        removed.</p>
                </div>

                <!-- Buttons -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleMedicine(false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script>
        function toggleMedicine(show, tag = null, actionType = null) {
            const modal = $('#toggleMedicine');
            if (show) {
                modal.removeClass('hidden').addClass('flex');
                let element = $(tag);
                if (element) {
                    let url = element.data('url');
                    if (url) {
                        $('#medicine_delete_form').attr('action', url);
                    }
                }
            } else {
                modal.addClass('hidden').removeClass('flex');
            }
        }

        // Stock filter function - SIMPLE AND RELIABLE
        function applyStockFilter(filterType) {
            console.log('Applying stock filter:', filterType);

            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            // Remove page parameter to start from first page
            urlParams.delete('page');

            // Set the stock filter
            urlParams.set('stock_filter', filterType);

            // Build new URL
            const baseUrl = '{{ route('admin.medicine.index') }}';
            const newUrl = baseUrl + '?' + urlParams.toString();

            console.log('Redirecting to:', newUrl);

            // Redirect to new URL
            window.location.href = newUrl;
        }

        // Auto-dismiss alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.msg-container').children().fadeOut(200, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endpush
