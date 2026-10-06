@extends('layouts.admin')

@section('title', 'Blood issue Details')

@section('content')
<div class="bg-gray-50 p-6 font-sans">
    <!-- Header with actions -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Blood Issue Bill Details</h1>

    </div>

    <!-- Bill Information Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8 border border-gray-200">
        <!-- Patient and Bill Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <!-- Column 1 -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Bill No</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Patient Name</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->patient_name}}</p>
                </div>

            </div>

            <!-- Column 2 -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Doctor</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->doctor_name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 font-medium">Blood Group</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->blood_group }}</p>
                </div>
            </div>

            <!-- Column 3 -->
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Note</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->notes }}</p>
                </div>

            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Bags</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->bags }}</p>
                </div>

            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Issue Date</p>
                    <p class="text-gray-800 font-semibold">{{ $issues->issue_date }}</p>
                </div>

            </div>
        </div>

        <!-- Financial Summary -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                    <p class="text-lg font-bold text-gray-800">₹{{ number_format($issues->amount) }}</p>
                </div>
                <!-- Add other financial fields here if needed -->
            </div>
        </div>
        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Unit</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Amount</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Blood Group</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <tr>
            <td class="px-4 py-2 text-gray-800">{{ $issues->bags }}</td>
            <td class="px-4 py-2 text-gray-800">₹{{ number_format($issues->amount) }}</td>
            <td class="px-4 py-2 text-gray-800">{{ $issues->blood_group }}</td>
        </tr>
    </tbody>
</table>

    </div>

    <!-- Tests Section -->




    <script>
        // Global variable to store available parameters
        let availableParameters = [];

        function openModalWithData(id) {
            fetch(`/admin/pathology/report/get/${id}`)
                .then(res => res.json())
                .then(data => {
                    const test = data.test;
                    const report = data.report;
                    availableParameters = data.test.parameters || [];

                    // Fill Test Details
                    document.getElementById('modalTestName').textContent = test.test_name || 'N/A';
                    document.getElementById('modalExpectedDate').textContent = test.expected_date || 'N/A';
                    document.getElementById('modalTestId').value = test.test_id || '';
                    // document.getElementById('modalApproveDate').value = report?.approve_date || '';
                    document.getElementById('resultInput').value = report?.result || '';
                    document.getElementById('modalPathologyCenter').value = report?.pathology_center || '';

                    // Render Test Parameters
                    const paramContainer = document.getElementById('parameterContainer');
                    paramContainer.innerHTML = '';

                    if (test.exit_parameters && Array.isArray(test.exit_parameters)) {
                        test.exit_parameters.forEach((param, index) => {
                            const html = `
                        <div class="bg-white p-4 rounded-md border border-gray-200">
                            <input type="hidden" name="parameter_id[]" value="${param.id}">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">${index + 1}. ${param.name || 'Parameter'}</label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Report Value</label>
                                    <input type="text" class="w-full border border-gray-300 rounded p-2" 
                                           name="report_value[]" value="${param.report_value || ''}">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Reference Range</label>
                                    <input type="text" class="w-50 border border-gray-300 rounded p-2 bg-gray-100" 
                                           value="${param.reference_range || 'N/A'}" name="reference_range[]" readonly>
                                    <input type="text" class="w-50 border border-gray-300 rounded p-2 bg-gray-100 mt-1" 
                                           value="${param.unit || ''}" readonly>
                                </div>
                            </div>
                        </div>
                    `;
                            paramContainer.insertAdjacentHTML('beforeend', html);
                        });
                    }

                    // Show the modal
                    document.getElementById('editReportModal').classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Error fetching report details:', err);
                    alert('Failed to load report data. Please try again.');
                });
        }

        function closeModal() {
            document.getElementById('editReportModal').classList.add('hidden');
        }

        // Close modal when clicking outside or pressing Escape
        document.addEventListener('click', (event) => {
            if (event.target === document.getElementById('editReportModal')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
    @endsection