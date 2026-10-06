<div class="bg-white p-6 rounded-lg border border-gray-200">
    <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
        <h3 class="text-lg font-medium text-gray-700">OPD Patient Charges (Bill Entry)</h3>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.pathology.bill.add', ['patient_id' => $opd_patient->patient_id ?? ($opd_patient->patient->id ?? ''), 'doctor_id' => $opd_patient->doctor_id ?? '']) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow-sm transition">
                <i class="ph ph-flask mr-1.5 text-sm"></i> Cut Pathology Bill
            </a>
            <a href="{{ route('admin.medicine.bill.add', ['patient_id' => $opd_patient->patient_id ?? ($opd_patient->patient->id ?? '')]) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-md shadow-sm transition">
                <i class="ph ph-pill mr-1.5 text-sm"></i> Cut Pharmacy Bill
            </a>
        </div>
    </div>

    <input type="hidden" id="patient_id" value="{{ $opd_patient->patient_id ?? ($opd_patient->patient->id ?? '') }}">
    <input type="hidden" id="ipd_no" value="{{ $opd_patient->opd_no }}">
    
    <input type="hidden" id="edit_charge_id" value="">
    <input type="hidden" id="charge_type_id" value="">
    <input type="hidden" id="charge_category_id" value="">

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Date</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Type</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Category</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Code</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Std Price</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Qty</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Applied</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Total</th>
                    <th class="px-2 py-2 text-left text-xs font-bold text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            
            <tbody id="charges_table_body" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="9" class="text-center py-4 text-gray-500">Loading...</td>
                </tr>
            </tbody>

            <tfoot class="bg-pink-50 border-t-2 border-pink-200">
                <tr>
                    <td class="px-2 py-2">
                        <input type="date" id="date" class="w-full text-xs border border-gray-300 rounded px-2 py-1 focus:ring-pink-500">
                    </td>
                    <td class="px-2 py-2">
                        <input type="text" id="charge_type" readonly class="w-full bg-gray-100 text-xs border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-2 py-2">
                        <select id="charge_category" class="w-full text-xs border border-gray-300 rounded px-2 py-1">
                            <option value="">Select Category</option>
                        </select>
                    </td>
                    <td class="px-2 py-2">
                        <input type="text" id="code" readonly class="w-full bg-gray-100 text-xs border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-2 py-2">
                        <input type="number" id="standard_charge" readonly class="w-full bg-gray-100 text-xs border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-2 py-2">
                        <input type="number" id="quantity" value="1" min="1" class="w-16 text-xs border border-gray-300 rounded px-2 py-1 text-center font-semibold">
                    </td>
                    <td class="px-2 py-2">
                        <input type="number" id="applied_charge" step="0.01" class="w-20 text-xs border border-gray-300 rounded px-2 py-1">
                    </td>
                    <td class="px-2 py-2">
                        <input type="number" id="total" readonly class="w-20 bg-gray-100 text-xs border border-gray-300 rounded px-2 py-1 font-bold text-pink-700">
                    </td>
                    <td class="px-2 py-2">
                        <div class="flex items-center space-x-1">
                            <button id="btn_save" onclick="saveCharge()" class="bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold py-1 px-3 rounded">
                                <i class="fas fa-plus"></i> Add
                            </button>
                            <button id="btn_cancel" onclick="resetForm()" class="hidden bg-gray-400 hover:bg-gray-500 text-white text-xs font-bold py-1 px-2 rounded">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Created Pathology & Pharmacy Bills --}}
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Pathology Bills --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-2 border-b">
            <h4 class="font-bold text-gray-800 text-base flex items-center gap-2">
                <i class="fas fa-flask text-indigo-600"></i> Generated Pathology Bills
            </h4>
            <span class="px-2.5 py-0.5 text-xs bg-indigo-100 text-indigo-800 font-bold rounded-full">{{ isset($pathology_bills) ? $pathology_bills->count() : 0 }}</span>
        </div>
        @if(isset($pathology_bills) && $pathology_bills->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                            <th class="py-2">Bill No</th>
                            <th class="py-2">Date</th>
                            <th class="py-2 text-right">Amount</th>
                            <th class="py-2 text-center">Status</th>
                            <th class="py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($pathology_bills as $pbill)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2.5 font-bold text-indigo-600">{{ $pbill->bill_no }}</td>
                                <td class="py-2.5 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($pbill->created_at)->format('d M, Y') }}</td>
                                <td class="py-2.5 font-bold text-gray-800 text-right">₹{{ number_format($pbill->amount, 2) }}</td>
                                <td class="py-2.5 text-center">
                                    @if($pbill->due_amount <= 0.01)
                                        <span class="px-2 py-0.5 text-[10px] bg-green-100 text-green-800 font-bold rounded">Paid</span>
                                    @elseif($pbill->paid_amount > 0)
                                        <span class="px-2 py-0.5 text-[10px] bg-yellow-100 text-yellow-800 font-bold rounded">Partial</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-800 font-bold rounded">Unpaid</span>
                                    @endif
                                </td>
                                <td class="py-2.5 text-center space-x-2">
                                    <a href="{{ route('admin.pathology.bill.show', $pbill->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded text-xs font-semibold" title="View Details">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('admin.pathology.bill.bill', $pbill->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold" title="Print Bill">
                                        <i class="fas fa-print mr-1"></i> Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-400 text-center py-6 border border-dashed rounded-lg bg-gray-50">No pathology bills generated for this patient yet.</p>
        @endif
    </div>

    {{-- Pharmacy / Medicine Bills --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-2 border-b">
            <h4 class="font-bold text-gray-800 text-base flex items-center gap-2">
                <i class="fas fa-pills text-emerald-600"></i> Generated Pharmacy Bills
            </h4>
            <span class="px-2.5 py-0.5 text-xs bg-emerald-100 text-emerald-800 font-bold rounded-full">{{ isset($pharmacy_bills) ? $pharmacy_bills->count() : 0 }}</span>
        </div>
        @if(isset($pharmacy_bills) && $pharmacy_bills->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                            <th class="py-2">Bill No</th>
                            <th class="py-2">Date</th>
                            <th class="py-2 text-right">Amount</th>
                            <th class="py-2 text-center">Status</th>
                            <th class="py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($pharmacy_bills as $mbill)
                            @php
                                $mpaid = ($mbill->paid_cash ?? 0) + ($mbill->paid_online ?? 0);
                                $mdue = ($mbill->net_amount ?? $mbill->total) - $mpaid;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-2.5 font-bold text-emerald-600">#{{ $mbill->bill_number }}</td>
                                <td class="py-2.5 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($mbill->bill_date ?? $mbill->created_at)->format('d M, Y') }}</td>
                                <td class="py-2.5 font-bold text-gray-800 text-right">₹{{ number_format($mbill->net_amount ?? $mbill->total, 2) }}</td>
                                <td class="py-2.5 text-center">
                                    @if($mdue <= 0.01)
                                        <span class="px-2 py-0.5 text-[10px] bg-green-100 text-green-800 font-bold rounded">Paid</span>
                                    @elseif($mpaid > 0)
                                        <span class="px-2 py-0.5 text-[10px] bg-yellow-100 text-yellow-800 font-bold rounded">Partial</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-800 font-bold rounded">Unpaid</span>
                                    @endif
                                </td>
                                <td class="py-2.5 text-center space-x-2">
                                    <a href="{{ route('admin.medicine.bill.show', $mbill->id) }}" target="_blank" class="inline-flex items-center px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold" title="View & Print Bill">
                                        <i class="fas fa-print mr-1"></i> Print / View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-400 text-center py-6 border border-dashed rounded-lg bg-gray-50">No pharmacy bills generated for this patient yet.</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function() {
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        let dateInput = document.getElementById('date');
        if (dateInput) {
            dateInput.valueAsDate = new Date();
        }

        loadOpdBillingCategories();
        loadTableData();

        let chargeCategory = document.getElementById('charge_category');
        if (chargeCategory) {
            chargeCategory.addEventListener('change', function() {
                fetchCategoryDetails(this.value);
            });
        }

        let quantity = document.getElementById('quantity');
        let appliedCharge = document.getElementById('applied_charge');
        
        if (quantity) quantity.addEventListener('input', calculateTotal);
        if (appliedCharge) appliedCharge.addEventListener('input', calculateTotal);
    }, 500);
});

function initializeSelect2() {
    try {
        if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
            $('#charge_category').select2({
                placeholder: 'Search and Select Category',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#charge_category').parent(),
                language: {
                    noResults: function () {
                        return 'No results found';
                    }
                }
            }).on('change', function (e) {
                fetchCategoryDetails(this.value);
            });
        }
    } catch(e) {
        console.error('Error initializing Select2:', e);
    }
}

