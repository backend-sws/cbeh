<!-- OPD Diagnosis & Investigation Orders & Nursing Workflow Module -->
<div class="bg-white p-6 rounded-lg shadow-md border border-gray-200" id="opdInvestigationsTabWrapper">
    <!-- Tab Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center space-x-2">
                <span class="p-2 bg-pink-100 text-pink-700 rounded-lg">
                    <i class="fas fa-microscope text-lg"></i>
                </span>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Investigations</h3>
                    <p class="text-xs text-gray-500">Doctor clinical diagnosis orders, diagnostic investigations & nursing sample collection workflow</p>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors"
                onclick="openAddOpdInvestigationModal()">
                <i class="fas fa-plus mr-2"></i> Add Investigation
            </button>
        </div>
    </div>

    <!-- Filter & Stats Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-5 bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs">
        <!-- Status Filter Pills -->
        <div class="flex flex-wrap items-center gap-1.5" id="opdInvStatusFilterGroup">
            <button type="button" onclick="filterOpdInvestigations('all')" class="opd-inv-filter-btn active px-3 py-1.5 rounded-md font-semibold bg-pink-600 text-white shadow-sm transition-all" data-filter="all">
                All (<span id="opd-count-all">0</span>)
            </button>
            <button type="button" onclick="filterOpdInvestigations('Pending')" class="opd-inv-filter-btn px-3 py-1.5 rounded-md font-semibold bg-white text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-300 transition-all" data-filter="Pending">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span> Pending (<span id="opd-count-pending">0</span>)
            </button>
            <button type="button" onclick="filterOpdInvestigations('Sample Collected')" class="opd-inv-filter-btn px-3 py-1.5 rounded-md font-semibold bg-white text-gray-700 hover:bg-blue-50 hover:text-blue-700 border border-gray-300 transition-all" data-filter="Sample Collected">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1"></span> Collected (<span id="opd-count-collected">0</span>)
            </button>
            <button type="button" onclick="filterOpdInvestigations('Sample Accepted')" class="opd-inv-filter-btn px-3 py-1.5 rounded-md font-semibold bg-white text-gray-700 hover:bg-purple-50 hover:text-purple-700 border border-gray-300 transition-all" data-filter="Sample Accepted">
                <span class="inline-block w-2 h-2 rounded-full bg-purple-500 mr-1"></span> Accepted (<span id="opd-count-accepted">0</span>)
            </button>
            <button type="button" onclick="filterOpdInvestigations('In Process')" class="opd-inv-filter-btn px-3 py-1.5 rounded-md font-semibold bg-white text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 border border-gray-300 transition-all" data-filter="In Process">
                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 mr-1"></span> Processing (<span id="opd-count-process">0</span>)
            </button>
            <button type="button" onclick="filterOpdInvestigations('Completed')" class="opd-inv-filter-btn px-3 py-1.5 rounded-md font-semibold bg-white text-gray-700 hover:bg-green-50 hover:text-green-700 border border-gray-300 transition-all" data-filter="Completed">
                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span> Completed (<span id="opd-count-completed">0</span>)
            </button>
        </div>

        <!-- Search Box -->
        <div class="relative w-full sm:w-64">
            <input type="text" id="opdInvSearchInput" onkeyup="searchOpdInvestigations()" placeholder="Search diagnosis or test..."
                class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-pink-500 bg-white">
            <i class="fas fa-search absolute left-2.5 top-2 text-gray-400"></i>
        </div>
    </div>

    <!-- Investigations Table Container -->
    <div id="opdInvestigationsTableContainer">
        <div class="text-center py-10">
            <i class="fas fa-spinner fa-spin text-pink-500 text-3xl mb-3"></i>
            <p class="text-gray-500 text-sm">Loading investigations...</p>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: ADD DIAGNOSIS & INVESTIGATION MODAL (Doctor / Admin) -->
