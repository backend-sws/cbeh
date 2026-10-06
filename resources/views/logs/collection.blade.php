@extends('layouts.admin')
@section('title', 'Collection Logs')
@section('content')

<body class="bg-gray-100 p-2">

    {{-- Success & Error Messages --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="container py-4 px-4 mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        {{-- Header Section --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Payment Collections</h1>
            <div>
                {{-- <a href="#"
                    class="inline-flex items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                    <i class="ph ph-plus-bold mr-2"></i> New Collection
                </a> --}}
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Patient or Staff Name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                        <option value="">All Methods</option>
                        <option value="Cash" @if(request('payment_method') == 'Cash') selected @endif>Cash</option>
                        <option value="Card" @if(request('payment_method') == 'Card') selected @endif>Card</option>
                        <option value="UPI" @if(request('payment_method') == 'UPI') selected @endif>UPI</option>
                        <option value="Net Banking" @if(request('payment_method') == 'Net Banking') selected @endif>Net Banking</option>
                        <option value="Cheque" @if(request('payment_method') == 'Bank Transfer') selected @endif>Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                </div>

                <div class="flex space-x-2">
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                        <i class="ph ph-funnel mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.collection.index') }}"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table Section --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($collections as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item->user?->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $item->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $item->patient?->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $item->patient?->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-green-600">
                                ₹{{ number_format($item->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full 
                                    @if($item->payment_method == 'Cash') bg-blue-100 text-blue-800
                                    @elseif($item->payment_method == 'Card') bg-purple-100 text-purple-800
                                    @elseif($item->payment_method == 'UPI') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $item->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-sm rounded-full bg-pink-100 text-main-color">
                                    {{ $item->Payment_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->created_at)->format('jS M Y') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('g:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No collection records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            @include('partials.pagination', ['users' => $collections])
        </div>
    </div>

</body>
@endsection