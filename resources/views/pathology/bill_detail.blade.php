@extends('layouts.admin')

@section('title', 'Pathology Bill Details')

@section('content')
    <div class="bg-gray-50 p-6 font-sans">
        <!-- Header with actions -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pathology Bill Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.pathology.bill.bill', $bill->id) }}" target="_blank"
                    class="bg-pink-100 hover:bg-pink-200 text-pink-800 border border-pink-300 px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="ph ph-printer ph-bold mr-2"></i>
                    PRINT BILL
                </a>
                <a href="{{ route('admin.pathology.bill.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="ph ph-arrow-left ph-bold mr-2"></i>
                    BACK
                </a>
            </div>
        </div>

        <!-- Bill Information Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8 border border-gray-200">
            <!-- Patient and Bill Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                <!-- Column 1 -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Bill No</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->bill_no }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Patient Name</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->patient->name ?? 'N/A' }}</p>
                        @if ($bill->patient->email ?? false)
                            <p class="text-sm text-gray-500">{{ $bill->patient->email }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Age/Gender</p>
                        <p class="text-gray-800 font-semibold">
                            {{ $bill->patient->age ?? 'N/A' }}Y {{ $bill->patient->age_month ?? '0' }}D /
                            {{ ucfirst($bill->patient->gender ?? 'N/A') }}
                        </p>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Doctor</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Contact</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->patient->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Blood Group</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->patient->blood_group ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Address</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->patient->address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Note</p>
                        <p class="text-gray-800 font-semibold">{{ $bill->note ?? 'No notes available' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                        <p class="text-lg font-bold text-gray-800">₹{{ number_format($bill->amount, 2) }}</p>
                    </div>
                    <!-- Add other financial fields here if needed -->
                </div>
            </div>
        </div>

        <!-- Tests Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Test Reports</h3>
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Test Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Expected Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tests as $index => $test)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $test->test->test_name ?? 'N/A' }}</div>
                                    </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                         @if ($bill->created_at)
                                             {{ \Carbon\Carbon::parse($bill->created_at)->addDays((int) ($test->test->report_days ?? 0))->format('d/m/Y') }}
                                         @else
                                             N/A
                                         @endif
                                     </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if (isset($reports[$test->test->id]))
                                                <span class="text-green-600">Has Done</span>
                                            @else
                                                <span class="text-yellow-600">Pending</span>
                                            @endif
                                        </div>
                                        @if ($test->approve_date)
                                            <div class="text-xs text-gray-500">
                                                {{ date('d/m/Y', strtotime($test->approve_date)) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>₹{{ number_format($test->amount, 2) }}</div>
                                        <div class="text-xs">Tax: {{ $test->tax ?? '0' }}%</div>
                                    </td>
                                    @php
                                        // Find the specific report for this test from the $reports collection
                                        $currentReport = $reports[$test->test->id] ?? null;
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="openModalWithData({{ $test->test->id }}, {{ $bill->id }})"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50 transition-colors"
                                                title="Edit Report">
                                                <i class="ph ph-test-tube text-xl"></i>
                                            </button>
                                            @if($currentReport)
            <a href="{{ route('admin.pathology.report.download', $currentReport->id) }}" 
               class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-50 transition-colors"
               title="Download Report" target="_blank">
                <i class="ph ph-printer text-xl"></i>
            </a>
        @else
            <span class="text-gray-300 cursor-not-allowed p-1"><i class="ph ph-printer text-xl"></i></span>
        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No tests found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Report Modal -->
    <div id="editReportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 pb-2 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Test Report Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ph ph-x ph-bold text-2xl"></i>
                </button>
            </div>

            <form id="editReportForm" class="space-y-4" action="{{ route('admin.pathology.report.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="test_id" id="modalTestId">
                <input type="hidden" name="bill_id" value="{{ $bill->id }}">

                <!-- Test Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Test Name</label>
                            <div class="p-2 bg-white rounded border border-gray-200" id="modalTestName"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Date</label>
                            <div class="p-2 bg-white rounded border border-gray-200" id="modalExpectedDate"></div>
                        </div>
                    </div>
                </div>

                <!-- Test Results Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h4 class="text-md font-medium text-gray-800 mb-3">Test Results</h4>

                    <div class="mb-4">
                        <label for="resultInput" class="block text-sm font-medium text-gray-700 mb-1">Overall
                            Result</label>
                        <input type="text" name="result" id="resultInput"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-500"
                            placeholder="Enter overall result here...">
                    </div>

                    <div id="parameterContainer" class="space-y-4">
                        <!-- Parameters will be injected here by JavaScript -->
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global variable to store available parameters
        let availableParameters = [];

        function openModalWithData(id, bill_id) {
            fetch(`/admin/pathology/report/get/${id}/${bill_id}`)
                .then(res => res.json())
                .then(data => {
                    const test = data.test;
                    const report = data.report;
                    availableParameters = data.test.parameters || [];

                    // Fill Test Details
                    document.getElementById('modalTestName').textContent = test.test_name || 'N/A';
                    document.getElementById('modalExpectedDate').textContent = test.expected_date || 'N/A';
                    document.getElementById('modalTestId').value = test.test_id || '';
                    document.getElementById('resultInput').value = report?.result || '';

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
