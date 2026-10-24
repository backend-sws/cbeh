<!-- OPD Progress Notes Section & Modals (Styled identically to Prescriptions & IPD Module) -->
<div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <!-- Tab Header -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="text-lg font-medium text-gray-700">Clinical Progress Notes</h3>
            <p class="text-xs text-gray-500">Outpatient clinical observations, examination findings, progress notes and chronological addendums</p>
        </div>
        <button type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700"
            onclick="openAddOpdProgressNoteModal()">
            <i class="fas fa-plus mr-1"></i> Add Progress Note
        </button>
    </div>

    @if (isset($progress_notes) && $progress_notes->count())
        <!-- Summary Table -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Note Title</th>
                        <th class="px-4 py-2 text-left">Recorded By</th>
                        <th class="px-4 py-2 text-left">Date & Time</th>
                        <th class="px-4 py-2 text-left">Prescription</th>
                        <th class="px-4 py-2 text-left">Addendums</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($progress_notes as $index => $pNote)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 font-medium text-gray-900">
                                <a href="#opd-pnote-detail-{{ $pNote->id }}" class="text-pink-600 hover:underline">
                                    {{ $pNote->title }}
                                </a>
                            </td>
                            <td class="px-4 py-2 text-gray-700">{{ $pNote->staff_name }}</td>
                            <td class="px-4 py-2 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($pNote->date_time)->format('jS M, Y h:i A') }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if($pNote->medicines && $pNote->medicines->count() > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-100 text-pink-800">
                                        <i class="fas fa-pills mr-1"></i> {{ $pNote->medicines->count() }} Med(s)
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if($pNote->addendums && $pNote->addendums->count() > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-history mr-1"></i> {{ $pNote->addendums->count() }} Addendum(s)
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center whitespace-nowrap">
                                <!-- Print Button -->
                                <a href="{{ route('admin.opd.progress_notes.print', $pNote->id) }}" target="_blank"
                                    class="inline-flex items-center px-2.5 py-1 bg-green-600 text-white text-xs font-semibold rounded shadow hover:bg-green-700 mr-1">
                                    <i class="fas fa-print mr-1"></i> Print
                                </a>

                                <!-- Add Addendum Button (Instead of Edit) -->
                                <button type="button"
                                    class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded mr-1 hover:bg-amber-200"
                                    onclick="openOpdAddendumModal({{ $pNote->id }}, '{{ addslashes($pNote->title) }}', '{{ addslashes($pNote->staff_name) }}', '{{ \Carbon\Carbon::parse($pNote->date_time)->format('jS M, Y h:i A') }}')">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Addendum
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.opd.progress_notes.destroy', $pNote->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Delete this progress note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded hover:bg-red-200">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Detailed Cards / Timeline View -->
        <div class="space-y-6 pt-4 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Detailed Notes History</h4>
            @foreach($progress_notes as $pNote)
                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm" id="opd-pnote-detail-{{ $pNote->id }}">
                    <!-- Card Top Header -->
                    <div class="flex flex-wrap items-center justify-between pb-3 mb-3 border-b border-gray-100 gap-2">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-pink-100 text-pink-800">
                                <i class="fas fa-file-medical-alt mr-1"></i> {{ $pNote->title }}
                            </span>
                            <span class="text-xs text-gray-600 font-medium">
                                <i class="fas fa-user-md text-gray-400 mr-1"></i> {{ $pNote->staff_name }}
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="far fa-clock text-gray-400 mr-1"></i> {{ \Carbon\Carbon::parse($pNote->date_time)->format('jS M, Y h:i A') }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button"
                                onclick="openOpdAddendumModal({{ $pNote->id }}, '{{ addslashes($pNote->title) }}', '{{ addslashes($pNote->staff_name) }}', '{{ \Carbon\Carbon::parse($pNote->date_time)->format('jS M, Y h:i A') }}')"
                                class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-semibold rounded border border-amber-300 hover:bg-amber-100">
                                <i class="fas fa-plus-circle mr-1 text-amber-600"></i> Add Addendum
                            </button>
                            <a href="{{ route('admin.opd.progress_notes.print', $pNote->id) }}" target="_blank"
                                class="inline-flex items-center px-2.5 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                                <i class="fas fa-print mr-1"></i> Print Slip
                            </a>
                        </div>
                    </div>

                    <!-- Clinical Blank Box Note -->
                    <div class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed bg-gray-50 p-4 rounded border border-gray-150">
                        {{ $pNote->note }}
                    </div>

                    <!-- Attached Prescriptions -->
                    @if($pNote->medicines && $pNote->medicines->count() > 0)
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center">
                                <i class="fas fa-pills mr-1.5 text-pink-600"></i> Prescribed Medicines
                            </h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-8">#</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-28">Category</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold">Medicine Name</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-24">Dose</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-20">Route</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-24">Frequency</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold w-24">Duration</th>
                                            <th class="px-2 py-1.5 text-left text-gray-500 font-semibold">Instructions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($pNote->medicines as $mIdx => $med)
                                            <tr>
                                                <td class="px-2 py-1.5 text-gray-400 text-center">{{ $mIdx + 1 }}</td>
                                                <td class="px-2 py-1.5 text-gray-600">{{ $med->category->medicine_category ?? '-' }}</td>
                                                <td class="px-2 py-1.5 font-bold text-gray-800">{{ $med->medicine_name }}</td>
                                                <td class="px-2 py-1.5 font-medium text-gray-700">{{ $med->dosage ?? '-' }}</td>
                                                <td class="px-2 py-1.5 text-gray-600">{{ $med->route ?? '-' }}</td>
                                                <td class="px-2 py-1.5 font-bold text-pink-700">{{ $med->dose_interval ?? '-' }}</td>
                                                <td class="px-2 py-1.5 text-gray-600">{{ $med->duration ?? '-' }}</td>
                                                <td class="px-2 py-1.5 text-gray-600">{{ $med->instructions ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Addendums Section -->
                    @if($pNote->addendums && $pNote->addendums->count() > 0)
                        <div class="mt-4 pt-3 border-t border-gray-100 space-y-3">
                            <h5 class="text-xs font-bold text-amber-700 uppercase tracking-wider flex items-center">
                                <i class="fas fa-history mr-1.5 text-amber-500"></i> Addendums ({{ $pNote->addendums->count() }})
                            </h5>
                            @foreach($pNote->addendums as $addendum)
                                <div class="bg-amber-50 border-l-4 border-amber-400 p-3.5 rounded-r">
                                    <div class="flex items-center justify-between text-xs text-amber-900 font-semibold mb-1">
                                        <span><i class="fas fa-pen-nib mr-1 text-amber-600"></i> Addendum by {{ $addendum->staff_name }}</span>
                                        <span class="text-amber-700 font-normal">{{ \Carbon\Carbon::parse($addendum->date_time)->format('jS M, Y h:i A') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-800 whitespace-pre-wrap mt-1">
                                        {{ $addendum->note }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-notes-medical text-4xl text-gray-300 mb-4"></i>
            <p>No progress notes found for this patient.</p>
        </div>
    @endif
</div>

<!-- ==========================================
     Modal 1: Add OPD Progress Note Modal
=============================================== -->
<div id="addOpdProgressNoteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-notes-medical mr-2 text-pink-600"></i>
                <span>Add Clinical Progress Note</span>
            </h2>
            <button class="text-gray-400 hover:text-gray-600 text-2xl" onclick="closeAddOpdProgressNoteModal()">
                &times;
            </button>
        </div>

        <!-- Modal Form Body -->
        <form id="opdProgressNoteForm" action="{{ route('admin.opd.progress_notes.store', $opd_patient->id) }}" method="POST"
            class="overflow-y-auto flex-1 p-6">
            @csrf

            <!-- Basic Information Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Note Title: <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="opd_pn_title" list="opdProgressNoteTitleSuggestions"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                        placeholder="e.g. Clinical Follow-up Note" required>
                    <datalist id="opdProgressNoteTitleSuggestions">
                        <option value="OPD Consultation Note">
                        <option value="Clinical Follow-up Note">
                        <option value="Treatment Response Assessment">
                        <option value="Diagnostic Review Note">
                        <option value="Specialist Opinion Note">
                        <option value="Emergency Evaluation Note">
                        <option value="Routine Checkup Progress">
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Date & Time: <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="date_time" id="opd_pn_datetime"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center justify-between">
                        <span>Staff / Doctor Name: <span class="text-red-500">*</span></span>
                        <span class="text-xs text-gray-400 font-normal"><i class="fas fa-lock mr-0.5 text-gray-400"></i> Fixed (Logged-in User)</span>
                    </label>
                    <input type="text" name="staff_name" id="opd_pn_staff_name"
                        value="{{ auth()->user()->name ?? 'Doctor' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700 font-semibold cursor-not-allowed text-sm focus:outline-none focus:ring-0 select-none"
                        readonly required>
                </div>
            </div>

            <!-- Blank Box: Clinical Progress Note -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Clinical Progress Note (Blank Box) <span class="text-red-500">*</span>
                </label>
                <textarea name="note" id="opd_pn_note" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                    placeholder="Enter clinical observations, patient condition, examination findings, vitals summary, and treatment response..." required></textarea>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Prescription Items Section -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-sm font-medium text-gray-700">Prescription Items (Optional)</h3>
                    <button type="button" onclick="addOpdProgressNoteMedicineRow()"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200" id="opdPnPrescriptionTbl">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-8">#</th>
                                <th scope="col" class="hidden px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">Category</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="min-width: 190px;">Medicine Name</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">Dose</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Route</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">Frequency</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">Duration</th>
                                <th scope="col" class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="min-width: 140px;">Instructions</th>
                                <th scope="col" class="px-2 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-10">Action</th>
                            </tr>
                        </thead>
                        <tbody id="opdPnMedicineTableBody" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center opd-pn-row-number" data-item-number="1">1</td>
                                <td class="hidden px-2 py-2 whitespace-nowrap">
                                    <select name="category_id[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-cat-select" onchange="filterOpdPnMedicineRow(this)">
                                        <option value="">All</option>
                                        @if(isset($medicine_categories))
                                            @foreach ($medicine_categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->medicine_category }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap" style="min-width: 190px;">
                                    <input type="text" name="medicine_name[]" list="opd_pn_med_list_1"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-med-input font-medium"
                                        placeholder="Search or type medicine..." autocomplete="off">
                                    <datalist id="opd_pn_med_list_1" class="opd-pn-med-datalist">
                                        @if(isset($medicines))
                                            @foreach ($medicines as $m)
                                                <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                                            @endforeach
                                        @endif
                                    </datalist>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                    <input type="text" name="dosage[]" list="opdPnDoseMasterList"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-dosage-input font-medium"
                                        placeholder="e.g. 500 mg, 1 Tab" autocomplete="off">
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap" style="min-width: 95px;">
                                    <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-route-input font-medium">
                                        <option value="Oral" selected>Oral</option>
                                        <option value="IV">IV</option>
                                        <option value="IM">IM</option>
                                        <option value="SC">SC</option>
                                        <option value="Inhalation">Inhalation</option>
                                        <option value="Topical">Topical</option>
                                        <option value="Sublingual">Sublingual</option>
                                        <option value="Eye/Ear Drops">Eye/Ear Drops</option>
                                        <option value="PR">PR</option>
                                    </select>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                    <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-freq-input font-semibold">
                                        <option value="OD">OD (Once Daily)</option>
                                        <option value="BD" selected>BD (Twice Daily)</option>
                                        <option value="TDS">TDS (Thrice Daily)</option>
                                        <option value="QID">QID (4 Times Daily)</option>
                                        <option value="SOS">SOS (As Needed)</option>
                                        <option value="STAT">STAT (Immediate Once)</option>
                                        <option value="HS">HS (At Bedtime)</option>
                                        <option value="Q4H">Q4H (Every 4 Hours)</option>
                                        <option value="Q6H">Q6H (Every 6 Hours)</option>
                                        <option value="Q8H">Q8H (Every 8 Hours)</option>
                                        <option value="Weekly">Weekly</option>
                                    </select>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                    <input type="text" name="duration[]" list="opdPnDurationMasterList"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-duration-input"
                                        placeholder="e.g. 5 Days" value="5 Days" autocomplete="off">
                                </td>
                                <td class="px-2 py-2" style="min-width: 130px;">
                                    <input type="text" name="instructions[]" placeholder="Remarks / Instructions..."
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-instruction-input">
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-center">
                                    <button type="button" onclick="deleteOpdProgressNoteMedicineRow(this)"
                                        class="text-red-500 hover:text-red-700 p-1" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Master Datalists -->
                <datalist id="opdPnMasterMedicineList">
                    @if(isset($medicines))
                        @foreach ($medicines as $m)
                            <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                        @endforeach
                    @endif
                </datalist>

                <datalist id="opdPnDoseMasterList">
                    <option value="1 Tab">1 Tab</option>
                    <option value="1 Cap">1 Cap</option>
                    <option value="500 mg">500 mg</option>
                    <option value="250 mg">250 mg</option>
                    <option value="650 mg">650 mg</option>
                    <option value="1 GM">1 GM</option>
                    <option value="5 ml">5 ml</option>
                    <option value="10 ml">10 ml</option>
                    <option value="1 Puff">1 Puff</option>
                    <option value="1 Drop">1 Drop</option>
                    <option value="2 Drops">2 Drops</option>
                    <option value="1 Ampoule">1 Ampoule</option>
                </datalist>

                <datalist id="opdPnDurationMasterList">
                    <option value="1 Day">1 Day</option>
                    <option value="2 Days">2 Days</option>
                    <option value="3 Days">3 Days</option>
                    <option value="5 Days">5 Days</option>
                    <option value="7 Days">7 Days</option>
                    <option value="10 Days">10 Days</option>
                    <option value="14 Days">14 Days</option>
                    <option value="1 Month">1 Month</option>
                    <option value="Ongoing">Ongoing</option>
                </datalist>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAddOpdProgressNoteModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700">
                    Save Progress Note
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     Modal 2: Add Addendum Modal (Medical-Legal Standard: Instead of Edit)
=============================================== -->
<div id="addOpdProgressNoteAddendumModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-amber-600"></i>
                <span>Add Clinical Addendum</span>
            </h2>
            <button class="text-gray-400 hover:text-gray-600 text-2xl" onclick="closeOpdAddendumModal()">
                &times;
            </button>
        </div>

        <!-- Form Body -->
        <form id="opdProgressNoteAddendumForm" method="POST" class="overflow-y-auto flex-1 p-6 space-y-4">
            @csrf

            <!-- Original Note Reference Box -->
            <div class="bg-amber-50 border border-amber-200 rounded-md p-3 text-xs">
                <div class="font-bold text-amber-900 mb-1 flex items-center justify-between">
                    <span id="opdAddendumOrigTitle">Original Note</span>
                    <span id="opdAddendumOrigMeta" class="text-amber-700 font-normal">--</span>
                </div>
                <p class="text-gray-600 italic">Medical notes cannot be modified after entry. This addendum will be permanently linked beneath the note with its own timestamp and author.</p>
            </div>

            <!-- Addendum Meta: Date/Time and Staff Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Addendum Date & Time: <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="date_time" id="opd_addendum_datetime"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center justify-between">
                        <span>Staff / Doctor Name: <span class="text-red-500">*</span></span>
                        <span class="text-xs text-gray-400 font-normal"><i class="fas fa-lock mr-0.5 text-gray-400"></i> Fixed (Logged-in User)</span>
                    </label>
                    <input type="text" name="staff_name" id="opd_addendum_staff_name"
                        value="{{ auth()->user()->name ?? 'Doctor' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700 font-semibold cursor-not-allowed text-sm focus:outline-none focus:ring-0 select-none"
                        readonly required>
                </div>
            </div>

            <!-- Blank Box for Addendum Note -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Addendum Note <span class="text-red-500">*</span>
                </label>
                <textarea name="note" id="opd_addendum_note" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                    placeholder="Enter additional findings, clarification, response to treatment, or follow-up note..." required></textarea>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeOpdAddendumModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700">
                    Save Addendum
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     Global Scripts for OPD Progress Notes & Addendums
=============================================== -->
<script>
    function openAddOpdProgressNoteModal() {
        const modal = document.getElementById('addOpdProgressNoteModal');
        if (!modal) return;

        // Pre-fill local date/time YYYY-MM-DDTHH:mm
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const formattedLocal = `${year}-${month}-${day}T${hours}:${minutes}`;

        const dtInput = document.getElementById('opd_pn_datetime');
        if (dtInput) dtInput.value = formattedLocal;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    window.openAddOpdProgressNoteModal = openAddOpdProgressNoteModal;

    function closeAddOpdProgressNoteModal() {
        const modal = document.getElementById('addOpdProgressNoteModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    window.closeAddOpdProgressNoteModal = closeAddOpdProgressNoteModal;

    function openOpdAddendumModal(noteId, title, staffName, dateTime) {
        const modal = document.getElementById('addOpdProgressNoteAddendumModal');
        if (!modal) return;

        const form = document.getElementById('opdProgressNoteAddendumForm');
        if (form) {
            form.action = "{{ url('admin/opd/progress-notes') }}/" + noteId + "/addendum";
            form.reset();
            const staffInput = document.getElementById('opd_addendum_staff_name');
            if (staffInput) staffInput.value = "{{ addslashes(auth()->user()->name ?? 'Doctor') }}";
        }

        const origTitleEl = document.getElementById('opdAddendumOrigTitle');
        if (origTitleEl) origTitleEl.textContent = title;

        const origMetaEl = document.getElementById('opdAddendumOrigMeta');
        if (origMetaEl) origMetaEl.textContent = `Recorded by ${staffName} on ${dateTime}`;

        // Set default datetime to now
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const formattedLocal = `${year}-${month}-${day}T${hours}:${minutes}`;

        const dtInput = document.getElementById('opd_addendum_datetime');
        if (dtInput) dtInput.value = formattedLocal;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    window.openOpdAddendumModal = openOpdAddendumModal;

    function closeOpdAddendumModal() {
        const modal = document.getElementById('addOpdProgressNoteAddendumModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    window.closeOpdAddendumModal = closeOpdAddendumModal;

    let opdPnRowCounter = 1;

    function addOpdProgressNoteMedicineRow() {
        const tbody = document.getElementById('opdPnMedicineTableBody');
        if (!tbody) return;

        opdPnRowCounter++;
        const currentCount = tbody.querySelectorAll('tr').length + 1;
        const datalistId = `opd_pn_med_list_${opdPnRowCounter}`;

        let catOptions = '<option value="">All</option>';
        @if(isset($medicine_categories))
            @foreach ($medicine_categories as $cat)
                catOptions += `<option value="{{ $cat->id }}">{{ addslashes($cat->medicine_category) }}</option>`;
            @endforeach
        @endif

        const master = document.getElementById('opdPnMasterMedicineList');
        const masterHtml = master ? master.innerHTML : '';

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center opd-pn-row-number" data-item-number="${currentCount}">${currentCount}</td>
            <td class="hidden px-2 py-2 whitespace-nowrap">
                <select name="category_id[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-cat-select" onchange="filterOpdPnMedicineRow(this)">
                    ${catOptions}
                </select>
            </td>
            <td class="px-2 py-2 whitespace-nowrap" style="min-width: 190px;">
                <input type="text" name="medicine_name[]" list="${datalistId}"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-med-input font-medium"
                    placeholder="Search or type medicine..." autocomplete="off">
                <datalist id="${datalistId}" class="opd-pn-med-datalist">
                    ${masterHtml}
                </datalist>
            </td>
            <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                <input type="text" name="dosage[]" list="opdPnDoseMasterList"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-dosage-input font-medium"
                    placeholder="e.g. 500 mg, 1 Tab" autocomplete="off">
            </td>
            <td class="px-2 py-2 whitespace-nowrap" style="min-width: 95px;">
                <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-route-input font-medium">
                    <option value="Oral" selected>Oral</option>
                    <option value="IV">IV</option>
                    <option value="IM">IM</option>
                    <option value="SC">SC</option>
                    <option value="Inhalation">Inhalation</option>
                    <option value="Topical">Topical</option>
                    <option value="Sublingual">Sublingual</option>
                    <option value="Eye/Ear Drops">Eye/Ear Drops</option>
                    <option value="PR">PR</option>
                </select>
            </td>
            <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-pink-500 opd-pn-freq-input font-semibold">
                    <option value="OD">OD (Once Daily)</option>
                    <option value="BD" selected>BD (Twice Daily)</option>
                    <option value="TDS">TDS (Thrice Daily)</option>
                    <option value="QID">QID (4 Times Daily)</option>
                    <option value="SOS">SOS (As Needed)</option>
                    <option value="STAT">STAT (Immediate Once)</option>
                    <option value="HS">HS (At Bedtime)</option>
                    <option value="Q4H">Q4H (Every 4 Hours)</option>
                    <option value="Q6H">Q6H (Every 6 Hours)</option>
                    <option value="Q8H">Q8H (Every 8 Hours)</option>
                    <option value="Weekly">Weekly</option>
                </select>
            </td>
            <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                <input type="text" name="duration[]" list="opdPnDurationMasterList"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-duration-input"
                    placeholder="e.g. 5 Days" value="5 Days" autocomplete="off">
            </td>
            <td class="px-2 py-2" style="min-width: 130px;">
                <input type="text" name="instructions[]" placeholder="Remarks / Instructions..."
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-pink-500 opd-pn-instruction-input">
            </td>
            <td class="px-2 py-2 whitespace-nowrap text-center">
                <button type="button" onclick="deleteOpdProgressNoteMedicineRow(this)"
                    class="text-red-500 hover:text-red-700 p-1" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        reindexOpdPnRows();
    }
    window.addOpdProgressNoteMedicineRow = addOpdProgressNoteMedicineRow;

    function deleteOpdProgressNoteMedicineRow(btn) {
        const tbody = document.getElementById('opdPnMedicineTableBody');
        if (!tbody) return;
        const rows = tbody.querySelectorAll('tr');
        if (rows.length <= 1) {
            // Reset input values instead of removing the only row
            const tr = rows[0];
            tr.querySelectorAll('input').forEach(i => i.value = '');
            tr.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
            const routeSel = tr.querySelector('.opd-pn-route-input');
            if (routeSel) routeSel.value = 'Oral';
            const freqSel = tr.querySelector('.opd-pn-freq-input');
            if (freqSel) freqSel.value = 'BD';
            const durInput = tr.querySelector('.opd-pn-duration-input');
            if (durInput) durInput.value = '5 Days';
            return;
        }
        btn.closest('tr').remove();
        reindexOpdPnRows();
    }
    window.deleteOpdProgressNoteMedicineRow = deleteOpdProgressNoteMedicineRow;

    function reindexOpdPnRows() {
        const tbody = document.getElementById('opdPnMedicineTableBody');
        if (!tbody) return;
        tbody.querySelectorAll('tr').forEach((row, idx) => {
            const numEl = row.querySelector('.opd-pn-row-number');
            if (numEl) numEl.textContent = idx + 1;
        });
    }

    function filterOpdPnMedicineRow(selectElem) {
        const tr = selectElem.closest('tr');
        const selectedCat = selectElem.value;
        const datalist = tr.querySelector('.opd-pn-med-datalist');
        if (!datalist) return;

        const allMedData = @json($medicines ?? []);
        datalist.innerHTML = '';
        allMedData.forEach(function(m) {
            if (!selectedCat || m.category == selectedCat) {
                const opt = document.createElement('option');
                opt.value = m.medicine;
                opt.setAttribute('data-category', m.category || '');
                datalist.appendChild(opt);
            }
        });
    }
    window.filterOpdPnMedicineRow = filterOpdPnMedicineRow;
</script>
