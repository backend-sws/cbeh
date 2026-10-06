@extends('layouts.admin')

@section('title', 'Pathology Bills')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Styling */
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single {
            height: 42px; border: 1px solid #d1d5db; border-radius: 0.5rem; display: flex; align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { top: 8px; }
        .select2-dropdown { z-index: 9999; }
    </style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen p-6 font-sans">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pathology Bills</h1>
                <p class="text-sm text-gray-500 mt-1">Manage billing, payments & track dues.</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <button onclick="openSearchModal()" class="inline-flex items-center px-4 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm focus:ring-4 focus:ring-purple-300">
                    <i class="ph ph-magnifying-glass text-lg mr-2"></i> Search IPD Patient
                </button>

                <a href="{{ route('admin.pathology.bill.add') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-300">
                    <i class="ph ph-plus-circle text-lg mr-2"></i> Generate Bill
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                    <div class="col-span-1 lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="ph ph-magnifying-glass"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Bill No, Patient Name, Phone..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase">Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-sm">
                            <option value="">All Statuses</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="due" {{ request('payment_status') == 'due' ? 'selected' : '' }}>Due / Partial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase">Date From</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.pathology.bill.index') }}" class="inline-flex justify-center items-center px-3 py-2 bg-gray-100 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors" title="Reset">
                            <i class="ph ph-arrow-counter-clockwise text-lg"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            
            <div class="px-6 pt-4">
                <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bill Info</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due / Adv</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bills as $bill)
                        @php
                            $statusClass = 'bg-red-100 text-red-800 border-red-200';
                            $statusText = 'Unpaid';
                            
                            // Advance Logic (Negative Due)
                            if ($bill->due_amount < -0.01) {
                                $statusClass = 'bg-purple-100 text-purple-800 border-purple-200';
                                $statusText = 'Advance';
                            } elseif ($bill->due_amount <= 0.01) {
                                $statusClass = 'bg-green-100 text-green-800 border-green-200';
                                $statusText = 'Paid';
                            } elseif ($bill->paid_amount > 0) {
                                $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                $statusText = 'Partial';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-indigo-600 group-hover:text-indigo-800 transition-colors">{{ $bill->bill_no }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5">{{ date('d M, Y', strtotime($bill->created_at)) }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($bill->patient)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-xs border border-gray-200">
                                            {{ substr($bill->patient->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $bill->patient->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $bill->patient->phone ?? '' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-sm">Unknown Patient</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-800">
                                ₹{{ number_format($bill->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600">
                                ₹{{ number_format($bill->paid_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($bill->due_amount > 0.01)
                                    <span class="text-red-600">₹{{ number_format($bill->due_amount, 2) }}</span>
                                @elseif($bill->due_amount < -0.01)
                                    <span class="text-purple-600 font-bold" title="Advance Payment">Adv: ₹{{ number_format(abs($bill->due_amount), 2) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center items-center gap-1">
                                    
                                    {{-- 1. PAYMENT BUTTON (Open Modal) --}}
                                    <button onclick="openPaymentModal({{ $bill->id }})" class="p-1.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Add Payment / View History">
                                        <i class="ph ph-wallet text-xl"></i>
                                    </button>

                                    {{-- 2. IPD PROFILE LINK (Medicine Module Style) --}}
                                    @if($bill->ipd_patient_id)
                                        <a href="{{ route('admin.pathology.bill.ipd.profile', $bill->ipd_patient_id) }}" class="p-1.5 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Go to IPD Ledger & Profile">
                                            <i class="ph ph-user-circle text-xl"></i>
                                        </a>
                                    @endif

                                    {{-- 3. VIEW --}}
                                    <a href="{{ route('admin.pathology.bill.show', $bill->id) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Details">
                                        <i class="ph ph-eye text-xl"></i>
                                    </a>
                                    
                                    {{-- 4. EDIT --}}
                                    <a href="{{ route('admin.pathology.bill.edit', $bill->id) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Bill">
                                        <i class="ph ph-pencil-simple text-xl"></i>
                                    </a>

                                    {{-- 5. PRINT --}}
                                    <a href="{{ route('admin.pathology.bill.bill', $bill->id) }}" target="_blank" class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors" title="Print Bill">
                                        <i class="ph ph-printer text-xl"></i>
                                    </a>
                                    
                                    {{-- 6. DELETE --}}
                                    <button onclick="showDeleteModal('{{ route('admin.pathology.bill.delete', $bill->id) }}')" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <i class="ph ph-trash text-xl"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-14 w-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="ph ph-flask text-3xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-gray-900 font-medium">No Bills Found</h3>
                                    <p class="text-gray-500 text-sm mt-1">Try adjusting filters or generate a new bill.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($bills->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $bills->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="searchModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl p-6 transform transition-all scale-95 opacity-0" id="searchModalContent">
            
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="ph ph-magnifying-glass text-indigo-600"></i> Search IPD Patient
                </h3>
                <button onclick="closeSearchModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Find by Name, Phone or IPD No</label>
                <select id="quickPatientSearch" class="w-full"></select>
            </div>

            <div class="text-xs text-gray-400 italic text-center">
                Select a patient to open their Pathology Ledger & Profile.
            </div>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all scale-95 opacity-0" id="paymentModalContent">
            <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Manage Payments</h2>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium" id="modalBillInfo">Loading...</p>
                </div>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600"><i class="ph ph-x text-2xl"></i></button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 text-center">
                        <div class="text-[10px] font-bold text-blue-600 uppercase mb-1">Total Bill</div>
                        <div class="text-xl font-bold text-blue-900" id="modalTotalAmount">₹0.00</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                        <div class="text-[10px] font-bold text-green-600 uppercase mb-1">Total Paid</div>
                        <div class="text-xl font-bold text-green-900" id="modalPaidAmount">₹0.00</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg border border-red-100 text-center">
                        <div class="text-[10px] font-bold text-red-600 uppercase mb-1">Due Amount</div>
                        <div class="text-xl font-bold text-red-900" id="modalDueAmount">₹0.00</div>
                    </div>
                </div>

                <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h3 class="text-xs font-bold text-gray-700 mb-3 uppercase flex items-center gap-1">
                        <i class="ph ph-plus-circle"></i> Add Payment
                    </h3>
                    <form action="{{ route('admin.pathology.bill.payment.add') }}" method="POST" class="grid grid-cols-12 gap-3 items-end">
                        @csrf
                        <input type="hidden" name="bill_id" id="modalBillIdInput">
                        
                        <div class="col-span-5">
                            <label class="block text-[10px] font-bold text-gray-600 mb-1 uppercase">Amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₹</span>
                                <input type="number" name="amount" id="modalPaymentInput" step="0.01" class="w-full pl-6 pr-3 py-2 border border-gray-300 rounded-lg text-sm font-bold focus:ring-green-500" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-[10px] font-bold text-gray-600 mb-1 uppercase">Mode</label>
                            <select name="payment_mode" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-green-500">
                                <option value="Cash">Cash</option>
                                <option value="UPI">UPI</option>
                                <option value="Card">Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Discount">Discount</option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <button type="submit" class="w-full py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 shadow-sm transition-colors">
                                CONFIRM
                            </button>
                        </div>
                    </form>
                </div>

                <h3 class="text-xs font-bold text-gray-500 mb-2 uppercase">Transaction History</h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg max-h-40 overflow-y-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-500 font-medium sticky top-0">
                            <tr>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Mode</th>
                                <th class="px-4 py-2 text-right">Amount</th>
                                <th class="px-4 py-2 text-center">Rcpt</th>
                            </tr>
                        </thead>
                        <tbody id="transactionHistoryBody" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-xl shadow-2xl p-6 transform transition-all scale-95 opacity-0 text-center" id="deleteModalContent">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="ph ph-warning text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Bill?</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure? This action will reverse all financial records associated with this bill.</p>
            <form id="deleteForm" method="POST" class="flex gap-3 justify-center">
                @csrf @method('DELETE')
                <button type="button" onclick="hideDeleteModal()" class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 text-sm font-medium">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium shadow-sm">Delete</button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // --- SEARCH MODAL LOGIC ---
    function openSearchModal() {
        const modal = document.getElementById('searchModal');
        const content = document.getElementById('searchModalContent');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
        
        // Initialize Select2 when modal opens
        $('#quickPatientSearch').select2({
            dropdownParent: $('#searchModal'),
            placeholder: "Search Name, Phone or IPD No...",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('admin.pathology.bill.ipd.search.ajax') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) { return { term: params.term }; },
                processResults: function (data) { return { results: data.results }; },
                cache: true
            }
        });

        // Redirect on Select
        $('#quickPatientSearch').on('select2:select', function (e) {
            if(e.params.data.id) window.location.href = "{{ url('admin/pathology/bill/ipd-profile') }}/" + e.params.data.id;
        });
    }

    function closeSearchModal() {
        const modal = document.getElementById('searchModal');
        const content = document.getElementById('searchModalContent');
        content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    // --- PAYMENT MODAL LOGIC ---
    function openPaymentModal(billId) {
        const modal = document.getElementById('paymentModal');
        const content = document.getElementById('paymentModalContent');
        
        // Reset & Loading State
        document.getElementById('modalBillIdInput').value = billId;
        document.getElementById('modalPaymentInput').value = '';
        document.getElementById('transactionHistoryBody').innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-400">Loading...</td></tr>';

        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });

        fetch(`/admin/pathology/bill/payments/${billId}`)
            .then(res => res.json())
            .then(data => {
                const bill = data.bill;
                document.getElementById('modalBillInfo').textContent = `Bill #${bill.bill_no} • ${bill.patient.name}`;
                document.getElementById('modalTotalAmount').innerText = `₹${parseFloat(bill.amount).toFixed(2)}`;
                document.getElementById('modalPaidAmount').innerText = `₹${parseFloat(bill.paid_amount).toFixed(2)}`;
                document.getElementById('modalDueAmount').innerText = `₹${parseFloat(bill.due_amount).toFixed(2)}`;
                
                // If Due > 0, auto-fill input
                if(parseFloat(bill.due_amount) > 0) document.getElementById('modalPaymentInput').value = bill.due_amount;

                let rows = '';
                if(data.transactions.length) {
                    data.transactions.forEach(txn => {
                        const date = new Date(txn.created_at).toLocaleDateString('en-IN');
                        const url = `{{ url('admin/pathology/bill/payment/receipt') }}/${txn.id}`;
                        rows += `<tr class="border-b hover:bg-gray-50"><td class="px-4 py-2 text-gray-600">${date}</td><td class="px-4 py-2">${txn.payment_mode}</td><td class="px-4 py-2 text-right text-green-600 font-bold">₹${parseFloat(txn.amount).toFixed(2)}</td><td class="px-4 py-2 text-center"><a href="${url}" target="_blank" class="text-blue-500 hover:text-blue-700"><i class="ph ph-printer"></i></a></td></tr>`;
                    });
                } else { rows = '<tr><td colspan="4" class="text-center py-4 text-gray-400">No records found.</td></tr>'; }
                document.getElementById('transactionHistoryBody').innerHTML = rows;
            });
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        const content = document.getElementById('paymentModalContent');
        content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    // --- DELETE MODAL ---
    function showDeleteModal(url) {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        document.getElementById('deleteForm').action = url;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); });
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }
</script>
@endpush