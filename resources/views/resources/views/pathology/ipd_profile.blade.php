@extends('layouts.admin')

@section('title', 'IPD Pathology Profile')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 font-sans">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">IPD Pathology Ledger</h1>
                <p class="text-sm text-gray-500">Managing records for <span class="font-semibold text-purple-600">{{ $ipdPatient->patient->name }}</span></p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="openAccountPayModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 shadow-sm transition-colors">
                    <i class="ph ph-money text-lg mr-2"></i> Collect Payment
                </button>

                <a href="{{ route('admin.pathology.bill.ipd.statement', $ipdPatient->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                    <i class="ph ph-printer text-lg mr-2"></i> Consolidated Bill
                </a>

                <a href="{{ route('admin.pathology.bill.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ph ph-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-green-100 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>     
        @endif

        @if (session('error'))
            <div class="mb-6 px-4 py-3 bg-red-100 border border-red-200 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>     
        @endif


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Patient Name</label>
                    <div class="text-lg font-bold text-gray-800">{{ $ipdPatient->patient->name }}</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">IPD Number</label>
                    <div class="text-lg font-bold text-purple-600">#{{ $ipdPatient->ipd_no }}</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Phone</label>
                    <div class="text-base font-medium text-gray-700">{{ $ipdPatient->patient->phone ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Doctor</label>
                    <div class="text-base font-medium text-gray-700">{{ $ipdPatient->doctor->name ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-5 rounded-xl border border-blue-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold text-blue-800 uppercase block">Total Bill Amount</span>
                    <span class="text-3xl font-bold text-blue-900">₹{{ number_format($total_bill_amount, 2) }}</span>
                </div>
                <div class="p-3 bg-blue-100 rounded-full text-blue-600"><i class="ph ph-receipt text-2xl"></i></div>
            </div>

            <div class="bg-green-50 p-5 rounded-xl border border-green-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold text-green-800 uppercase block">Total Received</span>
                    <span class="text-3xl font-bold text-green-900">₹{{ number_format($total_paid, 2) }}</span>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600"><i class="ph ph-check-circle text-2xl"></i></div>
            </div>

            <div class="p-5 rounded-xl border flex items-center justify-between {{ $net_balance > 0 ? 'bg-red-50 border-red-200' : 'bg-purple-50 border-purple-200' }}">
                <div>
                    <span class="text-sm font-bold uppercase block {{ $net_balance > 0 ? 'text-red-800' : 'text-purple-800' }}">
                        {{ $net_balance > 0 ? 'Net Due' : 'Advance Balance' }}
                    </span>
                    <span class="text-3xl font-bold {{ $net_balance > 0 ? 'text-red-900' : 'text-purple-900' }}">
                        ₹{{ number_format(abs($net_balance), 2) }}
                    </span>
                </div>
                <div class="p-3 rounded-full {{ $net_balance > 0 ? 'bg-red-100 text-red-600' : 'bg-purple-100 text-purple-600' }}">
                    <i class="ph ph-wallet text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="ph ph-scroll text-indigo-500"></i> Bill History
                    </h3>
                    <a href="{{ route('admin.pathology.bill.add') }}?patient_id={{ $ipdPatient->patient_id }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        + New Bill
                    </a>
                </div>
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium sticky top-0">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Bill No</th>
                                <th class="px-6 py-3 text-right">Amount</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bills as $bill)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-600">{{ date('d M Y', strtotime($bill->bill_date)) }}</td>
                                <td class="px-6 py-3 font-medium text-indigo-600">{{ $bill->bill_no }}</td>
                                <td class="px-6 py-3 text-right font-bold text-gray-800">₹{{ number_format($bill->amount, 2) }}</td>
                                <td class="px-6 py-3 text-center">
                                    @if($bill->due_amount <= 0.01)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Paid</span>
                                    @elseif($bill->paid_amount > 0)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Partial</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('admin.pathology.bill.bill', $bill->id) }}" target="_blank" class="text-gray-400 hover:text-gray-600" title="Print Bill">
                                        <i class="ph ph-printer text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-6 text-center text-gray-400">No bills generated yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="ph ph-clock-counter-clockwise text-green-500"></i> Payment History
                    </h3>
                </div>
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-medium sticky top-0">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Mode</th>
                                <th class="px-6 py-3">Note</th>
                                <th class="px-6 py-3 text-right">Amount</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $txn)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-600">{{ date('d M Y', strtotime($txn->payment_date)) }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded text-xs font-medium text-gray-600">
                                        {{ $txn->payment_mode }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs text-gray-500 italic max-w-[150px] truncate" title="{{ $txn->note }}">
                                    {{Str::limit($txn->note, 20)}}
                                </td>
                                <td class="px-6 py-3 text-right font-bold text-green-600">₹{{ number_format($txn->amount, 2) }}</td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('admin.pathology.bill.receipt.print', $txn->id) }}" target="_blank" class="text-gray-400 hover:text-gray-600" title="Print Receipt">
                                        <i class="ph ph-receipt text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-6 text-center text-gray-400">No payments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="accountPayModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl p-6 transform transition-all scale-95 opacity-0" id="accountPayContent">
            
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Collect Payment</h3>
                    <p class="text-xs text-gray-500">Amount will settle oldest dues first.</p>
                </div>
                <button onclick="closeAccountPayModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.pathology.bill.settle_account') }}" method="POST">
                @csrf
                <input type="hidden" name="ipd_id" value="{{ $ipdPatient->id }}">
                
                <div class="bg-blue-50 p-3 rounded border border-blue-100 mb-4 flex justify-between items-center">
                    <span class="text-sm font-medium text-blue-800">Current Net Due:</span>
                    <span class="text-lg font-bold text-blue-900">₹{{ number_format($net_balance > 0 ? $net_balance : 0, 2) }}</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Amount</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                            <input type="number" name="amount" step="0.01" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-bold text-gray-800" placeholder="Enter amount" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Payment Mode</label>
                        <select name="payment_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI / Online</option>
                            <option value="Card">Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Note (Optional)</label>
                        <textarea name="note" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="e.g. Advance deposit"></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition-all active:scale-95">
                        Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    function openAccountPayModal() {
        const modal = document.getElementById('accountPayModal');
        const content = document.getElementById('accountPayContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeAccountPayModal() {
        const modal = document.getElementById('accountPayModal');
        const content = document.getElementById('accountPayContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
@endpush