function calculateTotal() {
    let qty = parseFloat(document.getElementById('quantity').value) || 0;
    let price = parseFloat(document.getElementById('applied_charge').value) || 0;
    let total = (qty * price).toFixed(2);
    document.getElementById('total').value = total;
}

function loadOpdBillingCategories() {
    fetch("{{ route('admin.charge.category.details') }}")
        .then(res => res.json())
        .then(data => {
            let select = document.getElementById('charge_category');
            if (!select) return;
            
            if (jQuery.fn.select2) {
                try {
                    $('#charge_category').select2('destroy');
                } catch(e) {}
            }
            
            select.innerHTML = '<option value="">Select Category</option>';
            
            if (data && data.length > 0) {
                data.forEach(item => {
                    let opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.charge_category;
                    opt.dataset.chargeTypeId = item.charge_type_id;
                    opt.dataset.chargeTypeName = item.charge_type_name;
                    opt.dataset.code = item.code;
                    opt.dataset.price = item.standard_charge;
                    select.appendChild(opt);
                });
            }
            
            setTimeout(() => {
                initializeSelect2();
            }, 100);
        })
        .catch(err => console.error("Error loading categories:", err));
}

function fetchCategoryDetails(catId) {
    if (!catId) {
        document.getElementById('charge_type').value = '';
        document.getElementById('code').value = '';
        document.getElementById('standard_charge').value = '';
        document.getElementById('applied_charge').value = '';
        document.getElementById('charge_type_id').value = '';
        document.getElementById('charge_category_id').value = '';
        calculateTotal();
        return;
    }

    let select = document.getElementById('charge_category');
    if (!select) return;
    
    let selectedOption = select.options[select.selectedIndex];

    let chargeTypeId = selectedOption.dataset.chargeTypeId || '';
    let chargeTypeName = selectedOption.dataset.chargeTypeName || '';
    let code = selectedOption.dataset.code || '';
    let standardCharge = parseFloat(selectedOption.dataset.price) || 0;

    document.getElementById('charge_type').value = chargeTypeName;
    document.getElementById('charge_type_id').value = chargeTypeId;
    document.getElementById('charge_category_id').value = catId;
    document.getElementById('code').value = code;
    document.getElementById('standard_charge').value = standardCharge;
    
    document.getElementById('applied_charge').value = standardCharge;
    calculateTotal();
}