<!-- ============================================================== -->
<div id="addOpdInvestigationModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-fadeIn">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-pink-600 to-rose-700 text-white">
            <div class="flex items-center space-x-2">
                <i class="fas fa-file-medical-alt text-xl"></i>
                <h3 class="text-lg font-bold">Order Investigations</h3>
            </div>
            <button type="button" onclick="closeAddOpdInvestigationModal()" class="text-white hover:text-gray-200 text-xl font-bold">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <form id="addOpdInvestigationForm" onsubmit="submitAddOpdInvestigation(event)" class="overflow-y-auto flex-1 p-6 space-y-4">
            @csrf
            <!-- Patient & Doctor Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-pink-50/70 p-3.5 rounded-lg border border-pink-200 text-xs">
                <div>
                    <span class="text-gray-500 block">Patient Name:</span>
                    <strong class="text-gray-900 text-sm">{{ $opd_patient->patient->name ?? 'Patient' }}</strong>
                    <div class="text-[11px] text-gray-500">OPD No: #{{ $opd_patient->opd_no }}</div>
                </div>
                <div>
                    <label for="opd_inv_doctor_id" class="text-gray-600 font-medium block mb-1">Prescribing Doctor *</label>
                    <select name="doctor_id" id="opd_inv_doctor_id" class="w-full border border-gray-300 rounded px-2.5 py-1.5 text-xs bg-white focus:ring-1 focus:ring-pink-500">
                        @if(isset($doctors) && $doctors->count())
                            @foreach($doctors as $doc)
                                @php
                                    $rawD = $doc->name ?? '';
                                    $clnD = $rawD ? preg_replace('/^(dr\.?\s*)+/i', '', trim($rawD)) : '';
                                @endphp
                                <option value="{{ $doc->id }}" {{ ($opd_patient->doctor_id == $doc->id) ? 'selected' : '' }}>
                                    Dr. {{ $clnD }}
                                </option>
                            @endforeach
                        @else
                            <option value="{{ $opd_patient->doctor_id }}">
                                {{ $opd_patient->doctor->name ?? 'Attending Doctor' }}
                            </option>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="opd_inv_urgency" class="text-gray-600 font-medium block mb-1">Urgency / Priority</label>
                    <select name="urgency" id="opd_inv_urgency" class="w-full border border-gray-300 rounded px-2.5 py-1.5 text-xs bg-white focus:ring-1 focus:ring-pink-500">
                        <option value="Routine" selected>Routine</option>
                        <option value="Urgent">Urgent</option>
                        <option value="STAT">STAT (Emergency)</option>
                    </select>
                </div>
            </div>

            <!-- Diagnosis Field (Doctor/Admin input) -->
            <div>
                <label for="opd_inv_diagnosis" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    <i class="fas fa-stethoscope text-pink-600 mr-1"></i> Clinical Diagnosis / Provisional Indication *
                </label>
                <input type="text" name="diagnosis" id="opd_inv_diagnosis" required
                    placeholder="e.g. Suspected Dengue Fever / Acute Tonsillitis / Hypertension Follow-up"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
                <p class="text-[11px] text-gray-500 mt-1">Specify clinical diagnosis or reason for investigation.</p>
            </div>

            <!-- Investigation Tests Section (Dynamic Multi-Row) -->
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-flask text-pink-600 mr-1"></i> Investigations / Tests Ordered
                    </label>
                    <button type="button" onclick="addOpdInvestigationRow()" class="text-xs text-pink-600 hover:text-pink-800 font-semibold flex items-center">
                        <i class="fas fa-plus-circle mr-1"></i> Add Another Test
                    </button>
                </div>

                <div id="opdInvestigationRowsContainer" class="space-y-2.5">
                    <!-- Row 1 (Default) -->
                    <div class="opd-inv-row flex flex-col sm:flex-row gap-2 items-center bg-white p-2.5 rounded-md border border-gray-200 shadow-sm">
                        <div class="flex-1 w-full">
                            <input type="text" name="tests[0][name]" required placeholder="Test Name (e.g. CBC, Lipid Profile, Chest X-Ray)"
                                class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-pink-500">
                        </div>
                        <div class="w-full sm:w-40">
                            <select name="tests[0][category]" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded bg-white focus:ring-1 focus:ring-pink-500">
                                <option value="Pathology" selected>Pathology</option>
                                <option value="Biochemistry">Biochemistry</option>
                                <option value="Microbiology">Microbiology</option>
                                <option value="Radiology">Radiology / Imaging</option>
                                <option value="Cardiology">Cardiology / ECG</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-36">
                            <input type="text" name="tests[0][sample_type]" placeholder="Sample (e.g. Blood EDTA)"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-pink-500">
                        </div>
                        <button type="button" onclick="removeOpdInvestigationRow(this)" class="text-red-500 hover:text-red-700 px-2 py-1 text-sm hidden">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Clinical Instructions / Notes -->
            <div>
                <label for="opd_inv_instruction" class="block text-xs font-semibold text-gray-700 mb-1">
                    Special Instructions / Nursing Notes
                </label>
                <textarea name="instruction" id="opd_inv_instruction" rows="2"
                    placeholder="e.g. 12-hour fasting required, collect sample before breakfast, stat reporting..."
                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"></textarea>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-3 border-t">
                <button type="button" onclick="closeAddOpdInvestigationModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" id="submitAddOpdInvBtn"
                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-sm font-semibold shadow-md flex items-center">
                    <i class="fas fa-check mr-2"></i> Save & Order Investigations
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: NURSE WORKFLOW STATUS UPDATE MODAL (Strictly Fixed User) -->
<!-- ============================================================== -->
<div id="updateOpdStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full flex flex-col overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-teal-600 to-cyan-700 text-white">
            <div class="flex items-center space-x-2">
                <i class="fas fa-tasks text-lg"></i>
                <h3 class="text-base font-bold">Update Investigation Workflow Status</h3>
            </div>
            <button type="button" onclick="closeOpdStatusModal()" class="text-white hover:text-gray-200 text-xl font-bold">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form id="updateOpdStatusForm" onsubmit="submitUpdateOpdStatus(event)" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="opdStatusModalInvId" name="investigation_id">

            <!-- Test Info Box -->
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-xs">
                <div class="text-gray-500">Test:</div>
                <div class="text-sm font-bold text-gray-800" id="opdStatusModalTestName">--</div>
                <div class="text-teal-700 mt-1" id="opdStatusModalDiagnosis">--</div>
            </div>

            <!-- New Status -->
            <div>
                <label for="opdStatusSelect" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Select Workflow Status *
                </label>
                <select name="status" id="opdStatusSelect" required onchange="onOpdStatusSelectChange(this.value)"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-teal-500">
                    <option value="Pending">⏳ Pending / Ordered</option>
                    <option value="Sample Collected">🧪 Sample Collected (Nurse)</option>
                    <option value="Sample Accepted">📥 Sample Accepted (Lab / Processing)</option>
                    <option value="In Process">⚙️ In Process</option>
                    <option value="Completed">✅ Completed / Reported</option>
                    <option value="Cancelled">❌ Cancelled</option>
                </select>
            </div>

            <!-- Staff / Nurse Name (Strictly Fixed & Readonly) -->
            <div>
                <label for="opdStatusStaffName" class="block text-xs font-semibold text-gray-700 mb-1 flex items-center justify-between">
                    <span><i class="fas fa-user-check text-teal-600 mr-1"></i> Action Performed By (Logged-in User)</span>
                    <span class="text-[10px] text-gray-500 font-normal"><i class="fas fa-lock text-gray-400 mr-0.5"></i> Fixed</span>
                </label>
                <div class="relative">
                    <input type="text" name="staff_name" id="opdStatusStaffName" readonly
                        value="{{ auth()->user() ? auth()->user()->name : 'Staff / Nurse' }}"
                        class="w-full px-3 py-2 text-xs font-bold text-gray-800 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed select-none focus:outline-none">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 pointer-events-none">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                <p class="text-[10px] text-gray-500 mt-1">Logged-in account: <strong>{{ auth()->user() ? auth()->user()->name : 'Staff' }}</strong> ({{ auth()->user() ? (auth()->user()->role ?? 'User') : 'User' }})</p>
            </div>

            <!-- Sample Type (Vial / Specimen) -->
            <div id="opdSampleTypeGroup">
                <label for="opdStatusSampleType" class="block text-xs font-medium text-gray-700 mb-1">
                    Sample / Specimen Type
                </label>
                <input type="text" name="sample_type" id="opdStatusSampleType"
                    placeholder="e.g. Blood (EDTA Purple Top), Plain Vial, Spot Urine, Swab"
                    class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-teal-500 bg-white">
            </div>

            <!-- Result Summary (shown if completed) -->
            <div id="opdResultSummaryGroup" class="hidden">
                <label for="opdStatusResultSummary" class="block text-xs font-medium text-gray-700 mb-1">
                    Test Result / Findings Summary
                </label>
                <textarea name="result_summary" id="opdStatusResultSummary" rows="2"
                    placeholder="Enter test findings or result summary..."
                    class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-teal-500 bg-white"></textarea>
            </div>

            <!-- Notes / Remarks -->
            <div>
                <label for="opdStatusNotes" class="block text-xs font-medium text-gray-700 mb-1">
                    Notes / Remarks
                </label>
                <textarea name="notes" id="opdStatusNotes" rows="2"
                    placeholder="e.g. Sample sent to lab, vial barcode #..., received in good condition..."
                    class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-teal-500 bg-white"></textarea>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-3 border-t">
                <button type="button" onclick="closeOpdStatusModal()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" id="submitOpdStatusBtn"
                    class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold shadow-md flex items-center">
                    <i class="fas fa-check mr-1.5"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- JAVASCRIPT LOGIC FOR OPD INVESTIGATIONS MODULE -->
