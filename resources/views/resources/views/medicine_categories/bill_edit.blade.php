@extends('layouts.admin')

@section('title', 'Edit Medicine Bill')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom styles for tighter form layout */
        .select2-container .select2-selection--single {
            height: 34px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem;
        }
        .select2-selection__rendered {
            line-height: 34px !important;
            font-size: 13px !important;
            padding-left: 10px !important;
        }
        .select2-selection__arrow {
            height: 32px !important;
        }
        .form-input-sm {
            padding: 4px 8px;
            font-size: 13px;
            height: 32px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        .bg-readonly { background: #f3f4f6; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
@endpush

@section('content')
    <div class="max-w-[98%] mx-auto py-4">

        {{-- Use standard form submission or AJAX as per your preference --}}
        <form method="POST" action="{{ route('admin.medicine.bill.update', $bill->id) }}"
              class="bg-white rounded-lg shadow border border-gray-200"
              id="editBillForm">
            @csrf
            
            {{-- HEADER --}}
            <div class="p-5 border-b bg-gray-50 rounded-t-lg">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    
                    {{-- Patient --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Patient Name *</label>
                        <select name="patient_select" class="patient-select w-full" required>
                            <option value="">Search or Type Patient Name...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $bill->patient_id == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} - {{ $patient->patientProfile->patient_unique_id ?? '' }}
                                </option>
                            @endforeach
                            
                            {{-- Handle Custom Name --}}
                            @if(is_null($bill->patient_id) && !empty($bill->patient_name))
                                <option value="{{ $bill->patient_name }}" selected>{{ $bill->patient_name }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Bill Date *</label>
                        <input type="datetime-local" name="bill_date"
                               value="{{ \Carbon\Carbon::parse($bill->bill_date)->format('Y-m-d\TH:i') }}"
                               class="w-full border-gray-300 rounded text-sm py-1.5 px-3 focus:ring-indigo-500 focus:border-indigo-500" required />
                    </div>

                    <div class="md:col-span-5 text-right">
                        <div class="inline-block bg-yellow-50 px-4 py-2 rounded border border-yellow-200">
                            <h2 class="text-base font-bold text-yellow-800 tracking-wide">EDIT INVOICE #{{ $bill->bill_number }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MEDICINE TABLE --}}
            <div class="p-4">
                <div class="border rounded-lg overflow-hidden shadow-sm">
                    <table class="min-w-full">
                        <thead class="bg-gray-100 text-xs font-bold text-gray-600 uppercase">
                            <tr>
                                <th class="px-3 py-2 text-left w-[22%]">Medicine</th>
                                <th class="px-2 py-2 text-left w-[12%]">Expiry</th>
                                <th class="px-2 py-2 w-[10%]">Unit</th>
                                <th class="px-2 py-2 text-right w-[10%]">Price</th>
                                <th class="px-2 py-2 text-center w-[8%]">Qty</th>
                                <th class="px-2 py-2 text-center w-[8%] text-indigo-600">Disc %</th>
                                <th class="px-2 py-2 text-center w-[8%]">Tax %</th>
                                <th class="px-3 py-2 text-right w-[12%]">Amount</th>
                                <th class="px-2 py-2 text-center w-[5%]"><i class="fas fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="medicine-rows" class="divide-y divide-gray-200 bg-white">
                            {{-- Rows will be populated via JS --}}
                        </tbody>
                    </table>
                </div>

                <button type="button" id="add-row" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow-sm flex items-center gap-2 transition-all">
                    <i class="fas fa-plus"></i> Add Another Medicine
                </button>
            </div>

            {{-- FOOTER / CALCULATIONS --}}
            <div class="grid grid-cols-12 gap-8 p-5 bg-gray-50 border-t rounded-b-lg">
                
                {{-- Left Side --}}
                <div class="col-span-7 space-y-5">
                    <div class="bg-white p-4 rounded border border-gray-200 shadow-sm">
                        <h4 class="text-xs font-bold text-gray-700 border-b pb-2 mb-3 uppercase tracking-wider">Payment Split</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold">Paid Cash</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₹</span>
                                    <input type="number" step="0.01" name="paid_cash" value="{{ $bill->paid_cash }}" class="paid-cash-input w-full border rounded pl-6 pr-2 py-1.5 text-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold">Paid Online</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₹</span>
                                    <input type="number" step="0.01" name="paid_online" value="{{ $bill->paid_online }}" class="paid-online-input w-full border rounded pl-6 pr-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="text-xs text-gray-600 font-semibold">Online Mode</label>
                            <select name="online_payment_type" class="w-full border rounded px-2 py-1.5 text-sm mt-1">
                                <option value="">Select Method</option>
                                <option value="UPI" {{ $bill->online_payment_type == 'UPI' ? 'selected' : '' }}>UPI / GPay</option>
                                <option value="Card" {{ $bill->online_payment_type == 'Card' ? 'selected' : '' }}>Card</option>
                                <option value="Bank" {{ $bill->online_payment_type == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-600">Note</label>
                        <textarea name="note" rows="2" class="w-full border-gray-300 rounded mt-1 text-sm p-2 focus:ring-indigo-500">{{ $bill->note }}</textarea>
                    </div>
                </div>

                {{-- Right Side (Totals) --}}
                <div class="col-span-5">
                    <div class="bg-white p-5 rounded border border-gray-200 shadow-sm space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 font-medium">Sub Total</span>
                            <input type="text" name="total" class="total-amount w-32 text-right bg-transparent font-bold text-gray-800 border-none p-0 focus:ring-0" readonly value="{{ $bill->total }}">
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 font-medium">Total Tax</span>
                            <input type="text" name="tax_amount" class="tax-total-input w-32 text-right bg-transparent text-gray-600 border-none p-0 focus:ring-0" readonly value="{{ $bill->tax_amount }}">
                        </div>
                        <div class="flex justify-between items-center text-sm" style="visibility:hidden">
                            <span class="text-gray-600 font-medium">Extra Discount (₹)</span>
                            <input type="number" step="0.01" name="discount" class="discount-global-input w-32 text-right border border-gray-300 px-2 py-1 rounded text-red-600 focus:ring-red-500" value="{{ $bill->discount }}">
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-2 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-800">Net Payable</span>
                            <input type="text" name="net_amount" class="net-amount w-40 text-right text-2xl font-extrabold text-indigo-700 bg-transparent border-none p-0 focus:ring-0" readonly value="{{ $bill->net_amount }}">
                        </div>
                        <div class="bg-gray-100 p-3 rounded flex justify-between items-center mt-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Balance Due</span>
                            <span class="balance-due text-lg font-bold text-red-600">0.00</span>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('admin.medicine.bill.index') }}" class="w-1/3 py-2.5 border border-gray-300 rounded text-sm font-medium text-gray-600 hover:bg-gray-50 text-center">Cancel</a>
                            <button type="submit" class="w-2/3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm font-bold shadow-md transition-all flex justify-center items-center gap-2">
                                <i class="fas fa-save"></i> Update Bill
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- TEMPLATE ROW (Hidden) --}}
        <table class="hidden">
            <tr id="template-row" class="medicine-row">
                <td class="px-2 py-2">
                    <select class="medicine-dropdown w-full form-input-sm" name="sale_medicines[0][medicine_id]">
                        <option value="">Search medicine...</option>
                    </select>
                    <small class="text-[10px] text-orange-500 hidden negative-warning">⚠️ Negative Sale</small>
                    <input type="hidden" class="strip-size-input" value="1">
                    <input type="hidden" class="base-price-input" value="0">
                    <input type="hidden" class="total-stock-input" value="0">
                    <input type="hidden" class="strip-stock-input" value="0">
                </td>
                <td class="px-2 py-2">
                    <input type="text" name="sale_medicines[0][expiry_date]" class="form-input-sm w-full bg-readonly expiry-input text-gray-900 font-semibold" readonly style="border: 1px solid #d1d5db; background: #ffffff;" />
                </td>
                <td class="px-2 py-2">
                    <select name="sale_medicines[0][unit]" class="form-input-sm w-full unit-dropdown bg-white focus:ring-1 focus:ring-indigo-500">
                        <option value="piece" selected>Piece</option>
                        <option value="strip">Strip</option>
                    </select>
                </td>
                <td class="px-2 py-2">
                    <input type="number" step="0.01" name="sale_medicines[0][price]" class="form-input-sm w-full text-right bg-readonly price-input font-medium" readonly />
                </td>
                <td class="px-2 py-2">
                    <input type="number" name="sale_medicines[0][quantity]" value="1" min="1" class="form-input-sm w-full text-center quantity-input font-bold text-gray-800" />
                    <div class="text-[10px] text-center mt-1">Stock: <span class="available-quantity font-bold text-blue-600">0</span></div>
                </td>
                <td class="px-2 py-2">
                    <input type="number" step="0.01" name="sale_medicines[0][discount_percent]" value="0" class="form-input-sm w-full text-center discount-percent-input text-indigo-600" />
                </td>
                <td class="px-2 py-2">
                    <input type="number" step="0.01" name="sale_medicines[0][tax]" value="0" class="form-input-sm w-full text-center tax-input" />
                </td>
                <td class="px-2 py-2">
                    <input type="number" step="0.01" name="sale_medicines[0][amount]" value="0.00" class="form-input-sm w-full text-right bg-gray-50 amount-input font-bold text-gray-900" readonly />
                </td>
                <td class="px-2 py-2 text-center">
                    <button type="button" class="remove-row text-gray-400 hover:text-red-500 transition-colors p-1">
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                </td>
            </tr>
        </table>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const existingItems = @json($bill->saleMedicine);

        $(document).ready(function () {

            // 1. Patient Select2
            $('.patient-select').select2({
                placeholder: "Search existing or type new name...",
                allowClear: true,
                tags: true,
                width: '100%',
                createTag: function (params) {
                    var term = params.term;
                    if (term === '') { return null; }
                    return { id: term, text: term, newTag: true }
                }
            });

            // 2. Load Existing Items
            if (existingItems.length > 0) {
                existingItems.forEach((item, index) => {
                    addExistingRow(item, index);
                });
                reIndexRows(); // Ensure correct indexing
            } else {
                addNewRow();
            }

            calculateTotals();
            updateBalance();
        });

        // --- Select2 Helper ---
        function loadMedicineDropdown(drop, selectedId = null, selectedText = null) {
            // Remove old instance if exists
            if (drop.hasClass("select2-hidden-accessible")) {
                drop.next('.select2').remove();
                drop.removeClass('select2-hidden-accessible');
                drop.find('option').remove();
            }

            if (selectedId && selectedText) {
                drop.html(`<option value="${selectedId}" selected>${selectedText}</option>`);
            } else {
                drop.html(`<option value="">Search medicine...</option>`);
            }

            drop.select2({
                placeholder: "Search medicine...",
                width: "100%",
                dropdownAutoWidth: true,
                ajax: {
                    url: "/admin/medicine/all",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.map(function (m) {
                                return { id: m.id, text: m.medicine };
                            })
                        };
                    }
                },
                allowClear: true
            });
        }

        // --- Add Existing Row ---
        function addExistingRow(item, index) {
            let newRow = $('#template-row').clone().removeAttr('id');
            
            // Fill Data
            let md = newRow.find('.medicine-dropdown');
            loadMedicineDropdown(md, item.medicine_id, item.medicine ? item.medicine.medicine : 'Unknown');

            // Stock/Price Info (Fetched from DB relation)
            let basePrice = parseFloat(item.medicine.selling_price || 0);
            let stripSize = parseInt(item.medicine.strip_quantity) || 1;
            let totalStock = item.medicine.total_quantity || 0;

            newRow.find('.base-price-input').val(basePrice);
            newRow.find('.strip-size-input').val(stripSize);
            newRow.find('.total-stock-input').val(totalStock);
            
            newRow.find('.expiry-input').val(item.expiry_date || '');
            newRow.find('.discount-percent-input').val(item.discount_percent || 0);
            newRow.find('.tax-input').val(item.tax || 0);
            newRow.find('.amount-input').val(parseFloat(item.amount).toFixed(2));

            // Unit & Quantity handling
            let unitType = item.unit_type || 'piece';
            let qty = parseFloat(item.quantity) || 1;
            newRow.find('.unit-dropdown').val(unitType);
            newRow.find('.quantity-input').val(qty);

            if (unitType === 'strip') {
                newRow.find('.price-input').val(basePrice.toFixed(2));
                let strips = stripSize > 0 ? Math.floor(totalStock / stripSize) : 0;
                newRow.find('.available-quantity').text(strips + " Strips");
            } else {
                let unitPrice = stripSize > 0 ? (basePrice / stripSize) : basePrice;
                newRow.find('.price-input').val(unitPrice.toFixed(2));
                newRow.find('.available-quantity').text(totalStock + " Pcs");
            }

            $('#medicine-rows').append(newRow);
        }

        // --- Add New Row (Blank) ---
        function addNewRow() {
            let newRow = $('#template-row').clone().removeAttr('id');
            
            // Reset fields
            newRow.find("input").val("");
            newRow.find('.quantity-input').val("1");
            newRow.find('.discount-percent-input').val("0");
            newRow.find('.tax-input').val("0");
            newRow.find('.amount-input').val("0.00");
            newRow.find('.available-quantity').text("0");
            
            // Ensure Unit is Piece
            newRow.find('.unit-dropdown').val('piece');

            // Init Select2
            let md = newRow.find('.medicine-dropdown');
            loadMedicineDropdown(md);

            $('#medicine-rows').append(newRow);
            reIndexRows();
        }

        $('#add-row').on('click', addNewRow);

        // --- Remove Row ---
        $(document).on('click', '.remove-row', function () {
            if ($('#medicine-rows .medicine-row').length > 1) {
                $(this).closest('.medicine-row').remove();
                reIndexRows();
                calculateTotals();
            } else {
                let row = $(this).closest('.medicine-row');
                row.find('input').not('.quantity-input').val('');
                row.find('.quantity-input').val(1);
                row.find('.medicine-dropdown').val(null).trigger('change');
            }
        });

        // --- Medicine Selection Change ---
        $(document).on('change', '.medicine-dropdown', function () {
            let id = $(this).val();
            let row = $(this).closest('.medicine-row');
            if (!id) return;

            $.get("/admin/get-medicine-details/" + id, function (data) {
                row.find('.expiry-input').val(data.expiry_date ?? '');
                row.find('.base-price-input').val(data.sale_price || 0);
                row.find('.strip-size-input').val(data.strip_quantity || 1);
                row.find('.total-stock-input').val(data.total_quantity || 0);
                row.find('.tax-input').val(data.tax !== undefined && data.tax !== null ? data.tax : 0);

                // ✅ Show warning if negative sale expected
                if (data.warning) {
                    row.find('.negative-warning').removeClass('hidden').addClass('block');
                } else {
                    row.find('.negative-warning').removeClass('block').addClass('hidden');
                }

                // Auto Select Piece & Calc Price
                let price = parseFloat(data.sale_price || 0);
                let stripSize = parseInt(data.strip_quantity || 1);
                let perPiece = stripSize > 0 ? (price / stripSize) : price;

                row.find('.unit-dropdown').val('piece');
                row.find('.price-input').val(perPiece.toFixed(2));
                row.find('.available-quantity').text((data.total_quantity || 0) + " Pcs");
                
                updateAmount(row);
            });
        });;

        // --- Unit Change (Piece / Strip) ---
        $(document).on('change', '.unit-dropdown', function () {
            let row = $(this).closest('.medicine-row');
            let unit = $(this).val();
            let basePrice = parseFloat(row.find('.base-price-input').val()) || 0;
            let stripSize = parseInt(row.find('.strip-size-input').val()) || 1;
            let totalStock = parseInt(row.find('.total-stock-input').val()) || 0;

            if (unit === 'piece') {
                let perPiece = stripSize > 0 ? (basePrice / stripSize) : basePrice;
                row.find('.price-input').val(perPiece.toFixed(2));
                row.find('.available-quantity').text(totalStock + " Pcs");
            } else {
                row.find('.price-input').val(basePrice.toFixed(2));
                let strips = stripSize > 0 ? Math.floor(totalStock / stripSize) : 0;
                row.find('.available-quantity').text(strips + " Strips");
            }
            updateAmount(row);
        });

        // --- Calculation ---
        function updateAmount(row) {
            let price = parseFloat(row.find('.price-input').val()) || 0;
            let qty = parseFloat(row.find('.quantity-input').val()) || 0;
            let tax = parseFloat(row.find('.tax-input').val()) || 0;
            let disc = parseFloat(row.find('.discount-percent-input').val()) || 0;

            let base = price * qty;
            let discAmt = base * (disc / 100);
            let taxable = base - discAmt;
            let taxAmt = taxable * (tax / 100);
            let total = taxable + taxAmt;

            row.find('.amount-input').val(total.toFixed(2));
            calculateTotals();
        }

        $(document).on('input', '.quantity-input, .discount-percent-input, .tax-input', function () {
            updateAmount($(this).closest('.medicine-row'));
        });

        function calculateTotals() {
            let subtotal = 0;
            let totalTax = 0;

            $('#medicine-rows .medicine-row').each(function () {
                let price = parseFloat($(this).find('.price-input').val()) || 0;
                let qty = parseFloat($(this).find('.quantity-input').val()) || 0;
                let disc = parseFloat($(this).find('.discount-percent-input').val()) || 0;
                let taxRate = parseFloat($(this).find('.tax-input').val()) || 0;

                let base = price * qty;
                let taxable = base - (base * (disc / 100));
                let taxVal = taxable * (taxRate / 100);

                subtotal += taxable;
                totalTax += taxVal;
            });

            let discount = parseFloat($('.discount-global-input').val()) || 0;
            let net = subtotal + totalTax - discount;
            if(net < 0) net = 0;

            $('.total-amount').val(subtotal.toFixed(2));
            $('.tax-total-input').val(totalTax.toFixed(2));
            $('.net-amount').val(net.toFixed(2));
            updateBalance();
        }

        $(document).on('input', '.discount-global-input', calculateTotals);

        // --- Balance ---
        function updateBalance() {
            let net = parseFloat($('.net-amount').val()) || 0;
            let cash = parseFloat($('.paid-cash-input').val()) || 0;
            let online = parseFloat($('.paid-online-input').val()) || 0;
            let balance = net - (cash + online);

            if (balance <= 0.01 && balance >= -0.01) {
                $('.balance-due').text("CLEARED").removeClass('text-red-600').addClass('text-green-600');
            } else {
                $('.balance-due').text("₹ " + balance.toFixed(2)).removeClass('text-green-600').addClass('text-red-600');
            }
        }

        $(document).on('input', '.paid-cash-input, .paid-online-input', updateBalance);

        // --- Re-Indexer (Important for Save) ---
        function reIndexRows() {
            $('#medicine-rows .medicine-row').each(function(index) {
                $(this).find('select, input').each(function() {
                    let name = $(this).attr('name');
                    if(name) {
                        $(this).attr('name', name.replace(/sale_medicines\[\d+\]/, `sale_medicines[${index}]`));
                    }
                });
            });
        }

        // --- AJAX Submit (Save & Print) ---
        $('#editBillForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status_code === 1) {
                        alert(res.message);
                        if(res.print_url) {
                            window.open(res.print_url, '_blank');
                        }
                        window.location.href = "{{ route('admin.medicine.bill.index') }}";
                    } else {
                        alert(res.message);
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(err) {
                    alert("Something went wrong!");
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

    
    </script>
@endpush