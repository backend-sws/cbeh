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
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    @if (!empty($hasRootTalhaFile))
                        <form action="{{ route('admin.medicine.import.root') }}" method="POST" class="inline" onsubmit="return confirm('Import TALHA.xlsx from project root?')">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center space-x-2 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm" title="Import TALHA.xlsx from project root">
                                <i class="ph ph-lightning text-lg"></i>
                                <span>Import TALHA.xlsx</span>
                            </button>
                        </form>
                    @endif

                    <button type="button" onclick="toggleImportModal(true)"
                        class="flex items-center justify-center space-x-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        <i class="ph ph-file-arrow-up text-lg"></i>
                        <span>Import Excel / CSV</span>
                    </button>

                    <a href="{{ route('admin.medicine.add') }}"
                        class="flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                        <i class="ph ph-plus-circle text-lg"></i>
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

    <!-- Bulk Import Modal -->
    <div id="bulkImportModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-200">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-indigo-50/30">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-sm">
                        <i class="ph ph-file-arrow-up text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Bulk Import Medicines</h2>
                        <p class="text-xs text-gray-500">Upload Excel (.xlsx) or CSV file with 4 columns</p>
                    </div>
                </div>
                <button type="button" onclick="toggleImportModal(false)" class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-100">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5 max-h-[78vh] overflow-y-auto">
                @if (!empty($hasRootTalhaFile))
                    <!-- Detected Root File Quick Action -->
                    <div class="p-4 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-200 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 bg-emerald-600 text-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-lightning text-xl font-bold"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-emerald-900 flex items-center gap-1.5">
                                    <span>TALHA.xlsx detected in root directory</span>
                                    <span class="px-2 py-0.5 bg-emerald-200/70 text-emerald-800 text-[10px] font-semibold rounded-full">Ready</span>
                                </h4>
                                <p class="text-xs text-emerald-700 mt-0.5">85 medicines ready to import (Company, Name, Category, Strip Size)</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.medicine.import.root') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" onclick="this.innerHTML='<i class=\'ph ph-spinner animate-spin mr-1\'></i> Importing...'; this.disabled=true; this.form.submit();"
                                class="w-full sm:w-auto px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-lg shadow transition-all flex items-center justify-center space-x-1.5 whitespace-nowrap">
                                <i class="ph ph-check-circle"></i>
                                <span>Import TALHA.xlsx Now</span>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Upload Form -->
                <form id="bulk_import_form" method="POST" action="{{ route('admin.medicine.import') }}" enctype="multipart/form-data" class="space-y-4" onsubmit="handleImportSubmit(event)">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Excel / CSV File</label>
                        <div id="drop_zone" onclick="document.getElementById('excel_file_input').click()"
                            class="border-2 border-dashed border-gray-300 hover:border-indigo-500 rounded-xl p-6 text-center cursor-pointer transition-all bg-gray-50/50 hover:bg-indigo-50/20 group">
                            <input type="file" name="excel_file" id="excel_file_input" accept=".xlsx,.xls,.csv,.txt" class="hidden" required onchange="handleFileSelect(this)">
                            <div class="flex flex-col items-center justify-center pointer-events-none">
                                <div class="w-12 h-12 bg-indigo-50 group-hover:bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-2 transition-colors">
                                    <i class="ph ph-cloud-arrow-up text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700" id="file_name_display">Click here to browse or drag & drop file</p>
                                <p class="text-xs text-gray-500 mt-1" id="file_size_display">Supported formats: .xlsx, .xls, .csv (Max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Column Layout Guide -->
                    <div class="rounded-xl border border-gray-200 overflow-hidden bg-white shadow-sm">
                        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                <i class="ph ph-table text-indigo-600"></i> Expected 4 Columns in Excel
                            </span>
                            <a href="{{ route('admin.medicine.import.sample') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-1">
                                <i class="ph ph-download-simple"></i> Download Sample CSV
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-gray-100/70 text-gray-600 uppercase font-semibold">
                                    <tr>
                                        <th class="px-3 py-2">Col 1 (A)</th>
                                        <th class="px-3 py-2">Col 2 (B)</th>
                                        <th class="px-3 py-2">Col 3 (C)</th>
                                        <th class="px-3 py-2">Col 4 (D)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-600">
                                    <tr class="font-medium text-gray-800 bg-gray-50/50">
                                        <td class="px-3 py-1.5 text-purple-700 font-semibold">Company / Brand</td>
                                        <td class="px-3 py-1.5 text-purple-700 font-semibold">Medicine Name</td>
                                        <td class="px-3 py-1.5 text-purple-700 font-semibold">Category / Type</td>
                                        <td class="px-3 py-1.5 text-purple-700 font-semibold">Strip Size</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">ABBO</td>
                                        <td class="px-3 py-1.5 font-mono text-[11px]">VERTIN 16MG [BILASTIN 16]</td>
                                        <td class="px-3 py-1.5"><span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold text-[10px]">TABLET</span></td>
                                        <td class="px-3 py-1.5 font-semibold">10</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">ALKEM</td>
                                        <td class="px-3 py-1.5 font-mono text-[11px]">AZITRAL 500</td>
                                        <td class="px-3 py-1.5"><span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold text-[10px]">TABLET</span></td>
                                        <td class="px-3 py-1.5 font-semibold">5</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-1.5">ABBO</td>
                                        <td class="px-3 py-1.5 font-mono text-[11px]">DUPHALACK</td>
                                        <td class="px-3 py-1.5"><span class="px-1.5 py-0.5 rounded bg-green-50 text-green-700 font-semibold text-[10px]">SYRUP</span></td>
                                        <td class="px-3 py-1.5 font-semibold">1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Helpful Notice -->
                    <div class="text-[11px] text-gray-600 space-y-1 bg-amber-50/70 p-3 rounded-xl border border-amber-200">
                        <p class="font-bold text-amber-900 flex items-center gap-1">
                            <i class="ph ph-info font-bold"></i> Important Import Guidelines:
                        </p>
                        <p>• <strong>Row headers are optional</strong> — files with or without headers (like <code class="bg-amber-100 text-amber-900 px-1 py-0.5 rounded font-mono font-bold">TALHA.xlsx</code>) are automatically parsed.</p>
                        <p>• <strong>Automatic registration</strong> — New companies (brands) and categories are automatically added.</p>
                        <p>• <strong>Safe & Idempotent</strong> — Existing medicines with the same name have their strip size & category updated without creating duplicates.</p>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-2 flex justify-end space-x-3">
                        <button type="button" onclick="toggleImportModal(false)"
                            class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="btn_import_submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold shadow-sm transition-all flex items-center space-x-2">
                            <i class="ph ph-upload-simple text-base"></i>
                            <span id="btn_import_text">Upload & Import</span>
                        </button>
                    </div>
                </form>
            </div>
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

        // Toggle Bulk Import Modal
        function toggleImportModal(show) {
            const modal = $('#bulkImportModal');
            if (show) {
                modal.removeClass('hidden').addClass('flex');
            } else {
                modal.addClass('hidden').removeClass('flex');
            }
        }

        // Close modal on Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                toggleImportModal(false);
                toggleMedicine(false);
            }
        });

        // Close modal on backdrop click
        $('#bulkImportModal').on('click', function(e) {
            if (e.target === this) {
                toggleImportModal(false);
            }
        });

        // Handle File Selection
        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeKb = (file.size / 1024).toFixed(1);
                $('#file_name_display').html('<span class="text-indigo-600 font-bold">' + file.name + '</span>');
                $('#file_size_display').html('Selected size: ' + (sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB'));
                $('#drop_zone').addClass('border-indigo-500 bg-indigo-50/30').removeClass('border-gray-300');
            }
        }

        // Drag and drop handling for drop zone
        const dropZone = document.getElementById('drop_zone');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('border-indigo-500', 'bg-indigo-50/40');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50/40');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    const input = document.getElementById('excel_file_input');
                    input.files = files;
                    handleFileSelect(input);
                }
            }, false);
        }

        // Prevent double submit and show loading state
        function handleImportSubmit(e) {
            const btn = document.getElementById('btn_import_submit');
            const btnText = document.getElementById('btn_import_text');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                if (btnText) {
                    btnText.innerText = 'Importing... Please wait';
                }
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

