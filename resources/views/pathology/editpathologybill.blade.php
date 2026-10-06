@extends('layouts.admin')

@section('title', 'Edit Pathology Bill')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
            font-size: 0.875rem;
            color: #374151;
            width: 100%;
        }
        .table-input {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            width: 100%;
            transition: border-color 0.15s;
        }
        .table-input:focus {
            border-color: #7c3aed;
            outline: none;
            box-shadow: 0 0 0 1px #7c3aed;
        }
    </style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen p-6">
    
    <div class="max-w-7xl mx-auto mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="ph ph-pencil-simple text-purple-600"></i> Edit Bill #{{ $bill->bill_no }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Update billing details and payment information.</p>
        </div>
        <a href="{{ route('admin.pathology.bill.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="ph ph-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="max-w-7xl mx-auto">
        <form id="pathology_bill_form" method="post" action="{{ route('admin.pathology.bill.update', $bill->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @csrf
            <div class="px-6 pt-6">
                <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            </div>

            <div class="p-6 space-y-8">
                
                <div class="bg-purple-50/50 p-6 rounded-xl border border-purple-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b border-gray-200 pb-2">Patient Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Select Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" id="patientSelect" class="w-full" required>
                                <option value="" disabled>Search Patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $bill->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Referral Doctor</label>
                            <select name="doctor_id" id="doctorSelect" class="w-full">
                                <option value="" selected disabled>Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $bill->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Bill Date</label>
                            <input type="datetime-local" name="bill_date" id="bill_date" value="{{ \Carbon\Carbon::parse($bill->bill_date)->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide border-l-4 border-purple-600 pl-2">Test Details</h3>
                        <button type="button" onclick="addTestRow()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-md hover:bg-indigo-700 shadow-sm transition-colors">
                            <i class="ph ph-plus mr-1"></i> Add Another Test
                        </button>
                    </div>

                    <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase w-[30%]">Test Name <span class="text-red-500">*</span></th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase w-[10%]">Report Days</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase w-[15%]">Report Date</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase w-[15%]">Price (₹)</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase w-[10%]">Tax (%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase w-[5%]">Action</th>
                                </tr>
                            </thead>
                            <tbody id="testRows" class="bg-white divide-y divide-gray-100">
                                </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-2">
                    
                    <div class="lg:col-span-7">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Internal Note / Remarks</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm placeholder-gray-400">{{ $bill->notes }}</textarea>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm">
                            
                            <div class="flex justify-between items-center mb-3 text-sm">
                                <span class="text-gray-600 font-medium">Subtotal</span>
                                <span class="font-bold text-gray-800" id="subtotal">₹0.00</span>
                            </div>

                            <div class="flex justify-between items-center mb-3 text-sm">
                                <span class="text-gray-600 font-medium">Total Tax</span>
                                <span class="font-medium text-gray-800" id="taxAmount">₹0.00</span>
                                <input type="hidden" name="tax" id="taxInput" value="{{ $bill->tax }}">
                            </div>

                            <div class="flex justify-between items-center mb-3 text-sm">
                                <span class="text-gray-600 font-medium">Discount (₹)</span>
                                <input type="number" name="discount" id="discountInput" step="0.01" value="{{ $bill->discount }}" class="w-24 px-2 py-1 text-right border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 font-bold text-gray-700">
                            </div>

                            <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-300 mb-4">
                                <span class="text-base font-extrabold text-gray-800">Net Amount</span>
                                <span class="text-xl font-extrabold text-purple-700" id="totalAmount">₹{{ number_format($bill->amount, 2) }}</span>
                                <input type="hidden" name="amount" id="totalAmountInput" value="{{ $bill->amount }}">
                            </div>

                            <div class="bg-white p-4 rounded-lg border border-gray-200 space-y-3 shadow-sm">
                                <div>
                                    <label class="block text-xs font-bold text-green-700 uppercase mb-1">Total Paid (Cumulative)</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                                        <input type="number" name="paid_amount" id="paidAmountInput" step="0.01" min="0" value="{{ $bill->paid_amount }}" class="w-full pl-8 pr-4 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 font-bold text-gray-900">
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1">Includes all previous payments. Increase this amount to add a new payment.</p>
                                </div>

                                <div class="flex justify-between items-center py-2 bg-red-50 px-3 rounded-md border border-red-100">
                                    <span class="text-xs font-bold text-red-700 uppercase">Balance Due</span>
                                    <span class="text-lg font-bold text-red-600" id="dueAmountDisplay">₹{{ number_format($bill->due_amount, 2) }}</span>
                                    <input type="hidden" name="due_amount" id="dueAmountInput" value="{{ $bill->due_amount }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Payment Mode (For New Amount)</label>
                                    <select name="payment_mode_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:bg-white" required>
                                        <option value="Cash" {{ $bill->payment_mode_id == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="UPI" {{ $bill->payment_mode_id == 'UPI' ? 'selected' : '' }}>UPI / Digital</option>
                                        <option value="Credit Card" {{ $bill->payment_mode_id == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="Debit Card" {{ $bill->payment_mode_id == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="Bank Transfer" {{ $bill->payment_mode_id == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.pathology.bill.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white font-bold rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all transform active:scale-95">
                        Update Bill
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let testRowCount = 0;
    
    // Existing Data
    const existingTests = @json($selectedTests);
    const allTestOptions = @json($test_names);

    $(document).ready(function() {
        // Initialize Select2
        $('#patientSelect').select2({ placeholder: "Search Patient...", allowClear: true, width: '100%' });
        $('#doctorSelect').select2({ placeholder: "Select Referral Doctor", allowClear: true, width: '100%' });

        // Load Existing Rows
        if (existingTests && existingTests.length > 0) {
            existingTests.forEach(test => addTestRow(test));
        } else {
            addTestRow();
        }

        // Calculation Events
        $('#discountInput, #paidAmountInput').on('input', calculateBill);
    });

    // Helper: Initialize Select logic for dynamic rows
    function initializeTestSelect(selectElement) {
        selectElement.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const row = this.closest('tr');

            // Auto-fill Data
            if (selectedOption.dataset.amount) row.querySelector('.test-amount').value = selectedOption.dataset.amount;
            if (selectedOption.dataset.report_days) row.querySelector('.report-days').value = selectedOption.dataset.report_days;
            if (selectedOption.dataset.tax) row.querySelector('.test-tax').value = selectedOption.dataset.tax;

            // Date Calc
            const reportDays = parseInt(row.querySelector('.report-days').value) || 0;
            if (reportDays >= 0) {
                const today = new Date();
                today.setDate(today.getDate() + reportDays);
                row.querySelector('.report-date').value = today.toISOString().split('T')[0];
            }
            calculateBill();
        });
    }

    function addTestRow(data = null) {
        const testRows = document.getElementById('testRows');
        const newRow = document.createElement('tr');
        newRow.className = 'hover:bg-gray-50 transition-colors';

        // Pre-fill data if editing
        const testId = data ? data.test_id : '';
        const amount = data ? data.amount : '';
        const tax = data ? data.tax : '';
        let reportDays = '';
        let reportDate = '';

        if (data) {
            const matchedTest = allTestOptions.find(t => t.id == data.test_id);
            if (matchedTest) reportDays = matchedTest.report_days;
            // Calculate date logic if needed or fetch stored date
        }

        newRow.innerHTML = `
            <td class="px-4 py-3">
                <select name="tests[${testRowCount}][test_id]" class="test-select w-full table-input" required>
                    <option value="" disabled ${!testId ? 'selected' : ''}>Select Test</option>
                    @foreach($test_names as $test)
                        <option value="{{ $test->id }}" 
                                data-amount="{{ $test->standard_charge }}"
                                data-report_days="{{ $test->report_days }}"
                                data-tax="{{ $test->tax }}"
                                ${testId == {{ $test->id }} ? 'selected' : ''}>
                            {{ $test->test_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-3">
                <input type="text" name="tests[${testRowCount}][report_days]" value="${reportDays}" class="report-days table-input bg-gray-50 text-center" readonly tabindex="-1">
            </td>
            <td class="px-4 py-3">
                <input type="date" name="tests[${testRowCount}][report_date]" class="report-date table-input">
            </td>
            <td class="px-4 py-3">
                <input type="number" step="0.01" name="tests[${testRowCount}][amount]" value="${amount}" class="test-amount table-input font-bold text-gray-800 text-right" placeholder="0.00" required oninput="calculateBill()">
            </td>
            <td class="px-4 py-3">
                <input type="number" step="0.01" name="tests[${testRowCount}][tax]" value="${tax}" class="test-tax table-input text-center text-gray-500" placeholder="0" oninput="calculateBill()">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="removeTestRow(this)" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="ph ph-trash text-lg"></i>
                </button>
            </td>
        `;
        testRows.appendChild(newRow);
        initializeTestSelect(newRow.querySelector('.test-select'));
        testRowCount++;
        calculateBill();
    }

    function removeTestRow(button) {
        const row = button.closest('tr');
        if (document.querySelectorAll('#testRows tr').length > 1) {
            row.remove();
        } else {
            row.querySelectorAll('input').forEach(i => i.value = '');
            row.querySelector('select').selectedIndex = 0;
        }
        calculateBill();
    }

    function calculateBill() {
        let subtotal = 0;
        let totalTaxAmount = 0;

        document.querySelectorAll('#testRows tr').forEach(row => {
            const amount = parseFloat(row.querySelector('.test-amount').value) || 0;
            const taxRate = parseFloat(row.querySelector('.test-tax').value) || 0;
            subtotal += amount;
            totalTaxAmount += amount * (taxRate / 100);
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        let netAmount = subtotal + totalTaxAmount - discount;
        if(netAmount < 0) netAmount = 0;

        // Update UI
        document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
        document.getElementById('taxAmount').textContent = `₹${totalTaxAmount.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `₹${netAmount.toFixed(2)}`;

        // Update Hidden Fields
        document.getElementById('totalAmountInput').value = netAmount.toFixed(2);
        document.getElementById('taxInput').value = totalTaxAmount.toFixed(2);

        // Due Logic
        const paidAmount = parseFloat(document.getElementById('paidAmountInput').value) || 0;
        let dueAmount = netAmount - paidAmount;
        if(dueAmount < 0) dueAmount = 0;

        document.getElementById('dueAmountDisplay').textContent = `₹${dueAmount.toFixed(2)}`;
        document.getElementById('dueAmountInput').value = dueAmount.toFixed(2);
    }
</script>
@endpush