function loadTableData() {
    let ipdNoInput = document.getElementById('ipd_no');
    if (!ipdNoInput) return;
    
    let ipd_no = ipdNoInput.value;
    let url = "{{ route('admin.opd.charge', ':id') }}".replace(':id', ipd_no);

    fetch(url)
        .then(res => res.json())
        .then(response => {
            let tbody = document.getElementById('charges_table_body');
            if (!tbody) return;
            
            tbody.innerHTML = '';

            let charges = [];
            if (response.data && Array.isArray(response.data)) {
                charges = response.data;
            } else if (Array.isArray(response)) {
                charges = response;
            } else if (response.charges) {
                charges = response.charges;
            }

            if (charges.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-gray-500">No additional charges added yet. (Standard Consultation Fee applies in Bill Details)</td></tr>';
                return;
            }

            charges.forEach(charge => {
                let chargeJson = JSON.stringify(charge).replace(/"/g, '&quot;');
                let qty = charge.quantity || 1;
                let applied = parseFloat(charge.applied_charge || 0);
                let total = charge.total || (qty * applied).toFixed(2);

                let row = `
                    <tr class="hover:bg-gray-50 transition border-b">
                        <td class="px-2 py-2 text-sm text-gray-700">${formatDate(charge.date)}</td>
                        <td class="px-2 py-2 text-sm text-gray-700">${charge.charge_type ? (charge.charge_type.charge_type || charge.charge_type.name) : '-'}</td>
                        <td class="px-2 py-2 text-sm text-gray-700">${charge.charge_category ? charge.charge_category.charge_category : '-'}</td>
                        <td class="px-2 py-2 text-sm text-gray-700">${charge.code || '-'}</td>
                        <td class="px-2 py-2 text-sm text-gray-700">${charge.standard_charge}</td>
                        <td class="px-2 py-2 text-sm text-gray-700 font-semibold">${qty}</td>
                        <td class="px-2 py-2 text-sm text-gray-700">${applied}</td>
                        <td class="px-2 py-2 text-sm font-bold text-gray-800">${total}</td>
                        <td class="px-2 py-2 text-sm">
                             <button onclick="editRow('${chargeJson}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteRow(${charge.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        })
        .catch(err => console.error("Error:", err));
}

function saveCharge() {
    let editChargeId = document.getElementById('edit_charge_id');
    let isEdit = editChargeId && editChargeId.value !== "";
    let url = isEdit ? "{{ route('admin.charge.update') }}" : "{{ route('admin.charge.add') }}";
    
    let data = {
        _token: "{{ csrf_token() }}",
        patient_id: document.getElementById('patient_id').value,
        ipd_no: document.getElementById('ipd_no').value,
        date: document.getElementById('date').value,
        charge_type: document.getElementById('charge_type_id').value,
        charge_category: document.getElementById('charge_category_id').value,
        code: document.getElementById('code').value,
        standard_charge: document.getElementById('standard_charge').value,
        applied_charge: document.getElementById('applied_charge').value,
        quantity: document.getElementById('quantity').value,
        total: document.getElementById('total').value
    };

    if(isEdit) {
        data.chargeid = editChargeId.value;
        data._method = "PUT";
    }

    if(!data.date || !data.charge_category || !data.applied_charge) {
        alert("Please fill required fields");
        return;
    }

    fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json", "Accept": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if(response.status_code === 1 || response.success) {
            resetForm();
            loadTableData();
        } else {
            let msg = response.message || "Error occurred";
            if(response.errors) msg += "\n" + JSON.stringify(response.errors);
            alert(msg);
        }
    })
    .catch(err => console.error(err));
}

function editRow(chargeJson) {
    try {
        let charge = JSON.parse(chargeJson);
        document.getElementById('edit_charge_id').value = charge.id;
        document.getElementById('date').value = charge.date;
        document.getElementById('code').value = charge.code || '';
        document.getElementById('standard_charge').value = charge.standard_charge || '';
        document.getElementById('applied_charge').value = charge.applied_charge || '';
        document.getElementById('quantity').value = charge.quantity || 1;
        document.getElementById('total').value = charge.total || (charge.applied_charge * (charge.quantity||1));

        let catId = charge.charge_category_id || (charge.charge_category ? charge.charge_category.id : '');
        let chargeTypeId = charge.charge_type_id || (charge.charge_type ? charge.charge_type.id : '');
        let chargeTypeName = charge.charge_type ? (charge.charge_type.charge_type || charge.charge_type.name) : '';
        
        document.getElementById('charge_category').value = catId;
        document.getElementById('charge_category_id').value = catId;
        document.getElementById('charge_type_id').value = chargeTypeId;
        document.getElementById('charge_type').value = chargeTypeName;
        
        if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
            $('#charge_category').trigger('change.select2');
        }

        let btn = document.getElementById('btn_save');
        btn.innerHTML = '<i class="fas fa-save"></i> Update';
        btn.classList.remove('bg-purple-600');
        btn.classList.add('bg-blue-600');
        
        let btnCancel = document.getElementById('btn_cancel');
        if (btnCancel) btnCancel.classList.remove('hidden');

        document.getElementById('date').scrollIntoView({behavior: 'smooth'});
    } catch(e) {
        console.error('Error parsing charge data:', e);
    }
}

function deleteRow(id) {
    if(!confirm("Are you sure you want to delete this charge?")) return;
    
    let url = "{{ route('admin.charge.delete', ':id') }}".replace(':id', id);
    
    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(() => loadTableData());
}

function resetForm() {
    document.getElementById('edit_charge_id').value = "";
    document.getElementById('date').valueAsDate = new Date();
    document.getElementById('charge_category').value = "";
    document.getElementById('charge_category_id').value = "";
    document.getElementById('charge_type').value = "";
    document.getElementById('charge_type_id').value = "";
    document.getElementById('code').value = "";
    document.getElementById('standard_charge').value = "";
    document.getElementById('applied_charge').value = "";
    document.getElementById('quantity').value = "1";
    document.getElementById('total').value = "";

    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
        $('#charge_category').trigger('change.select2');
    }

    let btn = document.getElementById('btn_save');
    btn.innerHTML = '<i class="fas fa-plus"></i> Add';
    btn.classList.add('bg-purple-600');
    btn.classList.remove('bg-blue-600');
    
    let btnCancel = document.getElementById('btn_cancel');
    if (btnCancel) btnCancel.classList.add('hidden');
}

function formatDate(dateString) {
    if(!dateString) return '-';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}
</script>
