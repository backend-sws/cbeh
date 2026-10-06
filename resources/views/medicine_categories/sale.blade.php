@extends('layouts.admin')

@section('title', 'Used Medicines')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Used Medicines</h1>

    <div class="bg-white shadow rounded-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold">Medicines</th>
                    <th class="px-4 py-2 text-left font-semibold">Used Quantity</th>
                    <th class="px-4 py-2 text-left font-semibold">Used At</th>
                    <th class="px-4 py-2 text-left font-semibold">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($sale_medicines as $sale)
                <tr class="hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $sale->medicine->medicine ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-md text-sm">
                            {{ $sale->quantity }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $sale->usage_type ?? 'Prescription' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="inline-block bg-blue-100 text-blue-600 px-3 py-1 rounded-md text-sm text-center leading-tight">
                            {{ \Carbon\Carbon::parse($sale->created_at)->format('g:i A') }}<br>
                            {{ \Carbon\Carbon::parse($sale->created_at)->format('jS M, Y') }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No used medicine records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $sale_medicines->links() }}
        </div>
    </div>
</div>
@endsection
