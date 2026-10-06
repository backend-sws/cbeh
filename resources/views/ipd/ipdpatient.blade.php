@extends('layouts.admin')
@section('title', 'IPD Patients')
@section('content')

    <body class="bg-gray-100 p-2">

        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class=" py-4 px-4 mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            {{-- Header Section --}}
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">IPD Patients</h1>
                    <p class="text-sm text-gray-500">Manage In-Patient Department admissions & billing</p>
                </div>
                <div>
                    <a href="{{ route('admin.ipd.add') }}"
                        class="inline-flex items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors shadow-sm">
                        <i class="ph ph-plus-bold mr-2"></i> New Admission
                    </a>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                    {{-- 1. Search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Name, IPD No, UHID..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    {{-- 2. Doctor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                            <option value="">All Doctors</option>
                            @foreach ($doctors as $doc)
                                <option value="{{ $doc->id }}"
                                    {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Admission/Discharge Status (NEW) --}}
                    {{-- 3. Admission/Discharge Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admission Status</label>
                        <select name="discharge_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">

                            {{-- Empty value for ALL --}}
                            <option value="" {{ $discharge_status == '' ? 'selected' : '' }}>All Status</option>

                            <option value="admitted" {{ $discharge_status == 'admitted' ? 'selected' : '' }}>
                                Admitted (In Hospital)
                            </option>

                            <option value="discharged" {{ $discharge_status == 'discharged' ? 'selected' : '' }}>
                                Discharged
                            </option>
                        </select>
                    </div>

                    {{-- 4. Payment Status (NEW) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select name="due_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                            <option value="">All Payment Status</option>
                            <option value="pending" {{ request('due_status') == 'pending' ? 'selected' : '' }}>Pending Dues
                            </option>
                            <option value="clear" {{ request('due_status') == 'clear' ? 'selected' : '' }}>No Dues (Paid)
                            </option>
                        </select>
                    </div>

                    {{-- 5. From Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    {{-- 6. To Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex space-x-2 lg:col-span-2">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors shadow-sm">
                            <i class="ph ph-funnel mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.ipd.index') }}"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors border border-gray-300">
                            <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">IPD /
                                UHID</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-[180px]">Patient
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-[240px]">Doctor
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Admission</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bed
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Due
                                Amount</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($ipd_patients as $patient)
                            @if ($patient->patient)
                                @php
                                    // CALCULATE DUE LOGIC
                                    $totalCharges = $patient->charges->sum('total') ?? 0;
                                    $totalPaid = $patient->payments->sum('amount') ?? 0;
                                    $dueAmount = $totalCharges - $totalPaid;

                                    // CHECK DISCHARGE STATUS
                                    $isDischarged = !empty($patient->discharge_date);
                                @endphp

                                <tr class="hover:bg-gray-50 transition duration-150">
                                    {{-- IPD / UHID --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <a href="{{ route('admin.ipd.detail', $patient->id) }}"
                                                class="text-sm font-bold text-blue-600 hover:text-blue-800">
                                                {{ $patient->ipd_no }}
                                            </a>
                                            <span class="text-xs text-gray-500 font-medium">
                                                UHID: {{ $patient->patient->patient_unique_id ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- PATIENT --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-[180px]">
                                            <div class="font-medium text-gray-900 text-sm line-clamp-2 break-words" title="{{ $patient->patient->name ?? 'N/A' }}">
                                                {{ $patient->patient->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $patient->patient->phone ?? '' }}</div>
                                        </div>
                                    </td>

                                    {{-- DOCTOR --}}
                                    <td class="px-6 py-4">
                                        <div class="max-w-[240px]">
                                            <div class="text-sm text-gray-900 font-medium line-clamp-2 break-words" title="{{ $patient->doctor->name ?? 'N/A' }}">
                                                {{ $patient->doctor->name ?? 'N/A' }}
                                            </div>
                                            @if (!empty($patient->doctor->email))
                                                <div class="text-xs text-gray-500 truncate" title="{{ $patient->doctor->email }}">
                                                    {{ $patient->doctor->email }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- ADMISSION DATE --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-gray-900 font-medium">
                                            {{ \Carbon\Carbon::parse($patient->admission_date)->format('d M, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($patient->admission_date)->format('h:i A') }}
                                        </div>
                                    </td>

                                    {{-- BED --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                        {{ $patient->beds ? $patient->beds->name : '-' }}
                                    </td>

                                    {{-- DUE AMOUNT --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if ($dueAmount > 1)
                                            <span class="text-red-600 font-bold">₹{{ number_format($dueAmount, 2) }}</span>
                                        @else
                                            <span
                                                class="text-green-600 font-bold text-xs bg-green-100 px-2 py-1 rounded-full">Paid</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($isDischarged)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-600 rounded-full">Discharged</span>
                                        @elseif ($patient->status)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-600 rounded-full">Admitted</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">Inactive</span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center overflow-visible">
                                        <div class="flex justify-center items-center gap-2 relative">

                                            {{-- 1. Edit Button --}}
                                            <a href="{{ route('admin.ipd.edit', $patient->id) }}"
                                                class="text-blue-600 hover:bg-blue-100 p-1.5 rounded transition-colors"
                                                title="Edit Patient">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </a>

                                            {{-- 2. Payment History --}}
                                            <button
                                                onclick="openIpdHistory({{ $patient->id }}, '{{ $patient->ipd_no }}')"
                                                class="text-gray-700 hover:bg-gray-200 p-1.5 rounded transition-colors"
                                                title="Payment History">
                                                <i class="ph ph-receipt text-lg"></i>
                                            </button>

                                            {{-- 3. Discharge --}}
                                            <a href="{{ route('admin.ipd.discharge.form', $patient->id) }}"
                                                class="text-red-600 hover:bg-red-100 p-1.5 rounded transition-colors"
                                                title="{{ $patient->status ? 'Discharge Patient' : 'Edit Discharge Summary' }}">
                                                <i class="ph ph-sign-out text-lg"></i>
                                            </a>

                                            {{-- 4. NEW: Print Dropdown (Click Based) --}}
                                            <div class="relative">
                                                <button onclick="togglePrintMenu(event, 'print-menu-{{ $patient->id }}')"
                                                    class="text-purple-600 hover:bg-purple-100 p-1.5 rounded transition-colors focus:outline-none"
                                                    title="Print Documents">
                                                    <i class="ph ph-printer text-lg"></i>
                                                </button>

                                                {{-- Dropdown Content --}}
                                                <div id="print-menu-{{ $patient->id }}"
                                                    class="print-dropdown-content hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50 text-left overflow-hidden">

                                                    <div
                                                        class="bg-gray-50 px-4 py-2 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase">
                                                        Print Documents
                                                    </div>

                                                    <a href="{{ route('admin.ipd.print.admission_slip', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-50 transition-colors">
                                                        <i class="ph ph-file-text mr-2"></i> Admission Slip
                                                    </a>

                                                    <a href="{{ route('admin.ipd.discharge.slip', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-50 transition-colors">
                                                        <i class="ph ph-file-pdf mr-2"></i> Discharge Slip
                                                    </a>

                                                    <a href="{{ route('admin.ipd.blankreceipt', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors">
                                                        <i class="ph ph-receipt mr-2"></i> Blank Prescription
                                                    </a>
                                                    <a href="{{ route('admin.ipd.print.discharge_slip', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-50 transition-colors">
                                                        <i class="ph ph-ticket mr-2"></i> Discharge / Gate Pass
                                                    </a>
                                                    <a href="{{ route('admin.ipd.print.no_dues', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-50 transition-colors">
                                                        <i class="ph ph-check-circle mr-2"></i> No Dues Certificate
                                                    </a>
                                                    <a href="{{ route('admin.ipd.print.service_chart', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-50 transition-colors">
                                                        <i class="ph ph-calendar-check mr-2"></i> Service Chart
                                                    </a>
                                                    <a href="{{ route('admin.ipd.print.pos_label', $patient->id) }}"
                                                        target="_blank"
                                                        class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors">
                                                        <i class="ph ph-barcode mr-2"></i> POS Label
                                                    </a>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="8" class="text-center text-red-500 py-2">Invalid Patient Data</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="ph ph-folder-open text-3xl mb-2 text-gray-300"></i>
                                        <p>No IPD patients found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{-- Appends ensures filters stay active when changing pages --}}
                @include('partials.pagination', ['users' => $ipd_patients->appends(request()->query())])
            </div>
        </div>

        {{-- Payment History Modal --}}
        <div id="ipdHistoryModal"
            class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-[650px] p-6 relative">
                <button onclick="closeIpdHistory()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="ph ph-x text-xl"></i>
                </button>

                <div class="mb-5 flex items-center gap-3">
                    <div class="bg-pink-100 p-2 rounded-full text-pink-600">
                        <i class="ph ph-receipt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">IPD Payment History</h3>
                        <p class="text-sm text-gray-500">Transaction log for IPD No: <span id="hist_ipd_no"
                                class="font-bold text-main-color"></span></p>
                    </div>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <div class="overflow-y-auto max-h-80">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Mode</th>
                                    <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase">Amount</th>
                                    <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ipdHistoryBody" class="bg-white divide-y divide-gray-100">
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-sm text-gray-500">Loading data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5 text-right">
                    <button onclick="closeIpdHistory()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-5 py-2 rounded-lg text-sm transition-colors">Close</button>
                </div>
            </div>
        </div>

    </body>

@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script>
        // --- HISTORY MODAL LOGIC ---
        function openIpdHistory(ipdId, ipdNo) {
            $('#ipdHistoryModal').removeClass('hidden');
            $('#hist_ipd_no').text(ipdNo);
            $('#ipdHistoryBody').html(
                '<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</td></tr>'
            );

            // AJAX Call to fetch payments
            $.get("/admin/ipd-patient/payment-history/" + ipdId, function(res) {
                if (res.status === 1) {
                    let rows = '';
                    if (res.data.length === 0) {
                        rows =
                            '<tr><td colspan="4" class="text-center py-6 text-sm text-gray-500">No payment history found.</td></tr>';
                    } else {
                        res.data.forEach(function(pay) {
                            rows += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-700">${pay.date}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            ${pay.mode.name}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-bold text-green-600">₹ ${pay.amount}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="${pay.print_url}" target="_blank" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-pink-100 hover:text-pink-600 transition-colors"
                                           title="Print Receipt">
                                           <i class="ph ph-printer text-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#ipdHistoryBody').html(rows);
                } else {
                    $('#ipdHistoryBody').html(
                        '<tr><td colspan="4" class="text-center py-4 text-red-500">Failed to load history.</td></tr>'
                    );
                }
            }).fail(function() {
                $('#ipdHistoryBody').html(
                    '<tr><td colspan="4" class="text-center py-4 text-red-500">Server Error.</td></tr>');
            });
        }

        function closeIpdHistory() {
            $('#ipdHistoryModal').addClass('hidden');
        }
    </script>
    <script>
        // --- PRINT DROPDOWN LOGIC ---
        function togglePrintMenu(event, menuId) {
            event.stopPropagation(); // Click event ko parent elements tak jane se rokein

            // 1. Sabhi khule huye dropdowns band karein (taaki ek baar me ek hi khule)
            document.querySelectorAll('.print-dropdown-content').forEach(el => {
                if (el.id !== menuId) el.classList.add('hidden');
            });

            // 2. Current dropdown ko toggle karein
            const menu = document.getElementById(menuId);
            menu.classList.toggle('hidden');
        }

        // Window par kahi bhi click karne se dropdown band karein
        window.addEventListener('click', function() {
            document.querySelectorAll('.print-dropdown-content').forEach(el => {
                el.classList.add('hidden');
            });
        });

        // Dropdown ke andar click karne se wo band na ho
        document.querySelectorAll('.print-dropdown-content').forEach(el => {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
@endpush
