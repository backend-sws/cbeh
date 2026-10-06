@extends('layouts.admin')

@section('title', 'Medicine Bills')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div
            class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center mb-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Medicine Sales</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage all medicine sales and transactions</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.medicine.ipd.search') }}">
                        <button
                            class="flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white border border-indigo-600 rounded-md text-sm font-medium transition-colors ">
                            <i class="fas fa-procedures mr-2"></i>Ipd Search
                        </button>
                    </a>
                    <a href="{{ route('admin.medicine.bill.due') }}">
                        <button
                            class="flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-exclamation-circle mr-2"></i> Due Bills
                        </button>
                    </a>
                    <a href="{{ route('admin.medicine.bill.add') }}">
                        <button
                            class="flex items-center px-4 py-2 bg-main-color hover:bg-pink-700 text-white rounded-md text-sm font-medium transition-colors">
                            <i class="ph ph-plus-circle mr-2"></i> New Bill
                        </button>
                    </a>
                </div>
            </div>
        </div>

        @if (auth()->user()->role == 'Admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($total_revenue, 2) }}</p>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-full text-purple-600">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Cash Collected</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($total_cash, 2) }}</p>
                        </div>
                        <div class="p-2 bg-green-50 rounded-full text-green-600">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Online Collected</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($total_online, 2) }}</p>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-full text-blue-600">
                            <i class="fas fa-credit-card text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
            <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Bill No, Patient, Payment Type..."
                            class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>
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
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors shadow-sm">
                        <i class="ph ph-funnel mr-2"></i> Apply
                    </button>
                    <a href="{{ route('admin.medicine.bill.index') }}"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors border border-gray-300">
                        <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill
                                #</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($medicine_bills as $bill)
                            @php
                                $paid = $bill->paid_cash + $bill->paid_online + $bill->discount;
                                $due = $bill->net_amount - $paid;
                                // Use a tolerance for floating point comparison
                                $isPaid = $paid >= $bill->net_amount - 0.1;
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-blue-600 font-bold">#{{ $bill->bill_number }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</div>
                                    {{-- <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($bill->bill_date)->format('h:i A') }}</div> --}}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-9 w-9">
                                            <div
                                                class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase text-xs">
                                                {{ substr($bill->patient_name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $bill->patient_name }}</div>
                                            @if ($bill->patient)
                                                <div class="text-xs text-gray-500">
                                                    {{ $bill->patient->email ?? $bill->patient->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <div>
                                            @if ($isPaid)
                                                <span
                                                    class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                            @elseif($paid == 0)
                                                <span
                                                    class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Unpaid
                                                </span>
                                            @else
                                                <span
                                                    class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Partial
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            @if ($bill->paid_cash > 0)
                                                <div>Cash: <span
                                                        class="font-semibold">₹{{ number_format($bill->paid_cash, 2) }}</span>
                                                </div>
                                            @endif
                                            @if ($bill->paid_online > 0)
                                                <div>Online: <span
                                                        class="font-semibold">₹{{ number_format($bill->paid_online, 2) }}</span>
                                                </div>
                                            @endif
                                            @if ($due > 0.1)
                                                <div class="text-red-600 font-bold mt-0.5">Due:
                                                    ₹{{ number_format($due, 2) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-bold text-base">₹{{ number_format($bill->net_amount, 2) }}</div>
                                    @if ($bill->discount > 0)
                                        <div class="text-xs text-green-600">Disc: ₹{{ number_format($bill->discount, 2) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">

                                        @if ($due > 0.1)
                                            <button
                                                onclick="openPayModal({{ $bill->id }}, '{{ $bill->bill_number }}', {{ $due }})"
                                                class="text-white bg-indigo-600 hover:bg-indigo-700 p-1.5 rounded-md shadow-sm transition-colors"
                                                title="Pay Due">
                                                <i class="fas fa-hand-holding-usd text-xs"></i>
                                            </button>
                                        @endif

                                        <button onclick="openHistoryModal({{ $bill->id }})"
                                            class="text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 p-1.5 rounded-md transition-colors"
                                            title="Payment History">
                                            <i class="fas fa-history text-xs"></i>
                                        </button>

                                        <a href="{{ route('admin.medicine.bill.show', $bill->id) }}" target="_blank"
                                            class="text-green-600 hover:bg-green-50 p-1.5 rounded-md transition-colors"
                                            title="Print Bill">
                                            <i class="fas fa-print text-lg"></i>
                                        </a>

                                        <a href="{{ route('admin.medicine.bill.edit', $bill->id) }}"
                                            class="text-blue-600 hover:bg-blue-50 p-1.5 rounded-md transition-colors"
                                            title="Edit">
                                            <i class="fas fa-pen text-lg"></i>
                                        </a>

                                        @if ($bill->ipd_patient_id)
                                            <a href="{{ route('admin.medicine.ipd.dashboard', $bill->ipd_patient_id) }}"
                                                class="text-white bg-indigo-600 hover:bg-indigo-700 p-1.5 rounded-md">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.medicine.bill.delete', $bill->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this bill?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:bg-red-50 p-1.5 rounded-md transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash-alt text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                                        <p>No medicine bills found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $medicine_bills->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div id="payModal"
        class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-96 p-6 relative transform transition-all scale-100">
            <button onclick="closePayModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Paying Amount (₹) <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                        <input type="number" step="0.01" name="pay_amount" id="modal_pay_amount"
                            class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-semibold"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Mode <span
                            class="text-red-500">*</span></label>
                    <select name="payment_mode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="Cash">Cash</option>
                        <option value="Online">Online (UPI/Card/Bank)</option>
                        <option value="Discount">Discount</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-transform active:scale-95 text-sm">
                    Confirm Payment
                </button>
            </form>
        </div>
    </div>

    <div id="historyModal"
        class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm">
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
                    <p class="text-sm text-gray-500">Transaction log for Bill #<span id="hist_bill_no"
                            class="font-bold text-indigo-600"></span></p>
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
                            <tr>
                                <td colspan="4" class="text-center py-6 text-sm text-gray-500">Loading data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5 text-right">
                <button onclick="closeHistoryModal()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-5 py-2 rounded-lg text-sm transition-colors">Close</button>
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
            $('#modal_bill_no').text('#' + billNo);
            $('#modal_due_amount').text(due.toFixed(2));

            $('#modal_pay_amount').val(due.toFixed(2));
            // $('#modal_pay_amount').attr('max', due);

            $('#payModal').removeClass('hidden');
        }

        function closePayModal() {
            $('#payModal').addClass('hidden');
        }

        function submitDuePayment(e) {
            e.preventDefault();

            // Show loading
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
                    if (res.status === 1) {
                        if (res.print_url) {
                            window.open(res.print_url, '_blank', 'width=400,height=600');
                        }
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
            $('#historyTableBody').html(
                '<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</td></tr>'
            );

            $.get("/admin/medicine/payment-history/" + billId, function(res) {
                if (res.status === 1) {
                    $('#hist_bill_no').text(res.bill_no);
                    let rows = '';

                    if (res.data.length === 0) {
                        rows =
                            '<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500">No payment history found.</td></tr>';
                    } else {
                        res.data.forEach(function(pay) {
                            rows += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700">${pay.date}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                alert("Server Error");
                closeHistoryModal();
            });
        }

        function closeHistoryModal() {
            $('#historyModal').addClass('hidden');
        }
    </script>
@endpush