<!-- ============================================================== -->
<script>
    let globalOpdInvestigations = [];
    let currentOpdFilter = 'all';
    let opdInvestigationRowIndex = 1;

    // Fetch and render investigations on tab load
    function fetchOpdInvestigations(opdPatientId) {
        const url = "{{ route('admin.opd.investigations.index', ':id') }}".replace(':id', opdPatientId);
        const container = document.getElementById('opdInvestigationsTableContainer');
        if (!container) return;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    globalOpdInvestigations = data.data || [];
                    updateOpdFilterCounts();
                    renderOpdInvestigationsTable();
                } else {
                    container.innerHTML = `<div class="p-6 text-center text-red-600">Failed to load investigations.</div>`;
                }
            })
            .catch(err => {
                console.error('Error fetching OPD investigations:', err);
                container.innerHTML = `<div class="p-6 text-center text-red-600">Error loading investigations: ${err.message}</div>`;
            });
    }

    // Update filter counts badges
    function updateOpdFilterCounts() {
        const total = globalOpdInvestigations.length;
        const pending = globalOpdInvestigations.filter(i => i.status === 'Pending').length;
        const collected = globalOpdInvestigations.filter(i => i.status === 'Sample Collected').length;
        const accepted = globalOpdInvestigations.filter(i => i.status === 'Sample Accepted').length;
        const process = globalOpdInvestigations.filter(i => i.status === 'In Process').length;
        const completed = globalOpdInvestigations.filter(i => i.status === 'Completed').length;

        if (document.getElementById('opd-count-all')) document.getElementById('opd-count-all').innerText = total;
        if (document.getElementById('opd-count-pending')) document.getElementById('opd-count-pending').innerText = pending;
        if (document.getElementById('opd-count-collected')) document.getElementById('opd-count-collected').innerText = collected;
        if (document.getElementById('opd-count-accepted')) document.getElementById('opd-count-accepted').innerText = accepted;
        if (document.getElementById('opd-count-process')) document.getElementById('opd-count-process').innerText = process;
        if (document.getElementById('opd-count-completed')) document.getElementById('opd-count-completed').innerText = completed;
    }

    // Filter by status pill
    function filterOpdInvestigations(status) {
        currentOpdFilter = status;
        document.querySelectorAll('.opd-inv-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === status) {
                btn.classList.add('bg-pink-600', 'text-white');
                btn.classList.remove('bg-white', 'text-gray-700');
            } else {
                btn.classList.remove('bg-pink-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            }
        });
        renderOpdInvestigationsTable();
    }

    // Filter by search text
    function searchOpdInvestigations() {
        renderOpdInvestigationsTable();
    }

    // Render the table
    function renderOpdInvestigationsTable() {
        const container = document.getElementById('opdInvestigationsTableContainer');
        if (!container) return;

        const searchQuery = (document.getElementById('opdInvSearchInput')?.value || '').toLowerCase().trim();

        let filtered = globalOpdInvestigations.filter(item => {
            const matchesFilter = (currentOpdFilter === 'all') || (item.status === currentOpdFilter);
            const matchesSearch = !searchQuery ||
                (item.investigation_name && item.investigation_name.toLowerCase().includes(searchQuery)) ||
                (item.diagnosis && item.diagnosis.toLowerCase().includes(searchQuery)) ||
                (item.category && item.category.toLowerCase().includes(searchQuery)) ||
                (item.ordered_by_name && item.ordered_by_name.toLowerCase().includes(searchQuery));
            return matchesFilter && matchesSearch;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-flask text-2xl"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-700 mb-1">No Diagnostic Investigations Found</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto mb-4">
                        ${globalOpdInvestigations.length === 0 
                            ? 'Doctors and Admins can click "+ Add Investigation" to prescribe diagnosis orders and tests.' 
                            : 'No investigations matched your filter or search query.'}
                    </p>
                    <button type="button" onclick="openAddOpdInvestigationModal()"
                        class="inline-flex items-center px-3.5 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 shadow">
                        <i class="fas fa-plus mr-1"></i> Add Investigation
                    </button>
                </div>
            `;
            return;
        }

        let html = `
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Diagnosis & Notes</th>
                            <th class="px-4 py-3 text-left">Investigation / Test</th>
                            <th class="px-4 py-3 text-left">Advised By</th>
                            <th class="px-4 py-3 text-left">Status & Sample Details</th>
                            <th class="px-4 py-3 text-center">Action / Workflow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
        `;

        filtered.forEach((inv, idx) => {
            // Clean doctor name
            let rawDoc = inv.doctor?.name || inv.ordered_by_name || '';
            let cleanDoc = rawDoc ? rawDoc.trim().replace(/^(dr\.?\s*)+/i, '') : '';
            let displayDoc = cleanDoc ? ('Dr. ' + cleanDoc) : 'Attending Doctor';

            // Format date
            let orderDate = inv.created_at ? new Date(inv.created_at).toLocaleString('en-GB', {
                day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : '--';

            // Urgency badge
            let urgencyBadge = '';
            if (inv.urgency === 'STAT') {
                urgencyBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 animate-pulse"><i class="fas fa-bolt mr-0.5"></i>STAT</span>`;
            } else if (inv.urgency === 'Urgent') {
                urgencyBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Urgent</span>`;
            } else {
                urgencyBadge = `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700">Routine</span>`;
            }

            // Status Badge
            let statusBadge = '';
            if (inv.status === 'Pending') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-amber-100 text-amber-800"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>Pending</span>`;
            } else if (inv.status === 'Sample Collected') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-blue-100 text-blue-800"><i class="fas fa-vial mr-1 text-blue-600"></i>Collected</span>`;
            } else if (inv.status === 'Sample Accepted') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-purple-100 text-purple-800"><i class="fas fa-check-double mr-1 text-purple-600"></i>Accepted</span>`;
            } else if (inv.status === 'In Process') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-indigo-100 text-indigo-800"><i class="fas fa-microscope mr-1 text-indigo-600"></i>Processing</span>`;
            } else if (inv.status === 'Completed') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1 text-green-600"></i>Completed</span>`;
            } else if (inv.status === 'Cancelled') {
                statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold text-xs bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1 text-red-600"></i>Cancelled</span>`;
            }

            // Nursing workflow info
            let workflowInfo = '';
            if (inv.sample_collected_by) {
                let collectedAt = inv.sample_collected_at ? new Date(inv.sample_collected_at).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) : '';
                workflowInfo += `<div class="text-[11px] text-gray-500 mt-1"><i class="fas fa-user-nurse mr-1 text-blue-500"></i>Collected: <strong class="text-gray-700">${inv.sample_collected_by}</strong> ${collectedAt ? '(' + collectedAt + ')' : ''}</div>`;
            }
            if (inv.sample_accepted_by) {
                let acceptedAt = inv.sample_accepted_at ? new Date(inv.sample_accepted_at).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) : '';
                workflowInfo += `<div class="text-[11px] text-gray-500"><i class="fas fa-flask mr-1 text-purple-500"></i>Accepted: <strong class="text-gray-700">${inv.sample_accepted_by}</strong> ${acceptedAt ? '(' + acceptedAt + ')' : ''}</div>`;
            }
            if (inv.result_summary) {
                workflowInfo += `<div class="text-[11px] text-emerald-800 bg-emerald-50 px-2 py-1 rounded border border-emerald-200 mt-1"><i class="fas fa-clipboard-check mr-1 text-emerald-600"></i><strong>Result:</strong> ${inv.result_summary}</div>`;
            }

            // Quick nurse action button
            let quickActionBtn = '';
            if (inv.status === 'Pending') {
                quickActionBtn = `
                    <button type="button" onclick="quickUpdateOpdStatus(${inv.id}, 'Sample Collected')"
                        class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow-sm transition-colors mr-1" title="Mark Sample Collected">
                        <i class="fas fa-vial mr-1"></i> Collect
                    </button>
                `;
            } else if (inv.status === 'Sample Collected') {
                quickActionBtn = `
                    <button type="button" onclick="quickUpdateOpdStatus(${inv.id}, 'Sample Accepted')"
                        class="inline-flex items-center px-2.5 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded shadow-sm transition-colors mr-1" title="Accept Sample in Lab">
                        <i class="fas fa-check-double mr-1"></i> Accept
                    </button>
                `;
            } else if (inv.status === 'Sample Accepted') {
                quickActionBtn = `
                    <button type="button" onclick="quickUpdateOpdStatus(${inv.id}, 'In Process')"
                        class="inline-flex items-center px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded shadow-sm transition-colors mr-1" title="Mark In Process">
                        <i class="fas fa-microscope mr-1"></i> Process
                    </button>
                `;
            } else if (inv.status === 'In Process') {
                quickActionBtn = `
                    <button type="button" onclick="openOpdStatusModalWithId(${inv.id}, 'Completed')"
                        class="inline-flex items-center px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded shadow-sm transition-colors mr-1" title="Mark Completed / Enter Findings">
                        <i class="fas fa-check mr-1"></i> Complete
                    </button>
                `;
            }

            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-semibold text-gray-500">${idx + 1}</td>
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900 flex items-center gap-1.5">
                            ${inv.diagnosis || '<span class="text-gray-400 italic font-normal">Not specified</span>'}
                            ${urgencyBadge}
                        </div>
                        ${inv.instruction ? `<div class="text-[11px] text-gray-500 italic mt-0.5"><i class="fas fa-info-circle mr-1 text-pink-400"></i>${inv.instruction}</div>` : ''}
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-pink-700 text-sm">${inv.investigation_name}</div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <span class="inline-block px-1.5 py-0.2 rounded text-[10px] bg-slate-100 text-slate-700 border border-slate-200">${inv.category || 'Pathology'}</span>
                            ${inv.sample_type ? `<span class="inline-block px-1.5 py-0.2 rounded text-[10px] bg-purple-50 text-purple-700 border border-purple-200"><i class="fas fa-vial mr-0.5 text-purple-400"></i>${inv.sample_type}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">${displayDoc}</div>
                        <div class="text-[11px] text-gray-500">${orderDate}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div>${statusBadge}</div>
                        ${workflowInfo}
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            ${quickActionBtn}
                            <button type="button" onclick="openOpdStatusModalWithId(${inv.id})"
                                class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded mr-1" title="Update Status / Add Notes">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" onclick="deleteOpdInvestigation(${inv.id})"
                                class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        container.innerHTML = html;
    }

    // Modal 1 controls: Add Investigation
    function openAddOpdInvestigationModal() {
        const modal = document.getElementById('addOpdInvestigationModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('opd_inv_diagnosis')?.focus();
        }
    }

    function closeAddOpdInvestigationModal() {
        const modal = document.getElementById('addOpdInvestigationModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function addOpdInvestigationRow() {
        const container = document.getElementById('opdInvestigationRowsContainer');
        const idx = opdInvestigationRowIndex++;
        const div = document.createElement('div');
        div.className = 'opd-inv-row flex flex-col sm:flex-row gap-2 items-center bg-white p-2.5 rounded-md border border-gray-200 shadow-sm animate-fadeIn';
        div.innerHTML = `
            <div class="flex-1 w-full">
                <input type="text" name="tests[${idx}][name]" required placeholder="Test Name (e.g. CBC, Lipid Profile, Chest X-Ray)"
                    class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-pink-500">
            </div>
            <div class="w-full sm:w-40">
                <select name="tests[${idx}][category]" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded bg-white focus:ring-1 focus:ring-pink-500">
                    <option value="Pathology" selected>Pathology</option>
                    <option value="Biochemistry">Biochemistry</option>
                    <option value="Microbiology">Microbiology</option>
                    <option value="Radiology">Radiology / Imaging</option>
                    <option value="Cardiology">Cardiology / ECG</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="w-full sm:w-36">
                <input type="text" name="tests[${idx}][sample_type]" placeholder="Sample (e.g. Blood EDTA)"
                    class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-pink-500">
            </div>
            <button type="button" onclick="removeOpdInvestigationRow(this)" class="text-red-500 hover:text-red-700 px-2 py-1 text-sm">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function removeOpdInvestigationRow(btn) {
        const row = btn.closest('.opd-inv-row');
        if (row) row.remove();
    }

    // Submit Add Investigation Form
    function submitAddOpdInvestigation(e) {
        e.preventDefault();
        const form = document.getElementById('addOpdInvestigationForm');
        const submitBtn = document.getElementById('submitAddOpdInvBtn');
        const originalHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

        const formData = new FormData(form);
        const opdPatientId = {{ $opd_patient->id }};
        const url = "{{ route('admin.opd.investigations.store', ':id') }}".replace(':id', opdPatientId);

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showOpdNotification(data.message || 'Investigations ordered successfully', 'success');
                closeAddOpdInvestigationModal();
                form.reset();
                fetchOpdInvestigations(opdPatientId);
            } else {
                showOpdNotification(data.message || 'Failed to save investigations', 'error');
            }
        })
        .catch(err => {
            showOpdNotification('Error: ' + err.message, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        });
    }

    // Modal 2 controls: Status Update (Nurse/Lab Workflow)
    function openOpdStatusModalWithId(id, defaultStatus = null) {
        const inv = globalOpdInvestigations.find(i => i.id === id);
        if (!inv) return;

        document.getElementById('opdStatusModalInvId').value = inv.id;
        document.getElementById('opdStatusModalTestName').innerText = inv.investigation_name + ' (' + (inv.category || 'Pathology') + ')';
        document.getElementById('opdStatusModalDiagnosis').innerText = 'Diagnosis: ' + (inv.diagnosis || 'General Investigation');
        document.getElementById('opdStatusSampleType').value = inv.sample_type || '';
        document.getElementById('opdStatusNotes').value = '';
        if (document.getElementById('opdStatusStaffName')) {
            document.getElementById('opdStatusStaffName').value = "{{ addslashes(auth()->user() ? auth()->user()->name : 'Staff / Nurse') }}";
        }

        const select = document.getElementById('opdStatusSelect');
        if (select) {
            select.value = defaultStatus || inv.status;
            onOpdStatusSelectChange(select.value);
        }

        const modal = document.getElementById('updateOpdStatusModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeOpdStatusModal() {
        const modal = document.getElementById('updateOpdStatusModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function onOpdStatusSelectChange(status) {
        const resGroup = document.getElementById('opdResultSummaryGroup');
        if (resGroup) {
            if (status === 'Completed') {
                resGroup.classList.remove('hidden');
            } else {
                resGroup.classList.add('hidden');
            }
        }
    }

    // Quick status update button
    function quickUpdateOpdStatus(id, newStatus) {
        const opdPatientId = {{ $opd_patient->id }};
        const staffName = "{{ auth()->user() ? auth()->user()->name : 'Nurse' }}";
        const url = "{{ route('admin.opd.investigations.update_status', ':id') }}".replace(':id', id);

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('status', newStatus);
        formData.append('staff_name', staffName);

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showOpdNotification(`Status updated to "${newStatus}"`, 'success');
                fetchOpdInvestigations(opdPatientId);
            } else {
                showOpdNotification(data.message || 'Status update failed', 'error');
            }
        })
        .catch(err => {
            showOpdNotification('Error: ' + err.message, 'error');
        });
    }

    // Submit full status update modal
    function submitUpdateOpdStatus(e) {
        e.preventDefault();
        const form = document.getElementById('updateOpdStatusForm');
        const id = document.getElementById('opdStatusModalInvId').value;
        const submitBtn = document.getElementById('submitOpdStatusBtn');
        const originalHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Updating...';

        const formData = new FormData(form);
        const url = "{{ route('admin.opd.investigations.update_status', ':id') }}".replace(':id', id);
        const opdPatientId = {{ $opd_patient->id }};

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showOpdNotification(data.message || 'Status updated successfully', 'success');
                closeOpdStatusModal();
                fetchOpdInvestigations(opdPatientId);
            } else {
                showOpdNotification(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(err => {
            showOpdNotification('Error: ' + err.message, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        });
    }

    // Delete Investigation
    function deleteOpdInvestigation(id) {
        if (!confirm('Are you sure you want to delete this investigation order?')) return;

        const opdPatientId = {{ $opd_patient->id }};
        const url = "{{ route('admin.opd.investigations.destroy', ':id') }}".replace(':id', id);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showOpdNotification('Investigation order deleted successfully', 'success');
                fetchOpdInvestigations(opdPatientId);
            } else {
                showOpdNotification(data.message || 'Failed to delete investigation', 'error');
            }
        })
        .catch(err => {
            showOpdNotification('Error: ' + err.message, 'error');
        });
    }

    // Notification toast helper
    function showOpdNotification(msg, type = 'success') {
        const notif = document.createElement('div');
        notif.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} z-50 flex items-center space-x-2 animate-fadeIn`;
        notif.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-lg"></i>
            <span class="text-sm font-medium">${msg}</span>
        `;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 3500);
    }

    // Initialize when DOM loaded
    document.addEventListener('DOMContentLoaded', function() {
        const opdPatientId = {{ $opd_patient->id }};
        fetchOpdInvestigations(opdPatientId);
    });
</script>
