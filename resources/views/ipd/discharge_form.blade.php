@extends('layouts.admin')

@section('title', 'Discharge Summary - ' . ($ipdPatient->ipd_no ?? ''))

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6">

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="ph ph-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @if($ipdPatient->discharge)
                <a href="{{ route('admin.ipd.discharge.slip', $ipdPatient->id) }}" target="_blank"
                   class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                    <i class="ph ph-printer mr-1"></i> View & Print Summary
                </a>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 shadow-sm flex items-center space-x-2">
            <i class="ph ph-warning-circle text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @php
        $discharge = $ipdPatient->discharge;

        // Pre-fill Vitals if discharge vitals not yet entered
        $bp = $discharge->bp ?? ($admissionVitals->blood_pressure ?? ($admissionVitals->bp ?? ''));
        $pulse = $discharge->pulse ?? ($admissionVitals->pulse ?? '');
        $rr = $discharge->rr ?? ($admissionVitals->respiratory_rate ?? ($admissionVitals->respiration ?? ($admissionVitals->rr ?? '')));
        $spo2 = $discharge->spo2 ?? ($admissionVitals->spo2 ?? '');
        $temp = $discharge->temperature ?? ($admissionVitals->temperature ?? '');

        // Decode investigations
        $invItems = [];
        if (!empty($discharge->investigations)) {
            $dec = json_decode($discharge->investigations, true);
            if (is_array($dec)) $invItems = $dec;
        }

        // Decode procedures
        $procItems = [];
        if (!empty($discharge->procedures)) {
            $dec = json_decode($discharge->procedures, true);
            if (is_array($dec)) $procItems = $dec;
        }

        // Decode medications
        $medItems = [];
        if (!empty($discharge->discharge_medications)) {
            $dec = json_decode($discharge->discharge_medications, true);
            if (is_array($dec)) $medItems = $dec;
        }

        $fatherSpouse = $discharge->father_spouse ?? ($ipdPatient->patient->patientProfile->guardian_name ?? ($ipdPatient->patient->patientProfile->father_name ?? ''));
        $doctorName = $discharge->treating_physician ?? ($ipdPatient->doctor->name ?? '');
    @endphp


    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-950 p-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold uppercase tracking-wider border border-blue-400/30">
                            Code Blue Emergency Hospital
                        </span>
                        @if($ipdPatient->status == 0)
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/30">
                                Discharged
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-400/30">
                                Currently Admitted
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-black mt-2 tracking-tight">Discharge Summary Form</h1>
                    <p class="text-slate-300 text-xs mt-1">Fill or edit the 10 sections matching the printed Discharge Summary document.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.ipd.discharge.slip', $ipdPatient->id) }}" target="_blank"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow transition-all flex items-center gap-1.5">
                        <i class="ph ph-printer text-base"></i>
                        <span>Preview / Print</span>
                    </a>
                    <a href="{{ route('admin.ipd.index') }}"
                       class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5">
                        <i class="ph ph-arrow-left text-base"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>

            <!-- Patient Quick Info Ribbon -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 mt-6 pt-4 border-t border-white/10 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Patient Name</span>
                    <span class="font-bold text-white text-sm truncate block">{{ $ipdPatient->patient->name ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">UHID / MRN</span>
                    <span class="font-bold text-white text-sm">{{ $uhid ?? $ipdPatient->ipd_no }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">IPD Number</span>
                    <span class="font-bold text-blue-300 text-sm">{{ $ipdPatient->ipd_no }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Age / Gender</span>
                    <span class="font-bold text-white">{{ $ipdPatient->patient->patientProfile->age ?? ($ipdPatient->patient->age ?? '—') }} Y / {{ ucfirst($ipdPatient->patient->patientProfile->gender ?? ($ipdPatient->patient->gender ?? '—')) }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Admission Date</span>
                    <span class="font-bold text-white">{{ $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d/m/Y h:i A') : '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Ward / Bed</span>
                    <span class="font-bold text-white">{{ trim(($ipdPatient->beds->bed_name ?? ($ipdPatient->beds->name ?? '')) . (!empty($ipdPatient->beds->bedType->name) ? ' / ' . $ipdPatient->beds->bedType->name : '')) ?: '—' }}</span>
                </div>
            </div>
        </div>

        <!-- The Main Form -->
        <form action="{{ route('admin.ipd.discharge.save', $ipdPatient->id) }}" method="POST" id="discharge_form" class="p-6 space-y-6">
            @csrf

            <!-- ==================== SECTION 1: DISCHARGE STATUS & BASIC DETAILS ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">1</span>
                        1. Patient &amp; Admission Details
                    </span>
                    <span class="text-xs text-blue-100">Admission details pre-filled from IPD record</span>
                </div>
                <div class="p-5 bg-white grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Discharge Date &amp; Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="discharge_date"
                            value="{{ ($discharge && $discharge->discharge_date) ? \Carbon\Carbon::parse($discharge->discharge_date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Discharge Status <span class="text-red-500">*</span></label>
                        @php $status = $discharge->discharge_status ?? ''; @endphp
                        <select name="discharge_status" class="w-full border-gray-300 rounded-lg p-2.5 text-sm border focus:ring-blue-500 focus:border-blue-500">
                            <option value="Normal" {{ $status == 'Normal' ? 'selected' : '' }}>Normal / Cured</option>
                            <option value="Improved" {{ $status == 'Improved' ? 'selected' : '' }}>Improved</option>
                            <option value="Referred" {{ $status == 'Referred' ? 'selected' : '' }}>Referred to higher center</option>
                            <option value="LAMA" {{ $status == 'LAMA' ? 'selected' : '' }}>LAMA (Left Against Medical Advice)</option>
                            <option value="Death" {{ $status == 'Death' ? 'selected' : '' }}>Death</option>
                            <option value="DOPR" {{ $status == 'DOPR' ? 'selected' : '' }}>DOPR (Discharge on Patient Request)</option>
                            <option value="Absconded" {{ $status == 'Absconded' ? 'selected' : '' }}>Absconded</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Father / Spouse Name</label>
                        <input type="text" name="father_spouse" value="{{ $fatherSpouse }}"
                            placeholder="Enter father or spouse name"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 2: FINAL DIAGNOSIS ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">2</span>
                        2. Final Diagnosis
                    </span>
                    <span class="text-xs text-blue-100">Main clinical diagnosis for summary</span>
                </div>
                <div class="p-5 bg-white">
                    <textarea name="diagnosis" rows="2"
                        class="w-full border-gray-300 rounded-lg p-3 text-sm border focus:ring-blue-500 focus:border-blue-500 font-medium"
                        placeholder="E.g. Acute Gastroenteritis with Moderate Dehydration / Sepsis with LRTI / Traumatic Head Injury">{{ $discharge->diagnosis ?? '' }}</textarea>
                </div>
            </div>

            <!-- ==================== SECTION 3: PRESENTING COMPLAINTS & RELEVANT HISTORY ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">3</span>
                        3. Presenting Complaints &amp; Relevant History
                    </span>
                    <span class="text-xs text-blue-100">Chief complaints, duration, past medical history</span>
                </div>
                <div class="p-5 bg-white">
                    <textarea name="history" rows="3"
                        class="w-full border-gray-300 rounded-lg p-3 text-sm border focus:ring-blue-500 focus:border-blue-500"
                        placeholder="E.g. Patient presented with high grade fever with chills for 4 days, breathlessness on exertion, multiple episodes of vomiting. Known case of Type 2 DM, HTN.">{{ $discharge->history ?? '' }}</textarea>
                </div>
            </div>

            <!-- ==================== SECTION 4: CLINICAL EXAMINATION ON ADMISSION ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">4</span>
                        4. Clinical Examination on Admission
                    </span>
                    <span class="text-xs text-blue-100">Admission vitals &amp; systemic examination</span>
                </div>
                <div class="p-5 bg-white space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">BP (mmHg)</label>
                            <input type="text" name="bp" value="{{ $bp }}" placeholder="120/80"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Pulse (/min)</label>
                            <input type="text" name="pulse" value="{{ $pulse }}" placeholder="78"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">RR (/min)</label>
                            <input type="text" name="rr" value="{{ $rr }}" placeholder="18"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">SpO₂ (%)</label>
                            <input type="text" name="spo2" value="{{ $spo2 }}" placeholder="98"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Temperature (&deg;F)</label>
                            <input type="text" name="temperature" value="{{ $temp }}" placeholder="98.6"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">GCS (/15)</label>
                            <input type="text" name="gcs" value="{{ $discharge->gcs ?? '15/15' }}" placeholder="15/15"
                                class="w-full border-gray-300 rounded-lg p-2 text-sm border font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Systemic examination / significant findings</label>
                        <textarea name="systemic_exam" rows="2"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border focus:ring-blue-500 focus:border-blue-500"
                            placeholder="CVS: S1S2 Normal. Chest: B/L vesicular breath sounds, no crepts. P/A: Soft, non-tender, no organomegaly. CNS: Conscious, oriented to time, place and person.">{{ $discharge->systemic_exam ?? ($discharge->condition_at_admission ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 5: INVESTIGATIONS ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">5</span>
                        5. Investigations
                    </span>
                    <button type="button" onclick="addInvestigationRow()"
                        class="px-2.5 py-1 bg-white text-blue-700 hover:bg-blue-50 rounded text-xs font-bold flex items-center gap-1 transition-all">
                        <i class="ph ph-plus-circle font-bold"></i> Add Test Row
                    </button>
                </div>
                <div class="p-5 bg-white space-y-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" id="investigations_table">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700 uppercase font-bold text-left">
                                    <th class="p-2.5 rounded-l-lg" style="width: 45%;">Investigation / Date</th>
                                    <th class="p-2.5" style="width: 45%;">Key Findings / Results</th>
                                    <th class="p-2.5 text-center rounded-r-lg" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="investigations_tbody" class="divide-y divide-slate-100">
                                @if(!empty($invItems))
                                    @foreach($invItems as $idx => $inv)
                                        <tr class="inv-row">
                                            <td class="p-2">
                                                <input type="text" name="inv_name[]" value="{{ $inv['name'] ?? '' }}"
                                                    placeholder="E.g. CBC (20/09/2026)" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="inv_result[]" value="{{ $inv['result'] ?? '' }}"
                                                    placeholder="E.g. Hb 11.2, TLC 9,400, Platelets 2.1L" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2 text-center">
                                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="inv-row">
                                        <td class="p-2">
                                            <input type="text" name="inv_name[]" value="CBC / Routine Blood Tests"
                                                placeholder="Investigation / Date" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="inv_result[]" value="Hb: 12.0, TLC: 7800, Plt: 2.2L"
                                                placeholder="Findings / Results" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="inv-row">
                                        <td class="p-2">
                                            <input type="text" name="inv_name[]" value="Chest X-Ray / ECG"
                                                placeholder="Investigation / Date" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="inv_result[]" value="NSR, No acute ischemic changes"
                                                placeholder="Findings / Results" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 6: HOSPITAL COURSE / TREATMENT GIVEN ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">6</span>
                        6. Hospital Course / Treatment Given
                    </span>
                    <span class="text-xs text-blue-100">Course during stay &amp; medical therapy provided</span>
                </div>
                <div class="p-5 bg-white">
                    <textarea name="treatment_given" rows="4"
                        class="w-full border-gray-300 rounded-lg p-3 text-sm border focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Patient admitted via Emergency. Managed with IV fluids (NS/RL), IV Antibiotics, Analgesics, PPIs, antiemetics and supportive therapy. Vitals remained stable throughout hospital stay. Patient responded well to medical therapy and is being discharged in stable condition.">{{ $discharge->treatment_given ?? '' }}</textarea>
                </div>
            </div>

            <!-- ==================== SECTION 7: PROCEDURES / INTERVENTIONS ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">7</span>
                        7. Procedures / Interventions
                    </span>
                    <button type="button" onclick="addProcedureRow()"
                        class="px-2.5 py-1 bg-white text-blue-700 hover:bg-blue-50 rounded text-xs font-bold flex items-center gap-1 transition-all">
                        <i class="ph ph-plus-circle font-bold"></i> Add Procedure
                    </button>
                </div>
                <div class="p-5 bg-white space-y-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" id="procedures_table">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700 uppercase font-bold text-left">
                                    <th class="p-2.5 rounded-l-lg" style="width: 25%;">Date</th>
                                    <th class="p-2.5" style="width: 35%;">Procedure / Intervention</th>
                                    <th class="p-2.5" style="width: 30%;">Indication / Findings</th>
                                    <th class="p-2.5 text-center rounded-r-lg" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="procedures_tbody" class="divide-y divide-slate-100">
                                @if(!empty($procItems))
                                    @foreach($procItems as $proc)
                                        <tr class="proc-row">
                                            <td class="p-2">
                                                <input type="text" name="proc_date[]" value="{{ $proc['date'] ?? '' }}"
                                                    placeholder="DD/MM/YYYY" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="proc_name[]" value="{{ $proc['name'] ?? '' }}"
                                                    placeholder="Procedure name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="proc_findings[]" value="{{ $proc['findings'] ?? '' }}"
                                                    placeholder="Indication / Findings" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2 text-center">
                                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="proc-row">
                                        <td class="p-2">
                                            <input type="text" name="proc_date[]" value="{{ $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d/m/Y') : '' }}"
                                                placeholder="DD/MM/YYYY" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="proc_name[]" value="IV Cannulation & Vitals Monitoring"
                                                placeholder="Procedure name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="proc_findings[]" value="Supportive Care / Medication administration"
                                                placeholder="Indication / Findings" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 8: CONDITION AT DISCHARGE ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">8</span>
                        8. Condition at Discharge
                    </span>
                    <span class="text-xs text-blue-100">Patient status on discharge</span>
                </div>
                <div class="p-5 bg-white grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">General Condition</label>
                        <input type="text" name="general_condition" value="{{ $discharge->general_condition ?? ($discharge->discharge_status ?? 'Stable & Afebrile') }}"
                            placeholder="E.g. Stable / Fair / Improved"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Consciousness</label>
                        <input type="text" name="consciousness" value="{{ $discharge->consciousness ?? 'Conscious & Oriented' }}"
                            placeholder="E.g. Conscious & Oriented"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mobility</label>
                        <input type="text" name="mobility" value="{{ $discharge->mobility ?? 'Ambulatory' }}"
                            placeholder="E.g. Ambulatory / With Support"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Diet</label>
                        <input type="text" name="diet" value="{{ $discharge->diet ?? 'Normal diet as tolerated' }}"
                            placeholder="E.g. Normal / Soft / Diabetic"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-semibold">
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 9: DISCHARGE MEDICATIONS ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">9</span>
                        9. Discharge Medications
                    </span>
                    <button type="button" onclick="addMedicationRow()"
                        class="px-2.5 py-1 bg-white text-blue-700 hover:bg-blue-50 rounded text-xs font-bold flex items-center gap-1 transition-all">
                        <i class="ph ph-plus-circle font-bold"></i> Add Medicine
                    </button>
                </div>
                <div class="p-5 bg-white space-y-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" id="medications_table">
                            <thead>
                                <tr class="bg-slate-100 text-slate-700 uppercase font-bold text-left">
                                    <th class="p-2.5 rounded-l-lg" style="width: 34%;">Medicine Name</th>
                                    <th class="p-2.5" style="width: 15%;">Dose</th>
                                    <th class="p-2.5" style="width: 15%;">Route</th>
                                    <th class="p-2.5" style="width: 16%;">Frequency</th>
                                    <th class="p-2.5" style="width: 14%;">Duration</th>
                                    <th class="p-2.5 text-center rounded-r-lg" style="width: 6%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="medications_tbody" class="divide-y divide-slate-100">
                                @if(!empty($medItems))
                                    @foreach($medItems as $med)
                                        <tr class="med-row">
                                            <td class="p-2">
                                                <input type="text" name="med_name[]" value="{{ $med['medicine'] ?? '' }}"
                                                    placeholder="Medicine name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-bold">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="med_dose[]" value="{{ $med['dose'] ?? '' }}"
                                                    placeholder="e.g. 500mg" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="med_route[]" value="{{ $med['route'] ?? 'Oral' }}"
                                                    placeholder="Oral / IV" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="med_frequency[]" value="{{ $med['frequency'] ?? '' }}"
                                                    placeholder="1-0-1 / OD / BD" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
                                            </td>
                                            <td class="p-2">
                                                <input type="text" name="med_duration[]" value="{{ $med['duration'] ?? '' }}"
                                                    placeholder="e.g. 5 Days" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                            </td>
                                            <td class="p-2 text-center">
                                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                    <i class="ph ph-trash text-base"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="med-row">
                                        <td class="p-2">
                                            <input type="text" name="med_name[]" value="Tab. Pantop"
                                                placeholder="Medicine name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-bold">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_dose[]" value="40 mg"
                                                placeholder="Dose" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_route[]" value="Oral"
                                                placeholder="Route" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_frequency[]" value="1-0-0 (Empty Stomach)"
                                                placeholder="Frequency" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_duration[]" value="7 Days"
                                                placeholder="Duration" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="med-row">
                                        <td class="p-2">
                                            <input type="text" name="med_name[]" value="Tab. Paracetamol"
                                                placeholder="Medicine name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-bold">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_dose[]" value="650 mg"
                                                placeholder="Dose" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_route[]" value="Oral"
                                                placeholder="Route" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_frequency[]" value="SOS (If fever/pain)"
                                                placeholder="Frequency" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="med_duration[]" value="5 Days"
                                                placeholder="Duration" class="w-full border-gray-300 rounded p-1.5 text-xs border">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                                                <i class="ph ph-trash text-base"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ==================== SECTION 10: ADVICE AT DISCHARGE ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-xs">10</span>
                        10. Advice at Discharge
                    </span>
                    <span class="text-xs text-blue-100">Standard &amp; custom advice guidelines</span>
                </div>
                <div class="p-5 bg-white space-y-4">
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-xs space-y-2">
                        <div class="font-bold text-slate-800 uppercase mb-2">Standard Code Blue Discharge Warnings (Printed Automatically):</div>
                        <label class="flex items-center gap-2 text-slate-700">
                            <input type="checkbox" checked disabled class="rounded text-blue-600">
                            <span>■ Continue medications as prescribed.</span>
                        </label>
                        <label class="flex items-center gap-2 text-slate-700">
                            <input type="checkbox" checked disabled class="rounded text-blue-600">
                            <span>■ Follow diet, activity and lifestyle advice.</span>
                        </label>
                        <label class="flex items-center gap-2 text-slate-700">
                            <input type="checkbox" checked disabled class="rounded text-blue-600">
                            <span>■ Follow up as advised on date given below.</span>
                        </label>
                        <label class="flex items-center gap-2 text-slate-700">
                            <input type="checkbox" checked disabled class="rounded text-blue-600">
                            <span>■ Seek immediate medical attention for worsening symptoms, fever, breathlessness, chest pain, bleeding, vomiting, or altered sensorium.</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Additional / Specific Medical Advice (Optional)</label>
                        <textarea name="advice" rows="3"
                            class="w-full border-gray-300 rounded-lg p-2.5 text-sm border focus:ring-blue-500 focus:border-blue-500"
                            placeholder="E.g. Drink plenty of boiled water. Avoid oily and spicy foods. Complete full antibiotic course.">{{ $discharge->advice ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ==================== FOLLOW-UP & DOCTOR SIGNATURE BLOCK ==================== -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-slate-800 px-4 py-2.5 text-white flex items-center justify-between">
                    <span class="font-bold text-sm uppercase tracking-wide flex items-center gap-2">
                        <i class="ph ph-calendar-plus text-base text-blue-400"></i>
                        Follow-Up &amp; Treating Physician Details
                    </span>
                    <span class="text-xs text-slate-300">Printed at the bottom of Page 2</span>
                </div>
                <div class="p-5 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left: Follow-Up -->
                    <div class="space-y-3 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="ph ph-calendar"></i> Follow-Up Instructions
                        </h4>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Next Visit Date</label>
                            <input type="text" name="followup_date" value="{{ $discharge->followup_date ?? 'After 5 - 7 Days' }}"
                                placeholder="E.g. 02/10/2026 or After 7 Days"
                                class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Doctor / Department</label>
                            <input type="text" name="followup_doctor" value="{{ $discharge->followup_doctor ?? $doctorName }}"
                                placeholder="E.g. Dr. A. K. Sharma / General Medicine OPD"
                                class="w-full border-gray-300 rounded-lg p-2.5 text-sm border">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Special Instructions</label>
                            <textarea name="special_instructions" rows="2"
                                class="w-full border-gray-300 rounded-lg p-2 text-xs border"
                                placeholder="E.g. Report to Emergency immediately if high fever or persistent vomiting occurs.">{{ $discharge->special_instructions ?? ($discharge->precautions_followup ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Right: Doctor / Treating Physician -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="ph ph-user-md"></i> Treating Physician
                        </h4>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Doctor Name</label>
                            <input type="text" name="treating_physician" value="{{ $doctorName }}"
                                placeholder="Treating Doctor Name"
                                class="w-full border-gray-300 rounded-lg p-2.5 text-sm border font-bold">
                        </div>
                        <div class="pt-6 border-t border-dashed border-slate-300 text-center">
                            <div class="h-12 border-b-2 border-slate-400 w-48 mx-auto mb-1"></div>
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Doctor's Signature &amp; Stamp</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.ipd.index') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                    Cancel &amp; Return
                </a>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" name="submit_action" value="draft"
                        class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-xl font-bold text-sm shadow transition-all flex items-center gap-1.5">
                        <i class="ph ph-floppy-disk text-lg"></i>
                        <span>Save as Draft</span>
                    </button>

                    <button type="submit" name="submit_action" value="save_and_print"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-bold text-sm shadow transition-all flex items-center gap-1.5">
                        <i class="ph ph-printer text-lg"></i>
                        <span>Save &amp; Print Summary</span>
                    </button>

                    @if($ipdPatient->status != 0)
                        <button type="submit" name="submit_action" value="discharge"
                            onclick="return confirm('Are you sure you want to finalize discharge for this patient?')"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-xl font-black text-sm shadow-md transition-all flex items-center gap-1.5">
                            <i class="ph ph-sign-out text-lg font-bold"></i>
                            <span>Finalize Discharge</span>
                        </button>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

@push('js')
<script>
    // Dynamic row helpers
    function removeRow(btn) {
        const row = btn.closest('tr');
        if (row) {
            row.remove();
        }
    }

    // Add Investigation Row
    function addInvestigationRow() {
        const tbody = document.getElementById('investigations_tbody');
        const tr = document.createElement('tr');
        tr.className = 'inv-row';
        tr.innerHTML = `
            <td class="p-2">
                <input type="text" name="inv_name[]" placeholder="Investigation / Date" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2">
                <input type="text" name="inv_result[]" placeholder="Key Findings / Results" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                    <i class="ph ph-trash text-base"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // Add Procedure Row
    function addProcedureRow() {
        const tbody = document.getElementById('procedures_tbody');
        const tr = document.createElement('tr');
        tr.className = 'proc-row';
        tr.innerHTML = `
            <td class="p-2">
                <input type="text" name="proc_date[]" placeholder="DD/MM/YYYY" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2">
                <input type="text" name="proc_name[]" placeholder="Procedure name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
            </td>
            <td class="p-2">
                <input type="text" name="proc_findings[]" placeholder="Indication / Findings" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                    <i class="ph ph-trash text-base"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // Add Medication Row
    function addMedicationRow() {
        const tbody = document.getElementById('medications_tbody');
        const tr = document.createElement('tr');
        tr.className = 'med-row';
        tr.innerHTML = `
            <td class="p-2">
                <input type="text" name="med_name[]" placeholder="Medicine name" class="w-full border-gray-300 rounded p-1.5 text-xs border font-bold">
            </td>
            <td class="p-2">
                <input type="text" name="med_dose[]" placeholder="Dose (e.g. 500mg)" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2">
                <input type="text" name="med_route[]" value="Oral" placeholder="Route" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2">
                <input type="text" name="med_frequency[]" placeholder="1-0-1 / OD / BD" class="w-full border-gray-300 rounded p-1.5 text-xs border font-semibold">
            </td>
            <td class="p-2">
                <input type="text" name="med_duration[]" placeholder="Duration (e.g. 5 Days)" class="w-full border-gray-300 rounded p-1.5 text-xs border">
            </td>
            <td class="p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 p-1">
                    <i class="ph ph-trash text-base"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }
</script>
@endpush
@endsection