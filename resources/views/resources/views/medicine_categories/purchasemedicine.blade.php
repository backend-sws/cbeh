@extends('layouts.admin')

@section('title', 'Medicine Purchases')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex card bg-white p-6 flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Medicine Purchases</h1>
                    <p class="text-gray-600 mt-1">Track and manage medicine purchases</p>
                </div>
                <div class="flex space-x-2">
                    <a href="purchase/add"
                        class="flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plus-circle"></i>
                        <span>New Purchase</span>
                    </a>
                </div>
            </div>
            {{-- Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Purchase id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $from_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $to_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                            <i class="ph ph-funnel mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.medicine.purchase.index') }}"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="card bg-white p-6">


                <!-- Table Section -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purchase #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tax
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Net Amount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment Mode
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($purchase_medicine as $data)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-white bg-purple-500 rounded-full">#{{ $data['purchase_no'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            Rs {{ number_format($data['total'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            Rs {{ number_format($data['tax'] ?? 0.0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $data['payment_status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $data['payment_status'] ?? 'Paid' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-purple-700">
                                            Rs{{ number_format($data['net_amount'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full 
        @if ($data['payment_type'] == 1) bg-green-100 text-green-800
        @elseif($data['payment_type'] == 2) bg-blue-100 text-blue-800
        @elseif($data['payment_type'] == 3) bg-indigo-100 text-indigo-800
        @elseif($data['payment_type'] == 4) bg-purple-100 text-purple-800
        @elseif($data['payment_type'] == 5) bg-teal-100 text-teal-800
        @else bg-gray-100 text-gray-800 @endif">

                                                @if ($data['payment_type'] == 1)
                                                    Cash
                                                @elseif($data['payment_type'] == 2)
                                                    Credit Card
                                                @elseif($data['payment_type'] == 3)
                                                    Debit Card
                                                @elseif($data['payment_type'] == 4)
                                                    UPI
                                                @elseif($data['payment_type'] == 5)
                                                    Bank Transfer
                                                @else
                                                    Cash
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.medicine.purchase.show', $data['id']) }}"
                                                    target="_blank"
                                                    class="p-2 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-colors"
                                                    title="View">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <!-- Delete Button -->
                                                <button type="button"
                                                    onclick="event.preventDefault(); document.getElementById('delete-purchase-{{ $data->id }}').submit();"
                                                    class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Hidden Delete Form -->
                                                <form id="delete-purchase-{{ $data->id }}"
                                                    action="{{ route('admin.medicine.purchase.delete', $data->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($purchase_medicine->hasPages())
                        <div class="px-6 py-4 border-t bg-white text-red-400 ">
                            {{ $purchase_medicine->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
