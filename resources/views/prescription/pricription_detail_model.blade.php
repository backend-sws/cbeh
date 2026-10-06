<!-- Modal -->
<div id="newBedModal"
    class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden justify-center items-center overflow-y-auto print:hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-4xl w-full relative print:w-full print:rounded-none print:shadow-none my-8"
        id="printArea" style="max-height: 90vh; overflow-y: auto;">

        <!-- Close and Print Buttons -->
        <div class="absolute top-4 right-4 flex space-x-2 print:hidden">
            <button onclick="printPrescription({{ $prescription->id ?? '0' }})"
                class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors" title="Print">
                <i class="fas fa-print"></i>
            </button>
            <button id="closeModalBtn"
                class="bg-gray-600 text-white p-2 rounded-lg hover:bg-gray-700 transition-colors text-lg font-bold">&times;</button>
        </div>

        <!-- Header Section - Hidden in Print -->
        <div class="header-section border-b-2 border-pink-600 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo"
                        class="h-16 w-16 object-contain">
                    <div>
                        <h1 class="text-2xl font-bold text-pink-600">{{ setting('hospital_name', 'Hospital') }}</h1>
                        <p class="text-gray-600 text-sm mt-1">{{ setting('address', 'Address') }}</p>
                    </div>
                </div>
                <div class="text-right text-sm text-gray-600">
                    <p>Phone: +91 {{ setting('phone', '0000000000') }}, {{ setting('mobile', '0000000000') }}</p>
                    <p>Website: <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}"
                            class="text-pink-600 hover:underline">{{ setting('website', 'www.patliputranetralaya.in') }}</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Blank Space for Print (Visible only in print) -->
        <div class="print-header-space hidden print:block" style="height: 80px; margin-bottom: 20px;">
            <!-- Blank space for print -->
        </div>

        <!-- Patient and Doctor Details -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 print:hidden">Patient Prescription Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <!-- Patient Basic Info -->
                <div class="space-y-2">
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">UHID:</span>
                        <span id="patientUHID"
                            class="text-gray-900">{{ $patient ? $patient->patient_unique_id : '' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Name:</span>
                        <span id="patientNameText" class="text-gray-900">-</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Email:</span>
                        <span id="patientEmailText" class="text-gray-900">-</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Phone:</span>
                        <span id="patientPhoneText" class="text-gray-900">-</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Gender:</span>
                        <span id="patientGenderText" class="text-gray-900">-</span>
                    </div>
                </div>

                <!-- Medical Info -->
                <div class="space-y-2">
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Age:</span>
                        <span id="patientAgeText" class="text-gray-900">-</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Blood Pressure:</span>
                        <span id="patientBpText" class="text-gray-900">{{ $ipd_patient->blood_pressure }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">IPD No:</span>
                        <span id="ipdNoText" class="text-gray-900">{{ $ipd_patient->ipd_no }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Case ID:</span>
                        <span id="caseIdText" class="text-gray-900">{{ $ipd_patient->case }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-1">
                        <span class="font-semibold text-gray-700">Prescription Date:</span>
                        <span id="prescriptionDateText" class="text-gray-900">-</span>
                    </div>
                </div>
            </div>

            <!-- Symptoms and Doctor -->
            <div class="mt-4 space-y-2">
                <div class="flex justify-between border-b pb-1">
                    <span class="font-semibold text-gray-700">Symptoms:</span>
                    <span id="statusText"
                        class="text-gray-900 text-right flex-1 ml-4">{{ $ipd_patient->symptoms }}</span>
                </div>
                <!--<div class="flex justify-between border-b pb-1">-->
                <!--    <span class="font-semibold text-gray-700">Doctor Name:</span>-->
                <!--    <span id="doctorNameText" class="text-gray-900">-</span>-->
                <!--</div>-->
            </div>
        </div>

        <!-- Medicine Table -->
        <div class="mb-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-3 border-b pb-2">Prescribed Medicines</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Category
                            </th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Medicine
                                Name</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Dosage
                            </th>
                            {{-- <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Frequency
                            </th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">
                                Instructions</th> --}}
                        </tr>
                    </thead>
                    <tbody id="medicineTableBody" class="bg-white">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Notes Section -->
        <div id="additionalNotes" class="mb-6 hidden">
            <h3 class="font-semibold text-lg text-gray-800 mb-3 border-b pb-2">Doctor Notes</h3>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p id="headerNotesText" class="text-gray-700"></p>
                <p id="footerNotesText" class="text-gray-700 mt-2"></p>
            </div>
        </div>

        <!-- Signature Area - Hidden in Print -->
        <div class="mt-8 pt-6 border-t border-gray-300 print:hidden">
            <div class="flex justify-between">
                <div class="text-center w-1/2">
                    <div class="border-t border-gray-400 w-3/4 mx-auto mb-2"></div>
                    <p class="text-sm font-semibold text-gray-700">Doctor's Signature</p>
                    <p id="doctorNameSignature" class="text-xs text-gray-600 mt-1">-</p>
                </div>
                <div class="text-center w-1/2">
                    <div class="border-t border-gray-400 w-3/4 mx-auto mb-2"></div>
                    <p class="text-sm font-semibold text-gray-700">Patient/Guardian Signature</p>
                </div>
            </div>
        </div>

        <!-- Footer - Hidden in Print -->
        <div class="default-text-footer" style="display: {{ setting('opd_footer_image') ? 'none' : 'block' }} !important;" class="mt-8 pt-6 border-t border-dashed border-pink-600 text-center text-xs text-gray-600 print:hidden">
            <p class="font-bold text-pink-600 mb-1">
                For Emergency Services: +91{{ setting('phone', '0000000000') }}, {{ setting('mobile', '0000000000') }}
            </p>
            <p class="mb-1">
                Website: <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}"
                    class="text-pink-600 hover:underline">{{ setting('website', 'www.patliputranetralaya.in') }}</a>
            </p>
            <p>{{ setting('address', 'Patna: Opposite SBI, Bhootnath Road, Patna-800026') }}</p>
        </div>
    </div>
</div>

<style>
    /* Print-specific styles */
    @media print {

        .header-section,
        .print-hidden,
        .footer,
        .signature-area {
            display: none !important;
        }

        .print-header-space {
            display: block !important;
            visibility: visible !important;
        }

        /* Ensure good print layout */
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        #printArea {
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
            max-height: none;
            overflow: visible;
            width: 100%;
        }

        /* Better table styling for print */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        /* Compact layout for print */
        .mb-6 {
            margin-bottom: 15px;
        }

        .mt-4 {
            margin-top: 10px;
        }

        .space-y-2>*+* {
            margin-top: 4px;
        }

        .border-b {
            border-bottom: 1px solid #e5e5e5;
        }

        .pb-1 {
            padding-bottom: 2px;
        }
    }

    /* Screen styles */
    @media screen {
        .print-header-space {
            display: none;
        }
    }
</style>

<script>
    function predictiondata(id) {
        let predetailapiurl = "{{ route('admin.prescription.prescription_detail', ':id') }}";
        predetailapiurl = predetailapiurl.replace(':id', id);

        fetch(predetailapiurl)
            .then(response => response.json())
            .then(data => {
                // Patient Information
                document.getElementById('patientNameText').textContent = data.patient?.name ?? 'N/A';
                document.getElementById('patientEmailText').textContent = data.patient?.email ?? 'N/A';
                document.getElementById('patientPhoneText').textContent = data.patient?.phone ?? 'N/A';
                document.getElementById('patientGenderText').textContent = data.patient?.gender ?? 'N/A';
                let ageText = (data.patient?.age ?? '0') + ' Y';
                if (data.patient?.age_month) {
                    ageText += ' ' + data.patient.age_month + ' M';
                }
                document.getElementById('patientAgeText').textContent = ageText;
                document.getElementById('doctorNameText').textContent = data.doctor?.name ?? 'N/A';
                document.getElementById('doctorNameSignature').textContent = data.doctor?.name ?? 'N/A';
                document.getElementById('prescriptionDateText').textContent = data.prescription_date ? new Date(data
                    .prescription_date).toLocaleDateString() : 'N/A';
                document.getElementById('statusText').textContent = data.status ?? 'N/A';

                // Additional Notes
                if (data.headernotes || data.footernotes) {
                    document.getElementById('additionalNotes').classList.remove('hidden');
                    document.getElementById('headerNotesText').textContent = data.headernotes || '';
                    document.getElementById('footerNotesText').textContent = data.footernotes || '';
                }

                // Medicines Table
                const medicineTable = document.getElementById('medicineTableBody');
                medicineTable.innerHTML = '';

                if (data.medicines && data.medicines.length > 0) {
                    data.medicines.forEach((med, index) => {
                        let tr = document.createElement('tr');
                        tr.className = index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                        tr.innerHTML = `
                            <td class="border border-gray-300 px-4 py-3 text-gray-700">${med.medicine?.category?.medicine_category || med.medicine?.categoryRelation?.medicine_category || '-'}</td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-700 font-medium">${med.medicine_name || med.medicine?.medicine || med.medicine?.name || 'N/A'}</td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-700">${med.dosage ?? 'N/A'} ${med.time ? `(${med.time})` : ''}</td>
                            {{-- <td class="border border-gray-300 px-4 py-3 text-gray-700">${med.frequency ?? 'N/A'}</td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-700">${med.instructions ?? 'N/A'}</td> --}}
                        `;
                        medicineTable.appendChild(tr);
                    });
                } else {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="5" class="border border-gray-300 px-4 py-4 text-center text-gray-500">
                            No medicines prescribed
                        </td>
                    `;
                    medicineTable.appendChild(tr);
                }
            })
            .catch(error => {
                console.error('Error fetching prescription data:', error);
                alert('Error loading prescription details. Please try again.');
            });
    }

   function printPrescription(id) {
    // New tab mein print page open karein
    const printUrl = "{{ route('admin.prescription.print', ':id') }}".replace(':id', id);
    window.open(printUrl, '_blank');
}

// Modal open function update karein
function openModalprecription(id) {
    predictiondata(id);
    const modal = document.getElementById('newBedModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    // Print button ko update karein
    const printBtn = modal.querySelector('button[onclick*="printPrescription"]');
    if (printBtn) {
        printBtn.setAttribute('onclick', `printPrescription(${id})`);
    }
}
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('newBedModal');
        const closeBtn = document.getElementById('closeModalBtn');

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            });
        }

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    });
</script>


@if(setting('opd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
