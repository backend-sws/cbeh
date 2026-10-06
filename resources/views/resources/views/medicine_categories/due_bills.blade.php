@extends('layouts.admin')

@section('title', 'Due Bills List')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center mb-6 shadow-sm">
        <div class="flex justify-between items-center w-full">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Pending / Due Bills</h2>
                <p class="text-sm text-gray-600 mt-1">Manage outstanding payments and bill history</p>
            </div>
            </div>
    </div>

    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <form method="GET" class="p-6 flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-grow">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search by Bill No, Patient Name..."
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex space-x-2">
                <button type="submit"
                    class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors shadow-sm text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.medicine.bill.due') }}"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm font-medium">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Net</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-green-600 uppercase tracking-wider">Paid</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-red-600 uppercase tracking-wider">Due Amount</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($due_bills as $bill)
                        @php
                            $paid = $bill->paid_cash + $bill->paid_online + $bill->discount;
                            $due = $bill->net_amount - $paid;
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-indigo-600 font-bold">#{{ $bill->bill_number }}</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-9 w-9">
                                        {{-- Use a default avatar if no image is available (adjust path as needed) --}}
                                        <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                            {{ substr($bill->patient_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $bill->patient_name }}</div>
                                        @if($bill->patient)
                                            <div class="text-xs text-gray-500">{{ $bill->patient->email ?? $bill->patient->phone ?? '' }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($bill->bill_date)->format('h:i A') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                ₹{{ number_format($bill->net_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600">
                                ₹{{ number_format($paid, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-red-600">
                                ₹{{ number_format($due, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <button onclick="openPayModal({{ $bill->id }}, '{{ $bill->bill_number }}', {{ $due }})"
                                        class="flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 rounded text-xs font-semibold transition-colors"
                                        title="Make Payment">
                                        <i class="fas fa-hand-holding-usd mr-1.5"></i> Pay
                                    </button>

                                    <button onclick="openHistoryModal({{ $bill->id }})"
                                        class="flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200 rounded text-xs font-semibold transition-colors"
                                        title="History">
                                        <i class="fas fa-history mr-1.5"></i> History
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                                    <p class="text-lg font-medium">No pending bills found!</p>
                                    <p class="text-sm">All payments are up to date.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $due_bills->withQueryString()->links() }}
        </div>
    </div>

</div>

<div id="payModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-96 p-6 relative transform transition-all scale-100">
        <button onclick="closePayModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="mb-5">
            <h3 class="text-xl font-bold text-gray-800">Clear Dues</h3>
            <p class="text-sm text-gray-500">Record a payment for this bill</p>
        </div>

        <form id="payDueForm" onsubmit="submitDuePayment(event)">
            @csrf
            <input type="hidden" name="bill_id" id="modal_bill_id">

            <div class="bg-gray-50 p-3 rounded-lg mb-4 border border-gray-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs text-gray-500">Bill Number</span>
                    <span id="modal_bill_no" class="text-sm font-bold text-gray-800"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Total Due</span>
                    <span class="text-sm font-bold text-red-600">₹<span id="modal_due_amount"></span></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Paying Amount (₹) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                    <input type="number" step="0.01" name="pay_amount" id="modal_pay_amount" 
                        class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-semibold" 
                        required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Mode <span class="text-red-500">*</span></label>
                <select name="payment_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                    <option value="Cash">Cash</option>
                    <option value="Online">Online (UPI/Card/Bank)</option>
                    <option value="Discount">Discount</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-transform active:scale-95 text-sm">
                Confirm Payment
            </button>
        </form>
    </div>
</div>

<div id="historyModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-[650px] p-6 relative">
        <button onclick="closeHistoryModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <div class="mb-5 flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-full text-indigo-600">
                <i class="fas fa-history text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Payment History</h3>
                <p class="text-sm text-gray-500">Transaction log for Bill #<span id="hist_bill_no" class="font-bold text-indigo-600"></span></p>
            </div>
        </div>
        
        <div class="overflow-hidden border border-gray-200 rounded-lg">
            <div class="overflow-y-auto max-h-80">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Mode</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase">Receipt</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="bg-white divide-y divide-gray-100">
                        <tr><td colspan="4" class="text-center py-6 text-sm text-gray-500">Loading data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 text-right">
            <button onclick="closeHistoryModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-5 py-2 rounded-lg text-sm transition-colors">Close</button>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script>
    // --- PAY MODAL LOGIC ---
    function openPayModal(id, billNo, due) {
        $('#modal_bill_id').val(id);
        $('#modal_bill_no').text('#' + billNo); // Set text for span
        $('#modal_due_amount').text(due.toFixed(2)); // Set text for span
        
        $('#modal_pay_amount').val(due.toFixed(2)); 
        $('#modal_pay_amount').attr('max', due); 
        
        $('#payModal').removeClass('hidden');
    }

    function closePayModal() {
        $('#payModal').addClass('hidden');
    }

    function submitDuePayment(e) {
        e.preventDefault();
        
        // Show loading state (optional UI enhancement)
        const btn = $(e.target).find('button[type="submit"]');
        const originalText = btn.text();
        btn.prop('disabled', true).text('Processing...');

        let formData = new FormData(document.getElementById('payDueForm'));

        $.ajax({
            url: "{{ route('admin.medicine.bill.pay_due') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.status === 1) {
                    // Success logic
                    if(res.print_url) {
                        window.open(res.print_url, '_blank', 'width=400,height=600');
                    }
                    // Simple toast or alert
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message);
                    btn.prop('disabled', false).text(originalText);
                }
            },
            error: function(err) {
                alert("Something went wrong!");
                btn.prop('disabled', false).text(originalText);
            }
        });
    }

    // --- HISTORY MODAL LOGIC ---
    function openHistoryModal(billId) {
        $('#historyModal').removeClass('hidden');
        $('#hist_bill_no').text('...');
        $('#historyTableBody').html('<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</td></tr>');

        $.get("/admin/medicine/payment-history/" + billId, function(res) {
            if(res.status === 1) {
                $('#hist_bill_no').text(res.bill_no);
                let rows = '';

                if(res.data.length === 0) {
                    rows = '<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500">No payment history found.</td></tr>';
                } else {
                    res.data.forEach(function(pay) {
                        rows += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700">${pay.date}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        ${pay.mode}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-bold text-green-600">₹ ${pay.amount}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="${pay.print_url}" target="_blank" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-indigo-100 hover:text-indigo-600 transition-colors"
                                       title="Print Receipt">
                                       <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#historyTableBody').html(rows);
            } else {
                alert("Failed to fetch history.");
                closeHistoryModal();
            }
        }).fail(function() {
            alert("Server Error: Unable to fetch history.");
            closeHistoryModal();
        });
    }

    function closeHistoryModal() {
        $('#historyModal').addClass('hidden');
    }
</script>
@endpush