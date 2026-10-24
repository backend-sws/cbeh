@extends('layouts.admin')
@section('title', 'IPD Patients')
@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            ring: 2px;
            ring-color: #3b82f6;
        }

        .table th {
            background-color: #f9fafb;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #374151;
        }

        /* Select2 Custom Styling to match Tailwind */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding-top: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
        }
    </style>

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">IPD Patient - Add Prescription</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.ipd.detail', $ipd->id) }}">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Back to IPD Details
                    </button>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="text-sm font-medium text-blue-600">Patient Name</div>
                <div class="text-lg font-semibold text-gray-800">{{ $ipd->patient->name }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-sm font-medium text-green-600">UHID</div>
                <div class="text-lg font-semibold text-gray-800">{{ $uhid ?? '--' }}</div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="text-sm font-medium text-purple-600">Doctor</div>
                @php
                    $rawAddPresDoc = $ipd->doctor->name ?? '';
                    $cleanAddPresDoc = $rawAddPresDoc ? preg_replace('/^(dr\.?\s*)+/i', '', trim($rawAddPresDoc)) : '';
                @endphp
                <div class="text-lg font-semibold text-gray-800">{{ $cleanAddPresDoc ? 'Dr. ' . $cleanAddPresDoc : 'N/A' }}</div>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <div class="text-sm font-medium text-orange-600">IPD No</div>
                <div class="text-lg font-semibold text-gray-800">{{ $ipd->id }}</div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.prescription.save', $ipd->ipd_no) }}" method="POST" id="prescriptionForm">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $ipd->patient->id }}">
                <input type="hidden" name="doctor_id" value="{{ $ipd->doctor->id }}">
                <input type="hidden" name="ipd_no" value="{{ $ipd->id }}">
                <input type="hidden" name="case" value="{{ $ipd->case }}">

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Doctor Notes <span class="text-xs text-gray-500">(You can use line breaks and bullets)</span>
                    </label>
                    <textarea
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm font-mono whitespace-pre-wrap"
                        rows="5" name="headernotes" id="headernotes" placeholder="Enter doctor notes...&#10;• Use bullets with • character&#10;• Press Enter for new lines"></textarea>
                    <small class="text-gray-500 text-xs mt-1">💡 Tip: Use ALT + 7 for bullets • and press Enter for line breaks</small>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-700">Prescription Items</h3>
                        <button type="button" id="addMedicineRow"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Add Medicine
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200" id="medicinesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="hidden px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dose</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frequency</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instruction</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="medicineRows">
                                <tr id="medicineRow0">
                                    <td class="hidden px-3 py-2 whitespace-nowrap">
                                        <select class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500 ipdCategoryId"
                                            name="medicine_category_id[]" onchange="filterIpdRowMedicines(this)">
                                            <option value="">All</option>
                                            @foreach ($medicineCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 180px;">
                                        <input type="text" list="ipdMedicineList_0"
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500 medicineInput font-medium"
                                            name="medicine_name[]" placeholder="Search medicine..." required autocomplete="off">
                                        <datalist id="ipdMedicineList_0" class="medicineDatalist">
                                            @foreach ($medicines as $medicine)
                                                <option value="{{ $medicine->medicine }}" data-category="{{ $medicine->category }}">{{ $medicine->medicine }}</option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                                        <input type="text" list="ipdDosageList"
                                            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500 dosageInput"
                                            name="dosage[]" placeholder="e.g. 500 mg" required autocomplete="off">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                                        <select name="dose_type[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                                            <option value="Tablet">Tablet</option>
                                            <option value="Capsule">Capsule</option>
                                            <option value="Syrup">Syrup</option>
                                            <option value="Injection (IV)">Injection (IV)</option>
                                            <option value="Injection (IM)">Injection (IM)</option>
                                            <option value="IV Infusion">IV Infusion</option>
                                            <option value="Drops">Drops</option>
                                            <option value="Inhaler">Inhaler</option>
                                            <option value="Ointment">Ointment</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                                        <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                                            <option value="Oral">Oral (PO)</option>
                                            <option value="Intravenous (IV)">Intravenous (IV)</option>
                                            <option value="Intramuscular (IM)">Intramuscular (IM)</option>
                                            <option value="Subcutaneous (SC)">Subcutaneous (SC)</option>
                                            <option value="Inhalation">Inhalation</option>
                                            <option value="Topical">Topical</option>
                                            <option value="Sublingual">Sublingual</option>
                                            <option value="Per Rectum (PR)">Per Rectum (PR)</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 120px;">
                                        <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs font-semibold focus:ring-blue-500">
                                            <option value="OD">OD (Once Daily)</option>
                                            <option value="BD" selected>BD (Twice Daily)</option>
                                            <option value="TDS">TDS (Thrice Daily)</option>
                                            <option value="QID">QID (4 Times Daily)</option>
                                            <option value="Q4H">Q4H (Every 4 Hours)</option>
                                            <option value="Q6H">Q6H (Every 6 Hours)</option>
                                            <option value="Q8H">Q8H (Every 8 Hours)</option>
                                            <option value="Continuous Dose">Continuous Dose</option>
                                            <option value="STAT">STAT (Immediate Once)</option>
                                            <option value="SOS">SOS (As Needed)</option>
                                            <option value="HS">HS (At Bedtime)</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                                        <select name="dose_duration[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                                            <option value="1 Day">1 Day</option>
                                            <option value="2 Days">2 Days</option>
                                            <option value="3 Days">3 Days</option>
                                            <option value="5 Days" selected>5 Days</option>
                                            <option value="7 Days">7 Days</option>
                                            <option value="10 Days">10 Days</option>
                                            <option value="14 Days">14 Days</option>
                                            <option value="30 Days">30 Days</option>
                                            <option value="Ongoing">Ongoing</option>
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500"
                                            name="instruction[]" placeholder="e.g. After meals">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center">
                                        <button type="button" class="text-red-500 hover:text-red-700"
                                            onclick="removeMedicineRow(0)" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Master Datalists -->
                    <datalist id="masterIpdMedicineList">
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->medicine }}" data-category="{{ $medicine->category }}">{{ $medicine->medicine }}</option>
                        @endforeach
                    </datalist>

                    <datalist id="ipdDosageList">
                        <option value="OD">OD - Once Daily</option>
                        <option value="BD">BD - Twice Daily</option>
                        <option value="TDS">TDS - Thrice Daily</option>
                        <option value="QID">QID - 4 Times Daily</option>
                        <option value="SOS">SOS - As Needed</option>
                        <option value="BBF">BBF - Before Breakfast</option>
                        <option value="HS">HS - At Bedtime</option>
                        <option value="1 Drop TDS">1 Drop TDS</option>
                        <option value="1 Drop BD">1 Drop BD</option>
                        <option value="2 Drops TDS">2 Drops TDS</option>
                        <option value="1 Tab OD">1 Tab OD</option>
                        <option value="1 Tab BD">1 Tab BD</option>
                        <option value="5 ml TDS">5 ml TDS</option>
                        <option value="10 ml BD">10 ml BD</option>
                    </datalist>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Investigation Advise <span class="text-xs text-gray-500">(You can use line breaks and bullets)</span>
                    </label>
                    <textarea
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm font-mono whitespace-pre-wrap"
                        rows="5" name="footernotes" id="footernotes" placeholder="Enter investigation advice...&#10;• ECG - Regular&#10;• CBC - Normal"></textarea>
                    <small class="text-gray-500 text-xs mt-1">💡 Tip: Use ALT + 7 for bullets • and press Enter for line breaks</small>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let medicineRowCount = 0;

        function filterIpdRowMedicines(select) {
            const categoryId = select.value;
            const row = select.closest('tr');
            const datalist = row.querySelector('.medicineDatalist');
            const master = document.getElementById('masterIpdMedicineList');
            if (!datalist || !master) return;

            if (!categoryId) {
                datalist.innerHTML = master.innerHTML;
                return;
            }

            datalist.innerHTML = '';
            Array.from(master.options).forEach(opt => {
                if (opt.dataset.category == categoryId) {
                    datalist.appendChild(opt.cloneNode(true));
                }
            });
        }

        function addMedicineRow() {
            medicineRowCount++;
            const master = document.getElementById('masterIpdMedicineList');
            const masterHtml = master ? master.innerHTML : '';
            const listId = 'ipdMedicineList_' + medicineRowCount;

            const row = `
            <tr id="medicineRow${medicineRowCount}">
                <td class="hidden px-3 py-2 whitespace-nowrap">
                    <select class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500 ipdCategoryId" 
                        name="medicine_category_id[]" onchange="filterIpdRowMedicines(this)">
                        <option value="">All</option>
                        @foreach ($medicineCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 180px;">
                    <input type="text"
                        list="${listId}"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500 medicineInput font-medium"
                        name="medicine_name[]"
                        placeholder="Search medicine..."
                        required
                        autocomplete="off">
                    <datalist id="${listId}" class="medicineDatalist">
                        ${masterHtml}
                    </datalist>
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                    <input type="text"
                        list="ipdDosageList"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500 dosageInput"
                        name="dosage[]"
                        placeholder="e.g. 500 mg"
                        required
                        autocomplete="off">
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                    <select name="dose_type[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                        <option value="Tablet">Tablet</option>
                        <option value="Capsule">Capsule</option>
                        <option value="Syrup">Syrup</option>
                        <option value="Injection (IV)">Injection (IV)</option>
                        <option value="Injection (IM)">Injection (IM)</option>
                        <option value="IV Infusion">IV Infusion</option>
                        <option value="Drops">Drops</option>
                        <option value="Inhaler">Inhaler</option>
                        <option value="Ointment">Ointment</option>
                    </select>
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                    <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                        <option value="Oral">Oral (PO)</option>
                        <option value="Intravenous (IV)">Intravenous (IV)</option>
                        <option value="Intramuscular (IM)">Intramuscular (IM)</option>
                        <option value="Subcutaneous (SC)">Subcutaneous (SC)</option>
                        <option value="Inhalation">Inhalation</option>
                        <option value="Topical">Topical</option>
                        <option value="Sublingual">Sublingual</option>
                        <option value="Per Rectum (PR)">Per Rectum (PR)</option>
                    </select>
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 120px;">
                    <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs font-semibold focus:ring-blue-500">
                        <option value="OD">OD (Once Daily)</option>
                        <option value="BD" selected>BD (Twice Daily)</option>
                        <option value="TDS">TDS (Thrice Daily)</option>
                        <option value="QID">QID (4 Times Daily)</option>
                        <option value="Q4H">Q4H (Every 4 Hours)</option>
                        <option value="Q6H">Q6H (Every 6 Hours)</option>
                        <option value="Q8H">Q8H (Every 8 Hours)</option>
                        <option value="STAT">STAT (Immediate Once)</option>
                        <option value="SOS">SOS (As Needed)</option>
                        <option value="HS">HS (At Bedtime)</option>
                    </select>
                </td>
                <td class="px-3 py-2 whitespace-nowrap" style="min-width: 110px;">
                    <select name="dose_duration[]" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500">
                        <option value="1 Day">1 Day</option>
                        <option value="2 Days">2 Days</option>
                        <option value="3 Days">3 Days</option>
                        <option value="5 Days" selected>5 Days</option>
                        <option value="7 Days">7 Days</option>
                        <option value="10 Days">10 Days</option>
                        <option value="14 Days">14 Days</option>
                        <option value="30 Days">30 Days</option>
                        <option value="Ongoing">Ongoing</option>
                    </select>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs focus:ring-blue-500" 
                        name="instruction[]" placeholder="e.g. After meals">
                </td>
                <td class="px-3 py-2 whitespace-nowrap text-center">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeMedicineRow(${medicineRowCount})" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;

            // Append row
            $('#medicineRows').append(row);
        }

        function removeMedicineRow(rowId) {
            const tbody = document.getElementById('medicineRows');
            if (tbody.children.length > 1) {
                const row = document.getElementById(`medicineRow${rowId}`);
                if (row) {
                    row.remove();
                }
            } else {
                const row = tbody.children[0];
                if (row) {
                    row.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                }
            }
        }

        // Existing alert hide logic
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addMedicineRow').addEventListener('click', addMedicineRow);
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert) {
                        alert.style.display = 'none';
                    }
                });
            }, 5000);
        });
    </script>

@endsection
