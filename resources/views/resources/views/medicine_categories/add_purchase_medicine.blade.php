@extends('layouts.admin')

@section('title', 'Purchase Medicine')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Fix Select2 height to match other inputs */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
        }
        
        /* Table Row Styling */
        .row-item {
            transition: all 0.2s;
        }
        .row-item:hover {
            background-color: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto p-4">
        
        {{-- Alerts --}}
        <div class="mb-4">
            <x-alert type="success" :message="session('success')" />
            <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            <div class="msg-container"></div>
        </div>

        <form action="{{ route('admin.medicine.purchase.save') }}" method="POST" class="bg-white rounded-lg shadow-md p-6"
            id="purchaseForm" onsubmit="formSubmit2(event, this, false, 'purchaseForm')">
            @csrf
            
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Purchase Medicine</h1>
                    <p class="text-gray-600 text-sm">Add new medicine purchase record</p>
                </div>
                <button type="button" onclick="history.back()"
                    class="px-4 py-2 bg-white border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </button>
            </div>

            {{-- Supplier & Bill Details --}}
            <div class="grid md:grid-cols-2 gap-6 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Supplier Name</label>
                    <input type="text" name="supplier_name" placeholder="Enter supplier name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Bill Number</label>
                    <input type="text" name="bill_number" placeholder="Enter bill/invoice number"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>

            {{-- Medicine Table Header --}}
            <div class="mb-6">
                <div class="grid grid-cols-12 gap-2 font-bold text-xs text-gray-700 mb-3 bg-gray-100 p-3 rounded-lg uppercase tracking-wider text-center">
                    <div class="col-span-3 text-left pl-2">Medicine <span class="text-red-500">*</span></div>
                    <div class="col-span-1">Batch <span class="text-red-500">*</span></div>
                    <div class="col-span-2">Expiry <span class="text-red-500">*</span></div>
                    <div class="col-span-1">Sale Price</div>
                    <div class="col-span-1">Buy Price <span class="text-red-500">*</span></div>
                    <div class="col-span-1">Qty (Strips) <span class="text-red-500">*</span></div>
                    <div class="col-span-1">Tax %</div>
                    <div class="col-span-2">Total</div>
                </div>

                <div id="medicineRows" class="space-y-2">
                    {{-- Rows will be dynamically added here --}}
                </div>

                <button type="button" id="addRow"
                    class="mt-4 flex items-center text-white bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-plus-circle mr-2"></i> Add Medicine Row
                </button>
            </div>

            {{-- Hidden Template for Row --}}
            <template id="templateRow">
                <div class="grid grid-cols-12 gap-2 items-start row-item p-2 rounded-lg border border-gray-100 mb-2">
                    
                    {{-- Medicine Select --}}
                    <div class="col-span-3">
                        <select name="medicine_id[]" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm" required>
                            <option value="">Search Medicine...</option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}" 
                                        sale-price="{{ $medicine->selling_price }}"
                                        purchase-price="{{ $medicine->buying_price }}"
                                        strip-size="{{ $medicine->strip_quantity ?? 1 }}">
                                    {{ $medicine->medicine }} ({{ $medicine->strip_quantity ?? 1 }} pcs/strip)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Batch No --}}
                    <div class="col-span-1">
                        <input type="text" name="lot_no[]" placeholder="Batch"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm" required>
                    </div>

                    {{-- Expiry --}}
                    <div class="col-span-2">
                        <input type="month" name="expiry_date[]"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm" required>
                    </div>

                    {{-- Sale Price --}}
                    <div class="col-span-1">
                        <input type="number" name="sale_price[]" step="0.01" value="{{ $medicine->selling_price ?? '' }}"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm text-right" required>
                        <small class="text-[10px] text-gray-400">Batch Sale Rate</small>
                    </div>

                    {{-- Buy Price --}}
                    <div class="col-span-1">
                        <input type="number" name="purchase_price[]" step="0.01" value="{{ $medicine->buying_price ?? '' }}"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm text-right font-medium text-blue-600" required>
                        <small class="text-[10px] text-gray-400">Batch Buy Rate</small>
                    </div>

                    {{-- Quantity --}}
                    <div class="col-span-1">
                        <input type="number" name="quantity[]" value="1" min="1"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm text-center font-bold" required>
                        <small class="text-[10px] text-gray-500 font-medium">Strips/Packs</small>
                    </div>

                    {{-- Tax (%) --}}
                    <div class="col-span-1">
                        <input type="number" name="tax[]" step="0.01" value="0" min="0" max="100"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm text-center text-gray-500">
                    </div>

                    {{-- Total Amount & Delete --}}
                    <div class="col-span-2 flex items-center gap-2">
                        <input type="number" name="amount[]" step="0.01" value="0.00"
                            class="w-full border border-gray-300 rounded-lg px-2 py-2 text-right bg-gray-100 font-bold text-gray-800 text-sm cursor-not-allowed" readonly>
                        
                        <button type="button" class="text-red-500 hover:text-red-700 delete-row p-2 rounded-full hover:bg-red-50 transition-colors" title="Remove">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </template>

            {{-- Footer Calculation Section --}}
            <div class="grid md:grid-cols-2 gap-8 mt-8 pt-6 border-t border-gray-200">
                
                {{-- Notes --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Note / Remarks</label>
                    <textarea name="note" placeholder="Enter any notes here..." rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                {{-- Totals Box --}}
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-sm space-y-3">
                    
                    {{-- Subtotal --}}
                    <div class="flex justify-between items-center text-sm">
                        <label class="font-medium text-gray-600">Subtotal (Excl. Tax)</label>
                        <div class="font-semibold text-gray-800">
                            ₹ <span id="displaySubtotal">0.00</span>
                            <input type="hidden" name="total" value="0.00">
                        </div>
                    </div>

                    {{-- Tax Total --}}
                    <div class="flex justify-between items-center text-sm">
                        <label class="font-medium text-gray-600">Total Tax</label>
                        <div class="font-semibold text-blue-600">
                            + ₹ <span id="displayTax">0.00</span>
                            <input type="hidden" name="tax_amount" value="0.00">
                        </div>
                    </div>

                    {{-- Discount --}}
                    <div class="flex justify-between items-center text-sm">
                        <label class="font-medium text-gray-600">Discount</label>
                        <div class="flex items-center w-32">
                            <span class="mr-2 text-gray-500 font-bold">- ₹</span>
                            <input type="number" name="discount" value="0.00" min="0" step="0.01"
                                class="border border-gray-300 rounded px-2 py-1 w-full text-right text-sm font-medium text-red-600">
                        </div>
                    </div>

                    <div class="border-t border-gray-300 my-2"></div>

                    {{-- Net Amount --}}
                    <div class="flex justify-between items-center">
                        <label class="text-base font-bold text-gray-900">Net Payable Amount</label>
                        <div class="text-xl font-bold text-purple-700">
                            ₹ <span id="displayNet">0.00</span>
                            <input type="hidden" name="net_amount" value="0.00">
                        </div>
                    </div>

                    {{-- Payment Details --}}
                    <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-dashed border-gray-300">
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Payment Mode <span class="text-red-500">*</span></label>
                            <select name="payment_type" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm bg-white" required>
                                <option value="1">Cash</option>
                                <option value="2">Credit Card</option>
                                <option value="3">Debit Card</option>
                                <option value="4">UPI</option>
                                <option value="5">Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Payment Note</label>
                            <input type="text" name="payment_note"
                                class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm"
                                placeholder="Txn ID / Ref">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-4 mt-8 pt-4 border-t border-gray-200">
                <button type="button" onclick="history.back()"
                    class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit"
                    class="px-8 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all shadow-md hover:shadow-lg font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Save Purchase
                </button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Initialize first row
            addNewRow();

            // 2. Add Row Button Event
            document.getElementById('addRow').addEventListener('click', addNewRow);

            // 3. Delete Row Event (Delegated)
            document.getElementById('medicineRows').addEventListener('click', (e) => {
                if (e.target.closest('.delete-row')) {
                    const container = document.getElementById('medicineRows');
                    if (container.querySelectorAll('.row-item').length > 1) {
                        e.target.closest('.row-item').remove();
                        calculateTotals();
                    } else {
                        alert("At least one medicine is required.");
                    }
                }
            });

            // 4. Input Change Events (Calculations)
            document.getElementById('medicineRows').addEventListener('input', function(e) {
                const target = e.target;
                const row = target.closest('.row-item');
                if (!row) return;

                if (target.name === 'quantity[]' || target.name === 'purchase_price[]' || target.name === 'tax[]') {
                    calculateRowAmount(row);
                    calculateTotals();
                }
            });

            // 5. Global Discount Event
            document.querySelector('input[name="discount"]').addEventListener('input', calculateTotals);
        });

        // --- Function to Add New Row ---
        function addNewRow() {
            const template = document.getElementById('templateRow');
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.row-item'); // Get div
            
            document.getElementById('medicineRows').appendChild(row);
            
            // Initialize Select2 on the new select element
            initializeSelect2(row);
        }

        // --- Initialize Select2 ---
        function initializeSelect2(rowElement) {
            $(rowElement).find('select[name="medicine_id[]"]').select2({
                placeholder: "Search Medicine...",
                width: '100%',
                dropdownParent: $('body') // Prevent overflow issues
            });
        }

        // --- Select2 Change Event (Auto-fill Prices) ---
        $(document).on('select2:select', 'select[name="medicine_id[]"]', function(e) {
            const selectedOption = e.params.data.element;
            const row = $(this).closest('.row-item')[0]; // Native DOM element

            if (selectedOption) {
                const salePrice = selectedOption.getAttribute('sale-price') || 0;
                const purchasePrice = selectedOption.getAttribute('purchase-price') || 0;

                row.querySelector('input[name="sale_price[]"]').value = parseFloat(salePrice).toFixed(2);
                row.querySelector('input[name="purchase_price[]"]').value = parseFloat(purchasePrice).toFixed(2);
                
                calculateRowAmount(row);
                calculateTotals();
            }
        });

        // --- Calculate Single Row Total ---
        function calculateRowAmount(row) {
            const price = parseFloat(row.querySelector('input[name="purchase_price[]"]').value) || 0;
            const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const taxRate = parseFloat(row.querySelector('input[name="tax[]"]').value) || 0;
            
            // Base = Price * Qty
            const baseAmount = price * quantity;
            
            // Tax = Base * (Tax / 100)
            const taxAmount = baseAmount * (taxRate / 100);
            
            // Total = Base + Tax
            const totalRowAmount = baseAmount + taxAmount;
            
            row.querySelector('input[name="amount[]"]').value = totalRowAmount.toFixed(2);
        }

        // --- Global Totals Calculation ---
        function calculateTotals() {
            const rows = document.querySelectorAll('#medicineRows .row-item');
            let subtotal = 0;
            let totalTax = 0;
            
            rows.forEach(row => {
                const price = parseFloat(row.querySelector('input[name="purchase_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
                const taxRate = parseFloat(row.querySelector('input[name="tax[]"]').value) || 0;

                const baseLineAmount = price * quantity;
                const lineTax = baseLineAmount * (taxRate / 100);

                subtotal += baseLineAmount;
                totalTax += lineTax;
            });

            const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
            const netAmount = subtotal + totalTax - discount;

            // Update Hidden Inputs
            document.querySelector('input[name="total"]').value = subtotal.toFixed(2);
            document.querySelector('input[name="tax_amount"]').value = totalTax.toFixed(2);
            document.querySelector('input[name="net_amount"]').value = netAmount.toFixed(2);

            // Update Display Text
            document.getElementById('displaySubtotal').innerText = subtotal.toFixed(2);
            document.getElementById('displayTax').innerText = totalTax.toFixed(2);
            document.getElementById('displayNet').innerText = netAmount.toFixed(2);
        }
    </script>
@endpush