@extends('layouts.admin')
@section('title', 'Medicine Dump & Loss Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-bold uppercase">Total Loss Amount</p>
                    <p class="text-2xl font-extrabold text-red-600">₹ {{ number_format($total_loss, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Value of dumped medicines</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-500 mr-4">
                    <i class="fas fa-pills text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-bold uppercase">Total Items Dumped</p>
                    <p class="text-2xl font-extrabold text-gray-700">{{ $dumps->sum('quantity') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total quantity removed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="border rounded p-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="border rounded p-2 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.medicine.dump.report') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h3 class="text-lg font-bold text-gray-800">Dump History Log</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Medicine Name</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Loss Amount</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($dumps as $dump)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($dump->dump_date)->format('d M, Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $dump->medicine->medicine ?? 'Unknown' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                        {{ $dump->quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-red-600">
                        ₹ {{ number_format($dump->loss_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $dump->reason == 'Expired' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800' }}">
                            {{ $dump->reason }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 italic">
                        {{ $dump->note ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        No dump records found for selected period.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($dumps->count() > 0)
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-700">TOTAL LOSS:</td>
                    <td class="px-6 py-3 text-right font-extrabold text-red-600 text-lg">
                        ₹ {{ number_format($total_loss, 2) }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection