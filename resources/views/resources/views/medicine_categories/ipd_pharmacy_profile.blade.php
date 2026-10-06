@extends('layouts.admin')
@section('title', 'IPD Pharmacy Ledger')
@section('content')

    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">

                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $ipdPatient->patient->name }}</h2>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-bold">IPD:
                            {{ $ipdPatient->ipd_no }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-phone-alt mr-1"></i> {{ $ipdPatient->patient->phone }} |
                        <i class="fas fa-user-md mr-1"></i> {{ $ipdPatient->doctor->name }}
                    </p>
                    <div class="text-sm text-gray-700">
                        <span class="font-semibold">Bed:</span>
                        <span class="bg-blue-50 text-blue-700 px-2 rounded text-xs border border-blue-100">
                            {{ $ipdPatient->beds->name ?? 'Not Assigned' }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        DOA: {{ \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d M, Y h:i A') }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.medicine.ipd.print_consolidated', $ipdPatient->id) }}" target="_blank">
                        <button
                            class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-transform active:scale-95">
                            <i class="fas fa-file-invoice mr-2"></i> Final Bill Statement
                        </button>
                    </a>

                    <button onclick="openPaymentModal()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-md transition-transform active:scale-95">
                        <i class="fas fa-wallet mr-2"></i> Receive Payment
                    </button>

                    <a href="{{ route('admin.medicine.ipd.bill.add', $ipdPatient->id) }}">
                        <button
                            class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-transform active:scale-95">
                            <i class="fas fa-plus mr-1"></i> New Sale
                        </button>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex justify-between items-center">
                    <div>
                        <div class="text-xs text-blue-600 font-bold uppercase tracking-wider">Total Inventory Value</div>
                        <div class="text-2xl font-bold text-gray-800">₹ {{ number_format($total_medicine_cost, 2) }}</div>
                    </div>
                    <div class="text-blue-200"><i class="fas fa-shopping-cart text-4xl"></i></div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg border border-green-100 flex justify-between items-center">
                    <div>
                        <div class="text-xs text-green-600 font-bold uppercase tracking-wider">Total Received (Cash/Online)
                        </div>
                        <div class="text-2xl font-bold text-gray-800">₹ {{ number_format($total_paid, 2) }}</div>
                    </div>
                    <div class="text-green-200"><i class="fas fa-money-bill-wave text-4xl"></i></div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100 flex justify-between items-center">
                    <div>
                        <div class="text-xs text-purple-600 font-bold uppercase tracking-wider">Total Discount/Waiver</div>
                        <div class="text-2xl font-bold text-gray-800">₹ {{ number_format($total_discount_given ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="text-purple-200"><i class="fas fa-hand-holding-heart text-4xl"></i></div>
                </div>

                @php
                    $is_advance = $net_balance < 0;
                    $display_amount = abs($net_balance);
                @endphp
                <div
                    class="{{ $is_advance ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200' }} p-4 rounded-lg border flex justify-between items-center">
                    <div>
                        <div
                            class="text-xs {{ $is_advance ? 'text-yellow-700' : 'text-red-600' }} font-bold uppercase tracking-wider">
                            {{ $is_advance ? 'Advance Available' : 'Current Net Due' }}
                        </div>
                        <div class="text-2xl font-bold {{ $is_advance ? 'text-yellow-800' : 'text-red-700' }}">
                            ₹ {{ number_format($display_amount, 2) }}
                        </div>
                    </div>
                    <div class="text-xs text-right mt-1 opacity-60">
                        (Bill - Paid - Disc)
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-lg shadow border border-gray-200 h-full">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700"><i class="fas fa-pills mr-2 text-gray-400"></i> Bill History</h3>
                    <span
                        class="text-xs bg-white border border-gray-300 px-2 py-1 rounded text-gray-500">{{ $ipdPatient->medicineBills->count() }}
                        Bills</span>
                </div>
                <div class="overflow-y-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Date</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Bill No</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Amount</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($ipdPatient->medicineBills as $bill)
                                @php
                                    $isPaid = ($bill->paid_cash + $bill->paid_online + $bill->discount) >= ($bill->net_amount - 0.1);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-M-Y') }}</td>
                                    <td class="px-4 py-3 font-medium text-blue-600">#{{ $bill->bill_number }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-800">
                                        ₹{{ number_format($bill->net_amount, 2) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($isPaid)
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Paid</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('admin.medicine.bill.show', $bill->id) }}" target="_blank"
                                            class="text-gray-500 hover:text-blue-600" title="View Bill">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 h-full">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700"><i class="fas fa-history mr-2 text-gray-400"></i> Payment History
                    </h3>
                    <span
                        class="text-xs bg-white border border-gray-300 px-2 py-1 rounded text-gray-500">{{ $ipdPatient->pharmacyPayments->count() }}
                        Trans.</span>
                </div>
                <div class="overflow-y-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Date</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Mode</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Paid</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($ipdPatient->pharmacyPayments->sortByDesc('created_at') as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">{{ $payment->payment_mode }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-green-600">
                                        ₹{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.medicine.payment.print', $payment->id) }}" target="_blank"
                                                class="text-indigo-500 hover:text-indigo-700" title="Print Receipt">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button 
                                                class="text-blue-500 hover:text-blue-700 edit-payment-btn" 
                                                title="Edit Payment"
                                                data-id="{{ $payment->id }}"
                                                data-amount="{{ $payment->amount }}"
                                                data-mode="{{ $payment->payment_mode }}"
                                                data-note="{{ $payment->note }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deletePayment({{ $payment->id }})"
                                                class="text-red-500 hover:text-red-700" title="Delete Payment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="paymentModal"
        class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-[9999] backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-96 p-6 transform transition-all scale-100">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-bold text-lg text-gray-800">Receive Payment</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <form onsubmit="submitPayment(event)">
                @csrf
                <input type="hidden" name="ipd_id" value="{{ $ipdPatient->id }}">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Paying Amount
                        (₹)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">₹</span>
                        <input type="number" step="0.01" name="amount"
                            class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg font-bold text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800"
                            placeholder="0.00" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 ml-1">You can accept Advance payment too.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Payment Mode</label>
                    <select name="payment_mode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500">
                        <option value="Cash">Cash</option>
                        <option value="Online">Online (UPI/Card/Bank)</option>
                        <option value="Discount">Discount</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Note</label>
                    <input type="text" name="note" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                        placeholder="Optional remark (e.g. Transaction ID)">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg flex justify-center items-center gap-2">
                    <i class="fas fa-check-circle"></i> Confirm & Print Receipt
                </button>
            </form>
        </div>
    </div>

    {{-- Edit Payment Modal --}}
    <div id="editPaymentModal"
        class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-[9999] backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-96 p-6 transform transition-all scale-100">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-bold text-lg text-gray-800">Edit Payment</h3>
                <button onclick="closeEditPaymentModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-lg"></i></button>
            </div>

            <form onsubmit="submitEditPayment(event)">
                @csrf
                <input type="hidden" name="payment_id" id="edit_payment_id">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Paying Amount
                        (₹)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">₹</span>
                        <input type="number" step="0.01" name="amount" id="edit_payment_amount"
                            class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg font-bold text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800"
                            placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Payment Mode</label>
                    <select name="payment_mode" id="edit_payment_mode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500">
                        <option value="Cash">Cash</option>
                        <option value="Online">Online (UPI/Card/Bank)</option>
                        <option value="Discount">Discount</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1 uppercase tracking-wide">Note</label>
                    <input type="text" name="note" id="edit_payment_note" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                        placeholder="Optional remark">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg flex justify-center items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function openPaymentModal() { $('#paymentModal').removeClass('hidden'); }
            function closePaymentModal() { $('#paymentModal').addClass('hidden'); }

            // Close modal on outside click
            $('#paymentModal').on('click', function (e) {
                if (e.target === this) closePaymentModal();
            });

            function submitPayment(e) {
                e.preventDefault();

                let btn = $(e.target).find('button[type="submit"]');
                let oldText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

                let formData = new FormData(e.target);

                $.ajax({
                    url: "{{ route('admin.medicine.ipd.pay_account') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, processData: false,
                    success: function (res) {
                        if (res.status == 1) {
                            // Open Receipt automatically
                            window.open(res.print_url, '_blank', 'width=400,height=600');
                            location.reload();
                        } else {
                            alert('Error: ' + res.message);
                            btn.prop('disabled', false).html(oldText);
                        }
                    },
                    error: function (err) {
                        alert('Server Error. Check console.');
                        console.log(err);
                        btn.prop('disabled', false).html(oldText);
                    }
                });
            }

            // Edit Payment
            $(document).on('click', '.edit-payment-btn', function() {
                let id = $(this).data('id');
                let amount = $(this).data('amount');
                let mode = $(this).data('mode');
                let note = $(this).data('note');
                
                openEditPaymentModal(id, amount, mode, note);
            });

            function openEditPaymentModal(id, amount, mode, note) {
                $('#edit_payment_id').val(id);
                $('#edit_payment_amount').val(amount);
                $('#edit_payment_mode').val(mode);
                $('#edit_payment_note').val(note);
                $('#editPaymentModal').removeClass('hidden');
            }

            function closeEditPaymentModal() {
                $('#editPaymentModal').addClass('hidden');
            }

            $('#editPaymentModal').on('click', function (e) {
                if (e.target === this) closeEditPaymentModal();
            });

            function submitEditPayment(e) {
                e.preventDefault();

                let id = $('#edit_payment_id').val();
                let btn = $(e.target).find('button[type="submit"]');
                let oldText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                let formData = new FormData(e.target);

                $.ajax({
                    url: "/admin/medicine/ipd/update-payment/" + id,
                    type: "POST",
                    data: formData,
                    contentType: false, processData: false,
                    success: function (res) {
                        if (res.status == 1) {
                            location.reload();
                        } else {
                            alert('Error: ' + res.message);
                            btn.prop('disabled', false).html(oldText);
                        }
                    },
                    error: function (err) {
                        alert('Server Error. Check console.');
                        console.log(err);
                        btn.prop('disabled', false).html(oldText);
                    }
                });
            }

            // Delete Payment
            function deletePayment(id) {
                if (confirm('Are you sure you want to delete this payment? All bills will be recalculated.')) {
                    $.ajax({
                        url: "/admin/medicine/ipd/delete-payment/" + id,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            if (res.status == 1) {
                                location.reload();
                            } else {
                                alert('Error: ' + res.message);
                            }
                        },
                        error: function (err) {
                            alert('Server Error. Check console.');
                            console.log(err);
                        }
                    });
                }
            }
        </script>
    @endpush

@endsection