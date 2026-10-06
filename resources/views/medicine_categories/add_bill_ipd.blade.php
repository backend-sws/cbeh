@extends('layouts.admin')

@section('title', 'Create IPD Medicine Bill')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom styles for compact layout */
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
    
    /* Hide number input arrows */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>
@endpush

@section('content')

<div class="max-w-[98%] mx-auto py-4">

    {{-- Form Start --}}
    <form method="POST" action="{{ route('admin.medicine.bill.save') }}"
          class="bg-white rounded-lg shadow border border-gray-200"
          id="createIpdBillForm">

        @csrf

        {{-- CRITICAL: Hidden Fields for IPD Linking --}}
        <input type="hidden" name="ipd_patient_id" value="{{ $ipdPatient->id }}">
        <input type="hidden" name="patient_id" value="{{ $ipdPatient->patient_id }}">
        <input type="hidden" name="patient_select" value="{{ $ipdPatient->patient_id }}">

        {{-- Header Section: Patient Info (Read Only) --}}
        <div class="p-5 border-b bg-purple-50 rounded-t-lg">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-lg font-bold text-purple-800 flex items-center gap-2">
                        <i class="fas fa-file-prescription"></i> IPD Medicine Bill
                    </h2>
                    <p class="text-xs text-purple-600 mt-1">Creating bill for admitted patient</p>
                </div>
                <div class="text-right">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Bill Date *</label>
                    <input type="datetime-local" name="bill_date"
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           class="border-gray-300 rounded text-sm py-1.5 px-3 focus:ring-purple-500 focus:border-purple-500 shadow-sm"
                           required />
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg border border-purple-200 shadow-sm grid grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] uppercase text-gray-500 font-bold">Patient Name</label>
                    <div class="text-sm font-bold text-gray-800">{{ $ipdPatient->patient->name }}</div>
                </div>
                <div>
                    <label class="text-[10px] uppercase text-gray-500 font-bold">IPD Number</label>
                    <div class="text-sm font-bold text-blue-600">#{{ $ipdPatient->ipd_no }}</div>
                </div>
                <div>
                    <label class="text-[10px] uppercase text-gray-500 font-bold">Doctor</label>
                    <div class="text-sm font-medium text-gray-700">{{ $ipdPatient->doctor->name }}</div>
                </div>
                <div>
                    <label class="text-[10px] uppercase text-gray-500 font-bold">Phone</label>
                    <div class="text-sm text-gray-600">{{ $ipdPatient->patient->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        {{-- Medicine Table Section --}}
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
                        <tr class="medicine-row hover:bg-gray-50 transition-colors">
                            <td class="px-2 py-2">
                                <select class="medicine-dropdown w-full" name="sale_medicines[0][medicine_id]">
                                    <option value="">Search medicine...</option>
                                </select>
                                
                                {{-- Hidden Calculations --}}
                                <input type="hidden" class="strip-size-input" value="1">
                                <input type="hidden" class="base-price-input" value="0">
                                <input type="hidden" class="total-stock-input" value="0">
                                <input type="hidden" class="strip-stock-input" value="0">
                            </td>

                            <td class="px-2 py-2">
                                <input type="text" name="sale_medicines[0][expiry_date]" class="form-input-sm w-full bg-readonly expiry-input text-gray-900 font-semibold" readonly style="border: 1px solid #d1d5db; background: #ffffff;" />
                            </td>

                            <td class="px-2 py-2">
                                <select name="sale_medicines[0][unit]" class="form-input-sm w-full unit-dropdown bg-white focus:ring-1 focus:ring-purple-500">
                                    <option value="piece" selected>Piece</option>
                                    <option value="strip">Strip</option>
                                </select>
                            </td>

                            <td class="px-2 py-2">
                                <input type="number" step="0.01" name="sale_medicines[0][price]" class="form-input-sm w-full text-right bg-readonly price-input font-medium" readonly />
                            </td>

                            <td class="px-2 py-2">
                                <input type="number" name="sale_medicines[0][quantity]" value="1" min="1" class="form-input-sm w-full text-center quantity-input font-bold text-gray-800" />
                                <div class="text-[10px] text-center mt-1">
                                    Stock: <span class="available-quantity font-bold text-blue-600">0</span>
                                </div>
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
                    </tbody>
                </table>
            </div>

            <button type="button" id="add-row" class="mt-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded shadow-sm flex items-center gap-2 transition-all">
                <i class="fas fa-plus"></i> Add Another Medicine
            </button>
        </div>

        {{-- Footer: Payment & Totals --}}
        <div class="grid grid-cols-12 gap-8 p-5 bg-gray-50 border-t rounded-b-lg">
            
            <div class="col-span-7 space-y-5">
                <div class="bg-white p-4 rounded border border-gray-200 shadow-sm">
                    <h4 class="text-xs font-bold text-gray-700 border-b pb-2 mb-3 uppercase tracking-wider">Payment Details</h4>
                    
                    {{-- IPD Note --}}
                    <div class="bg-blue-50 text-blue-800 text-xs p-2 rounded mb-3 border border-blue-100">
                        <i class="fas fa-info-circle mr-1"></i> 
                        <strong>Note:</strong> You can leave "Paid Amount" as 0 to add this bill to the patient's IPD dues.
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold">Paid Cash</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₹</span>
                                <input type="number" step="0.01" name="paid_cash" class="paid-cash-input w-full border rounded pl-6 pr-2 py-1.5 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold">Paid Online</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500">₹</span>
                                <input type="number" step="0.01" name="paid_online" class="paid-online-input w-full border rounded pl-6 pr-2 py-1.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-xs text-gray-600 font-semibold">Online Payment Method</label>
                        <select name="online_payment_type" class="w-full border rounded px-2 py-1.5 text-sm mt-1">
                            <option value="">Select Method</option>
                            <option value="UPI">UPI / GPay / PhonePe</option>
                            <option value="Card">Credit / Debit Card</option>
                            <option value="Bank">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mt-3">
                         <label class="text-xs font-bold text-gray-600">Note</label>
                         <textarea name="note" rows="1" class="w-full border-gray-300 rounded mt-1 text-sm p-2" placeholder="Note for this bill..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-5">
                <div class="bg-white p-5 rounded border border-gray-200 shadow-sm space-y-3">
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">Sub Total</span>
                        <input type="text" name="total" class="total-amount w-32 text-right bg-transparent font-bold text-gray-800 border-none p-0 focus:ring-0" readonly value="0.00">
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 font-medium">Total Tax</span>
                        <input type="text" name="tax_amount" class="tax-total-input w-32 text-right bg-transparent text-gray-600 border-none p-0 focus:ring-0" readonly value="0.00">
                    </div>

                    <div class="flex justify-between items-center text-sm" style="visibility:hidden">
                        <span class="text-gray-600 font-medium">Extra Discount (₹)</span>
                        <input type="number" step="0.01" name="discount" class="discount-global-input w-32 text-right border border-gray-300 px-2 py-1 rounded text-red-600 focus:ring-red-500" value="0.00">
                    </div>

                    <div class="border-t border-gray-200 pt-3 mt-2 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Net Payable</span>
                        <input type="text" name="net_amount" class="net-amount w-40 text-right text-2xl font-extrabold text-purple-700 bg-transparent border-none p-0 focus:ring-0" readonly value="0.00">
                    </div>

                    <div class="bg-gray-100 p-3 rounded flex justify-between items-center mt-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Balance / Due</span>
                        <span class="balance-due text-lg font-bold text-red-600">0.00</span>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm font-bold shadow-md transition-all flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> Save Bill
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        
        // --- 1. LOAD MEDICINE DROPDOWN ---
        function loadMedicineDropdown(drop) {
            $.get("/admin/medicine/all", function(res) {
                drop.html(`<option value="">Search medicine...</option>`);
                res.forEach(m => drop.append(`<option value="${m.id}">${m.medicine}</option>`));
                
                drop.select2({
                    placeholder: "Search medicine...",
                    width: "100%",
                    dropdownAutoWidth: true
                });
            });
        }
        
        loadMedicineDropdown($('.medicine-dropdown'));

        // --- 2. FETCH MEDICINE DETAILS ---
        $(document).on('change', '.medicine-dropdown', function() {
            let id = $(this).val();
            let row = $(this).closest('.medicine-row');
            if (!id) return;

            $.get("/admin/get-medicine-details/" + id, function(res) {
                row.find('.expiry-input').val(res.expiry_date ?? "");
                row.find('.base-price-input').val(res.sale_price);
                row.find('.strip-size-input').val(res.strip_quantity ?? 1);
                row.find('.tax-input').val(res.tax !== undefined && res.tax !== null ? res.tax : 0);
                row.find('.strip-stock-input').val(res.available_quantity); 
                row.find('.total-stock-input').val(res.total_quantity);
                
                // ✅ FIXED: Allow Negative Sales - Remove max attribute to allow quantities beyond available stock
                row.find('.quantity-input').removeAttr('max');
                
                row.find('.unit-dropdown').val('piece').trigger('change');
            });
        });

        // --- 3. UNIT CHANGE & STOCK DISPLAY ---
        $(document).on("change", ".unit-dropdown", function() {
            let row = $(this).closest('.medicine-row');
            let unit = $(this).val();
            let base = parseFloat(row.find('.base-price-input').val()) || 0;
            let size = parseInt(row.find('.strip-size-input').val()) || 1;
            
            let pieces = parseInt(row.find('.total-stock-input').val());
            let strips = parseInt(row.find('.strip-stock-input').val());

            if (unit == "piece") {
                row.find('.price-input').val((base / size).toFixed(2));
                row.find('.available-quantity').text(pieces + " Pcs");
            } else {
                row.find('.price-input').val(base.toFixed(2));
                row.find('.available-quantity').text(strips + " Strips");
            }
            updateAmount(row);
        });

        // --- 4. CALCULATION LOGIC ---
        function updateAmount(row) {
            let price = parseFloat(row.find('.price-input').val()) || 0;
            let qty = parseFloat(row.find('.quantity-input').val()) || 0;
            let disc = parseFloat(row.find('.discount-percent-input').val()) || 0;
            let tax = parseFloat(row.find('.tax-input').val()) || 0;

            let base = price * qty;
            let discAmt = base * disc / 100;
            let taxable = base - discAmt;
            let taxAmt = taxable * tax / 100;
            let final = taxable + taxAmt;

            row.find('.amount-input').val(final.toFixed(2));
            calculateTotals();
        }

        $(document).on("input", ".quantity-input, .discount-percent-input, .tax-input", function() {
            updateAmount($(this).closest('.medicine-row'));
        });

        // --- 5. ADD ROW ---
        $("#add-row").click(function() {
            let newRow = $("#medicine-rows .medicine-row:first").clone();
            newRow.find("input").val("");
            newRow.find("select.unit-dropdown").val("piece");
            newRow.find('.quantity-input').val("1");
            newRow.find('.discount-percent-input').val("0");
            newRow.find('.tax-input').val("0");
            newRow.find('.amount-input').val("0.00");
            newRow.find('.available-quantity').text("0");

            let md = newRow.find('.medicine-dropdown');
            md.next('.select2').remove();
            md.removeClass('select2-hidden-accessible');
            md.find('option').remove(); 

            $("#medicine-rows").append(newRow);
            loadMedicineDropdown(md);
            reIndexRows();
        });

        // --- 6. REMOVE ROW ---
        $(document).on("click", ".remove-row", function() {
            if ($(".medicine-row").length > 1) {
                $(this).closest('.medicine-row').remove();
                reIndexRows();
                calculateTotals();
            } else {
                alert("At least one medicine is required.");
            }
        });

        // --- 7. RE-INDEX ---
        function reIndexRows() {
            $("#medicine-rows .medicine-row").each(function(index) {
                $(this).find('.medicine-dropdown').attr('name', `sale_medicines[${index}][medicine_id]`);
                $(this).find('.expiry-input').attr('name', `sale_medicines[${index}][expiry_date]`);
                $(this).find('.unit-dropdown').attr('name', `sale_medicines[${index}][unit]`);
                $(this).find('.price-input').attr('name', `sale_medicines[${index}][price]`);
                $(this).find('.quantity-input').attr('name', `sale_medicines[${index}][quantity]`);
                $(this).find('.discount-percent-input').attr('name', `sale_medicines[${index}][discount_percent]`);
                $(this).find('.tax-input').attr('name', `sale_medicines[${index}][tax]`);
                $(this).find('.amount-input').attr('name', `sale_medicines[${index}][amount]`);
            });
        }

        // --- 8. TOTALS ---
        function calculateTotals() {
            let subtotal = 0;
            let totalTax = 0;

            $(".medicine-row").each(function() {
                let price = parseFloat($(this).find('.price-input').val()) || 0;
                let qty = parseFloat($(this).find('.quantity-input').val()) || 0;
                let disc = parseFloat($(this).find('.discount-percent-input').val()) || 0;
                let taxRate = parseFloat($(this).find('.tax-input').val()) || 0;
                
                let base = price * qty;
                let taxable = base - (base * disc / 100);
                let rowTax = taxable * taxRate / 100;
                
                subtotal += taxable;
                totalTax += rowTax;
            });

            $(".total-amount").val(subtotal.toFixed(2));
            $(".tax-total-input").val(totalTax.toFixed(2));

            let discount = parseFloat($(".discount-global-input").val()) || 0;
            let net = subtotal + totalTax - discount;
            if (net < 0) net = 0;

            $(".net-amount").val(net.toFixed(2));
            updateBalance();
        }

        $(document).on("input", ".discount-global-input", function() { calculateTotals(); });

        function updateBalance() {
            let net = parseFloat($('.net-amount').val()) || 0;
            let cash = parseFloat($('.paid-cash-input').val()) || 0;
            let online = parseFloat($('.paid-online-input').val()) || 0;
            let paid = cash + online;
            let balance = net - paid;

            if (balance <= 0.05 && paid >= net - 0.05) {
                $('.balance-due').text("CLEARED").removeClass('text-red-600').addClass('text-green-600');
            } else {
                $('.balance-due').text("₹ " + balance.toFixed(2)).removeClass('text-green-600').addClass('text-red-600');
            }
        }

        $(document).on("input", ".paid-cash-input, .paid-online-input", function () { updateBalance(); });

        // --- 9. SUBMIT ---
        $('#createIpdBillForm').on('submit', function(e) {
            e.preventDefault(); 
            let btn = $(this).find('button[type="submit"]');
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status_code === 1) {
                        if(res.print_url) window.open(res.print_url, '_blank');
                        alert(res.message);
                        // Redirect wapis IPD Dashboard par ya Bills list par
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

    });
</script>
@endpush