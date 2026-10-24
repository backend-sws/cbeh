@extends('layouts.admin')
@section('title', 'OPD Patient Details')
<style>
    .custom-container {
        display: flex;
        width: 100%;
        gap: 20px;
    }
    .left-section,
    .right-section {
        flex: 1;
        padding: 10px;
        box-sizing: border-box;
        border: none;
        min-height: 200px;
    }
    @media (max-width: 768px) {
        .custom-container {
            flex-direction: column;
        }
    }
</style>
@section('content')

    <body class="bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Patient Header Section -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center gap-6 border-b border-gray-100">
                    <div class="shrink-0">
                        @if ($opd_patient->patient->image)
                            <img src="{{ asset('avatar/' . $opd_patient->patient->image) }}"
                                class="w-24 h-24 md:w-28 md:h-28 rounded-full border-2 border-white shadow-sm object-cover"
                                alt="Patient Image">
                        @else
                            @php
                                $name = $opd_patient->patient->name ?? '';
                                $initials = collect(explode(' ', $name))
                                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                    ->take(2)
                                    ->implode('');
                            @endphp
                            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-sm"
                                style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <span
                                    class="inline-block px-2 py-1 text-xs font-medium bg-pink-50 text-pink-600 rounded-md mb-1">#{{ $opd_patient->opd_no }}</span>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $opd_patient->patient->name }}
                                </h1>
                                <p class="text-gray-600">{{ $opd_patient->patient->email }}</p>
                            </div>
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <button type="button" onclick="openVitalsModal()"
                                    class="inline-flex items-center px-3.5 py-1.5 bg-pink-600 text-white hover:bg-pink-700 rounded-lg text-sm font-semibold shadow-sm transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> Record Vitals
                                </button>
                                <a href="{{ route('admin.opd.edit', $opd_patient->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-sm font-medium shadow-sm transition-colors">
                                    <i class="fas fa-edit mr-1.5 text-gray-500"></i> Edit
                                </a>
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium 
                            {{ $opd_patient->is_old_patient ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $opd_patient->is_old_patient ? 'Returning Patient' : 'New Patient' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 md:gap-6 mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">{{ $opd_patient->patient->cases_count ?? 1 }}
                                </div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Cases</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">
                                    {{ $opd_patient->patient->admissions_count ?? 1 }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Admissions</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">
                                    {{ $appointments_count }}
                                </div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Appointments</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">
                                    {{ $opd_patient->patient->prescriptions_count ?? 0 }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Prescriptions</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">
                                    {{ isset($progress_notes) ? $progress_notes->count() : 0 }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Progress Notes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-pink-600">
                                    {{ isset($vitals_history) ? $vitals_history->count() : 0 }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Vitals Log</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex overflow-x-auto">
                        <button data-tab="overview"
                            class="tab-button active px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-pink-500 text-pink-600">
                            <i class="fas fa-info-circle mr-2"></i>Overview
                        </button>
                        <button data-tab="vitals"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-heartbeat mr-2"></i>Vitals History
                            @if(isset($vitals_history) && $vitals_history->count() > 0)
                                <span class="ml-1.5 px-2 py-0.5 text-xs rounded-full bg-pink-100 text-pink-700 font-bold">{{ $vitals_history->count() }}</span>
                            @endif
                        </button>
                        <button data-tab="visits"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-calendar-alt mr-2"></i>Visits
                        </button>
                        <button data-tab="diagnosis"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-flask mr-2"></i>Lab Uploads
                        </button>
                        <button data-tab="diagnosis-tests"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-microscope mr-2"></i>Investigation
                        </button>
                        <button data-tab="prescriptions"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-prescription-bottle-alt mr-2"></i>Prescriptions
                        </button>
                        <button data-tab="progress-notes"
                            class="tab-button px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-notes-medical mr-2"></i>Progress Notes
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6 md:p-8">
                    <!-- Overview Tab -->
                    <div id="overview" class="tab-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800">Patient Details</h3>
                                    <button type="button" onclick="openVitalsModal()"
                                        class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-pink-50 text-pink-700 hover:bg-pink-100 rounded-md border border-pink-200 transition-colors">
                                        <i class="fas fa-heartbeat mr-1"></i> Edit Vitals
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Assigned Doctor</p>
                                            <div class="flex items-center mt-1">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if (!empty($opd_patient->doctor->image))
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="{{ asset('avatar/' . $opd_patient->doctor->image) }}"
                                                            alt="{{ $opd_patient->doctor->name ?? 'N/A' }}">
                                                    @else
                                                        @php
                                                            $docName = $opd_patient->doctor->name ?? '';
                                                            $docInitials = collect(explode(' ', $docName))
                                                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                                                ->take(2)
                                                                ->implode('');
                                                        @endphp
                                                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-base font-bold shadow-sm"
                                                            style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);">
                                                            {{ $docInitials }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <p class="font-medium">{{ $opd_patient->doctor->name ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $opd_patient->doctor->specialization ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div>
                                            <p class="text-sm text-gray-500">Case ID</p>
                                            <p class="font-medium">{{ $opd_patient->case ?? 'N/A' }}</p>
                                        </div> --}}
                                        <div>
                                            <p class="text-sm text-gray-500">Appointment Date</p>
                                            <p class="font-medium">
                                                {{ \Carbon\Carbon::parse($opd_patient->appointment_date)->format('jS M, Y h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Height</p>
                                            <p class="font-medium">{{ $opd_patient->height ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Weight</p>
                                            <p class="font-medium">{{ $opd_patient->weight ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Guardian Name</p>
                                            <p class="font-medium">{{ $opd_patient->patient->guardian_name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Alternate Phone</p>
                                            <p class="font-medium">{{ $opd_patient->patient->alternate_phone ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Marital Status</p>
                                            <p class="font-medium">{{ $opd_patient->patient->marital_status ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Blood Group</p>
                                            <p class="font-medium">{{ $opd_patient->patient->blood_group ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Address</p>
                                            <p class="font-medium">{{ $opd_patient->patient->address ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Alternate Address</p>
                                            <p class="font-medium">{{ $opd_patient->patient->alternate_address ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Remarks</p>
                                            <p class="font-medium">{{ $opd_patient->patient->remarks ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Allergies</p>
                                            <p class="font-medium">{{ $opd_patient->patient->allergies ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">UHID</p>
                                            <p class="font-medium">{{ $opd_patient->patient_unique_id ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Blood Pressure</p>
                                            <p class="font-medium">{{ !empty($opd_patient->blood_pressure) ? $opd_patient->blood_pressure : (!empty($opd_patient->bp) ? $opd_patient->bp : 'N/A') }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Pulse Rate</p>
                                            <p class="font-medium">{{ !empty($opd_patient->pulse) ? $opd_patient->pulse . ' bpm' : 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Temperature</p>
                                            <p class="font-medium">{{ !empty($opd_patient->temperature) ? $opd_patient->temperature . ' °F' : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">SpO2</p>
                                            <p class="font-medium">{{ !empty($opd_patient->SpO2) ? $opd_patient->SpO2 . ' %' : (!empty($opd_patient->spo2) ? $opd_patient->spo2 . ' %' : 'N/A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Respiratory Rate</p>
                                            <p class="font-medium">{{ !empty($opd_patient->respiratory_rate) ? $opd_patient->respiratory_rate . ' /min' : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <p class="text-sm text-gray-500">RBS</p>
                                            <p class="font-medium">{{ !empty($opd_patient->rbs) ? $opd_patient->rbs . ' mg/dL' : 'N/A' }}</p>
                                        </div>
                                        <div>
                                            @php
                                                $detailBmi = null;
                                                if (!empty($opd_patient->weight) && !empty($opd_patient->height)) {
                                                    $cH = floatval(preg_replace('/[^0-9.]/', '', $opd_patient->height));
                                                    $cW = floatval(preg_replace('/[^0-9.]/', '', $opd_patient->weight));
                                                    if ($cH > 0 && $cW > 0) {
                                                        $mH = ($cH < 3) ? $cH : (($cH <= 8.5) ? ($cH * 0.3048) : ($cH / 100));
                                                        if ($mH > 0) $detailBmi = round($cW / ($mH * $mH), 1);
                                                    }
                                                }
                                            @endphp
                                            <p class="text-sm text-gray-500">BMI</p>
                                            <p class="font-medium">{{ $detailBmi ? $detailBmi . ' kg/m²' : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Medical
                                    Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Symptoms</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                            <p class="text-gray-700">
                                                {{ $opd_patient->symptoms ?? 'No symptoms recorded' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Notes</p>
                                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                            <p class="text-gray-700">{{ $opd_patient->notes ?? 'No notes available' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-sm font-semibold text-gray-700 mb-3">Physical Examination Findings</p>
                                        <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-md">
                                            <div>
                                                <p class="text-xs text-gray-500">General Appearance</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->general_appearance ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">CVS</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->cvs ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">RS (Respiratory System)</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->rs ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">P/A (Abdomen)</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->pa ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">CNS</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->cns ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Other Systems</p>
                                                <p class="font-medium text-sm text-gray-800">{{ $opd_patient->other_systems ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">
                                    Billing Information</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Mode</p>
                                        <p class="font-medium">{{ $opd_patient->payment_mode ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Standard Charge</p>
                                        <p class="font-medium">₹{{ number_format($opd_patient->standard_charge, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(isset($vitals_history) && $vitals_history->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-heartbeat text-pink-500 mr-2"></i> Per-Visit Vitals &amp; Follow-up History ({{ $vitals_history->count() }})
                                </h3>
                                <button type="button" onclick="openVitalsModal()"
                                    class="inline-flex items-center text-xs font-semibold px-3 py-1.5 bg-pink-50 text-pink-700 hover:bg-pink-100 rounded-md border border-pink-200 transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Record New Vitals
                                </button>
                            </div>
                            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200 text-xs">
                                    <thead class="bg-gray-50 text-gray-600 font-semibold uppercase">
                                        <tr>
                                            <th class="px-3 py-2.5 text-left">Date & Time</th>
                                            <th class="px-3 py-2.5 text-left">Visit / Shift</th>
                                            <th class="px-3 py-2.5 text-center">BP</th>
                                            <th class="px-3 py-2.5 text-center">Pulse</th>
                                            <th class="px-3 py-2.5 text-center">Temp</th>
                                            <th class="px-3 py-2.5 text-center">SpO2</th>
                                            <th class="px-3 py-2.5 text-center">RR</th>
                                            <th class="px-3 py-2.5 text-center">RBS</th>
                                            <th class="px-3 py-2.5 text-center">BMI</th>
                                            <th class="px-3 py-2.5 text-left">Systemic / Notes</th>
                                            <th class="px-3 py-2.5 text-left">Recorded By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach($vitals_history as $v)
                                        <tr class="hover:bg-pink-50/50">
                                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($v->recorded_at)->format('d-m-Y') }}
                                                <div class="text-[10px] text-gray-500 font-normal">{{ \Carbon\Carbon::parse($v->recorded_at)->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-100 text-pink-800">
                                                    {{ $v->shift ?: 'Follow-up' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-center font-bold text-gray-800 whitespace-nowrap">{{ $v->blood_pressure ?: '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $v->pulse ? $v->pulse . ' bpm' : '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $v->temperature ? $v->temperature . ' °F' : '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap font-semibold {{ intval($v->spo2) < 95 && !empty($v->spo2) ? 'text-red-600' : '' }}">{{ $v->spo2 ? $v->spo2 . '%' : '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $v->respiratory_rate ? $v->respiratory_rate . '/min' : '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $v->rbs ? $v->rbs . ' mg/dL' : '-' }}</td>
                                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $v->bmi ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-600 text-[11px] max-w-xs">
                                                @if(!empty($v->general_appearance)) <span><strong>GA:</strong> {{ $v->general_appearance }} </span> @endif
                                                @if(!empty($v->cvs)) <span><strong>CVS:</strong> {{ $v->cvs }} </span> @endif
                                                @if(!empty($v->notes)) <span class="italic text-gray-500">{{ Str::limit($v->notes, 40) }}</span> @endif
                                                @if(empty($v->general_appearance) && empty($v->cvs) && empty($v->notes)) - @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 whitespace-nowrap">{{ $v->recorded_by ?: 'Staff' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Created On</p>
                                    <p class="font-medium">{{ $opd_patient->created_at->format('jS M, Y h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Last Updated</p>
                                    <p class="font-medium">{{ $opd_patient->updated_at->format('jS M, Y h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Created By</p>
                                    <p class="font-medium">System</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visits Tab -->
                    <div id="visits" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Patient Visits</h3>
                            @if (isset($appointments) && $appointments->count())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    OPD No</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Doctor</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Appointment Date</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Standard Charge</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($appointments as $visit)
                                                <tr>
                                                    <td class="px-4 py-2 font-semibold text-pink-600">
                                                        {{ $opd_patient->opd_no }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="font-medium">
                                                            {{ optional($visit->doctor)->name ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ optional($visit->doctor)->email ?? '' }}</div>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($visit->appointment_date)->format('g:i A') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($visit->appointment_date)->format('jS M, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ₹{{ number_format($opd_patient->standard_charge, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-4"></i>
                                    <p>No visits found for this patient.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Diagnosis Tab -->
                    <div id="diagnosis" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-700">Lab Uploads</h3>
                                <button
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none"
                                    onclick="document.getElementById('diagnosisModal').classList.remove('hidden')">
                                    <i class="fas fa-upload mr-2"></i> Upload Lab Report
                                </button>
                            </div>
                            @if (isset($diagnoses) && $diagnoses->count())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-2 text-left font-semibold text-gray-600">#</th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Report Type
                                                </th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Report Date
                                                </th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Document</th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Description
                                                </th>
                                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($diagnoses as $index => $diagnosis)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 font-medium text-gray-800">
                                                        {{ $diagnosis->report_type }}</td>
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($diagnosis->report_date)->format('jS M, Y') }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        @if ($diagnosis->document)
                                                            <a href="{{ asset($diagnosis->document) }}" target="_blank"
                                                                class="inline-flex items-center px-2 py-1 bg-pink-50 text-pink-600 rounded hover:bg-pink-100 transition">
                                                                <i class="fas fa-file-alt mr-1"></i> View
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2">{{ $diagnosis->description }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        <button
                                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200 mr-2"
                                                            onclick="editDiagnosis({{ $diagnosis->id }}, '{{ addslashes($diagnosis->report_type) }}', '{{ $diagnosis->report_date }}', '{{ addslashes($diagnosis->description) }}', '{{ $diagnosis->document ? asset($diagnosis->document) : '' }}')">
                                                            <i class="fas fa-edit mr-1"></i> Edit
                                                        </button>
                                                        <form
                                                            action="{{ route('admin.diagnosis.destroy', $diagnosis->id) }}"
                                                            method="POST" class="inline-block"
                                                            onsubmit="return confirm('Are you sure you want to delete this diagnosis?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700 hover:bg-red-200">
                                                                <i class="fas fa-trash mr-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-stethoscope text-4xl text-gray-300 mb-4"></i>
                                    <p>No diagnosis records found.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Diagnosis Modal (Add/Edit) -->
                        {{-- <div id="diagnosisModal"
                        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                            <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-xl"
                                onclick="document.getElementById('diagnosisModal').classList.add('hidden')">&times;</button>
                            <h2 id="diagnosisModalTitle" class="text-lg font-semibold mb-4 text-pink-600">Add
                                Diagnosis</h2>
                            <form id="diagnosisForm" action="{{ route('admin.diagnosis.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="opd_patient_id" value="{{ $opd_patient->id }}">
                                <input type="hidden" name="diagnosis_id" id="diagnosis_id">
                                <input type="hidden" name="_method" id="diagnosis_method" value="POST">
                                @if ($errors->any())
                                    <div class="mb-4">
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                                            <ul class="list-disc pl-5 text-sm">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Report Type</label>
                                    <input type="text" name="report_type" id="report_type"
                                        class="w-full border rounded p-2" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Report Date</label>
                                    <input type="date" name="report_date" id="report_date"
                                        class="w-full border rounded p-2" required>
                                </div>
                                <div class="mb-4" id="diagnosis-document-preview" style="display:none;">
                                    <label class="block text-sm font-medium mb-1">Current Document</label>
                                    <a href="#" target="_blank" id="diagnosis-document-link"
                                        class="text-pink-600 underline">View Document</a>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Document</label>
                                    <input type="file" name="document" id="document"
                                        class="w-full border rounded p-2">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Description</label>
                                    <textarea name="description" id="description" class="w-full border rounded p-2" rows="3"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button"
                                        class="mr-2 px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 diagnosis-cancel-btn">Cancel</button>
                                    <button type="submit"
                                        class="px-4 py-2 rounded bg-pink-600 text-white hover:bg-pink-700">Save</button>
                                </div>
                            </form>
                        </div>
                    </div> --}}
                        <!-- Diagnosis Modal (Add/Edit) -->
                        <div id="diagnosisModal"
                            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 relative"
                                style="max-height: 90vh; overflow: hidden;">
                                <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-xl"
                                    onclick="document.getElementById('diagnosisModal').classList.add('hidden')">&times;</button>
                                <h2 id="diagnosisModalTitle" class="text-base font-semibold mb-3 text-pink-600">Upload Lab Report</h2>
                                <form id="diagnosisForm" action="{{ route('admin.diagnosis.store') }}" method="POST"
                                    enctype="multipart/form-data" class="overflow-y-auto" style="max-height: 70vh;">
                                    @csrf
                                    <input type="hidden" name="opd_patient_id" value="{{ $opd_patient->id }}">
                                    <input type="hidden" name="diagnosis_id" id="diagnosis_id">
                                    <input type="hidden" name="_method" id="diagnosis_method" value="POST">
                                    @if ($errors->any())
                                        <div class="mb-3">
                                            <div
                                                class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs">
                                                <ul class="list-disc pl-4">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium mb-1">Report Type</label>
                                        <input type="text" name="report_type" id="report_type"
                                            class="w-full border rounded p-2 text-sm" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium mb-1">Report Date</label>
                                        <input type="date" name="report_date" id="report_date"
                                            class="w-full border rounded p-2 text-sm" required>
                                    </div>
                                    <div class="mb-3" id="diagnosis-document-preview" style="display:none;">
                                        <label class="block text-xs font-medium mb-1">Current Document</label>
                                        <a href="#" target="_blank" id="diagnosis-document-link"
                                            class="text-pink-600 underline text-xs">View Document</a>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium mb-1">Document</label>
                                        <input type="file" name="document" id="document"
                                            class="w-full border rounded p-2 text-xs">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium mb-1">Description</label>
                                        <textarea name="description" id="description" class="w-full border rounded p-2 text-sm" rows="2"></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2 mt-2">
                                        <button type="button"
                                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 text-xs diagnosis-cancel-btn">Cancel</button>
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-pink-600 text-white hover:bg-pink-700 text-xs">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Investigation Tab -->
                    <div id="diagnosis-tests" class="tab-content hidden">
                        @include('opd.investigations-tab')
                    </div>

                    <!-- Prescriptions Tab -->

                    <!-- Prescriptions Tab -->
                    <div id="prescriptions" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-700">Prescriptions</h3>
                                <button
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700"
                                    onclick="openPrescriptionModal()">+ Add Prescription</button>
                            </div>
                            @if (isset($prescriptions) && $prescriptions->count())
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-2">#</th>
                                                <th class="px-4 py-2">OPD No</th>
                                                <th class="px-4 py-2">Patient Name</th>
                                                <th class="px-4 py-2">Doctor Name</th>
                                                <th class="px-4 py-2">Created On</th>
                                                <th class="px-4 py-2 text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prescriptions as $index => $pres)
                                                <tr>
                                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2">{{ $opd_patient->opd_no }}</td>
                                                    <td class="px-4 py-2">{{ $opd_patient->patient->name ?? '' }}</td>
                                                    <td class="px-4 py-2">{{ $opd_patient->doctor->name ?? '' }}</td>
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($pres->created_at)->format('jS M, Y h:i A') }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <a href="{{ route('opd.prescriptions.pdf', $pres->id) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center px-2 py-1 bg-green-600 text-white rounded shadow hover:bg-green-700">
                                                            <i class="fas fa-print mr-2"></i>Print
                                                        </a>
                                                        <button class="px-2 py-1 bg-blue-100 text-blue-800 rounded mr-2"
                                                            onclick="previewPrescription({{ $pres->id }})">
                                                            <i class="fas fa-eye"></i> Preview
                                                        </button>
                                                        <button
                                                            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded mr-2"
                                                            onclick="editPrescription({{ $pres->id }})">Edit</button>
                                                        <form action="{{ route('opd.prescriptions.destroy', $pres->id) }}"
                                                            method="POST" class="inline-block"
                                                            onsubmit="return confirm('Delete this prescription?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="px-2 py-1 bg-red-100 text-red-700 rounded">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-prescription-bottle-alt text-4xl text-gray-300 mb-4"></i>
                                    <p>No prescriptions found.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Notes Tab -->
                    <div id="progress-notes" class="tab-content hidden">
                        @include('opd.progress-notes')
                    </div>

                    <!-- Vitals History Tab -->
                    <div id="vitals" class="tab-content hidden">
                        @include('opd.vitals-tab')
                    </div>

                    <!-- Prescription Modal (Add/Edit) -->
                    <!-- Prescription Modal (Add/Edit) -->
                    <div id="prescriptionModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
                        <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                <h2 id="prescriptionModalTitle" class="text-xl font-semibold text-gray-800">
                                    <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i>
                                    <span>Add Prescription</span>
                                </h2>
                                <button class="text-gray-400 hover:text-gray-600 text-2xl"
                                    onclick="closePrescriptionModal()">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <form id="prescriptionForm" action="{{ route('opd.prescriptions.store') }}" method="POST"
                                class="overflow-y-auto flex-1 p-6">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $opd_patient->patient_id }}">
                                <input type="hidden" name="doctor_id" value="{{ $opd_patient->doctor_id }}">
                                <input type="hidden" name="_method" id="prescription_method" value="POST">
                                <input type="hidden" name="prescription_id" id="prescription_id">

                                <!-- Basic Information Section -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Prescription Date:
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="prescription_date" id="prescription_date"
                                            class="w-full border rounded p-2 text-sm" required
                                            value="{{ old('prescription_date', date('Y-m-d')) }}">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status" id="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            required>
                                            <option value="active" selected>Active</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Header Notes -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Notes
                                    </label>
                                    <textarea
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                        rows="2" name="headernotes" id="headernotes" placeholder="Enter any header notes..."></textarea>
                                </div>

                                <!-- Divider -->
                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- Prescription Items Section -->
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="text-sm font-medium text-gray-700">Prescription Items</h3>
                                        <button type="button" id="addOpdPrescriptionItem"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus mr-1"></i> Add Item
                                        </button>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200"
                                            id="opdPrescriptionTbl">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-8">
                                                        #</th>
                                                    <th scope="col"
                                                        class="hidden px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">
                                                        Category</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="min-width: 190px;">
                                                        Medicine Name</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">
                                                        Dose</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                                                        Route</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">
                                                        Frequency</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">
                                                        Duration</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="min-width: 140px;">
                                                        Instructions</th>
                                                    <th scope="col"
                                                        class="px-2 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-10">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white divide-y divide-gray-200 opd-prescription-item-container">
                                                <tr>
                                                    <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center opd-prescription-item-number"
                                                        data-item-number="1">1</td>
                                                    <td class="hidden px-2 py-2 whitespace-nowrap">
                                                        <select
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 opdCategoryId"
                                                            name="category_id[]">
                                                            <option value="">All</option>
                                                            @foreach ($medicine_categories as $cat)
                                                                <option value="{{ $cat->id }}">
                                                                    {{ $cat->medicine_category }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap" style="min-width: 190px;">
                                                        <input type="text"
                                                            list="opdMedicineList_1"
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 medicineInput font-medium"
                                                            name="medicine_name[]"
                                                            placeholder="Search or type medicine..."
                                                            required
                                                            autocomplete="off">
                                                        <datalist id="opdMedicineList_1" class="medicineDatalist">
                                                            @if(isset($medicines))
                                                                @foreach ($medicines as $m)
                                                                    <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                                                                @endforeach
                                                            @endif
                                                        </datalist>
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                                        <input type="text"
                                                            list="opdDoseList"
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 dosageInput font-medium"
                                                            name="dosage[]"
                                                            placeholder="e.g. 500 mg, 1 Tab"
                                                            required
                                                            autocomplete="off">
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap" style="min-width: 95px;">
                                                        <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 routeInput font-medium">
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
                                                        <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 frequencyInput font-semibold">
                                                            <option value="OD">OD (Once Daily)</option>
                                                            <option value="BD" selected>BD (Twice Daily)</option>
                                                            <option value="TDS">TDS (Thrice Daily)</option>
                                                            <option value="QID">QID (4 Times Daily)</option>
                                                            <option value="Continuous Dose">Continuous Dose</option>
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
                                                        <input type="text"
                                                            list="opdDurationList"
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 durationInput"
                                                            name="duration[]"
                                                            placeholder="e.g. 5 Days"
                                                            value="5 Days"
                                                            autocomplete="off">
                                                    </td>
                                                    <td class="px-2 py-2" style="min-width: 130px;">
                                                        <input type="text"
                                                            placeholder="Remarks / Instructions..."
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 instruction"
                                                            name="instructions[]">
                                                    </td>
                                                    <td class="px-2 py-2 whitespace-nowrap text-center">
                                                        <button type="button"
                                                            class="text-red-500 hover:text-red-700 deleteopdPrescription p-1"
                                                            title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Master Datalists -->
                                    <datalist id="masterMedicineList">
                                        @if(isset($medicines))
                                            @foreach ($medicines as $m)
                                                <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                                            @endforeach
                                        @endif
                                    </datalist>

                                    <datalist id="opdDoseList">
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

                                    <datalist id="opdDurationList">
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

                                <!-- Footer Notes -->
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Investigation Advice
                                    </label>
                                    <textarea
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                        rows="2" name="footernotes" id="footernotes" placeholder="Enter any footer notes..."></textarea>
                                </div>

                                <!-- Modal Footer -->
                                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
                                    <button type="button" onclick="closePrescriptionModal()"
                                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Save Prescription
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Preview Modal -->
            <!-- Prescription Preview Modal -->
            <div id="prescriptionPreviewModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b pb-4 mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i>
                            Prescription Details
                        </h2>
                        <button type="button" aria-label="Close" class="text-gray-400 hover:text-gray-600 text-2xl"
                            onclick="closePrescriptionPreviewModal()">
                            &times;
                        </button>
                    </div>

                    <!-- Print/Download Button -->
                    <div class="flex justify-end mb-4">
                        <a id="prescriptionPdfBtn" href="#" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700">
                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                        </a>
                    </div>

                    <!-- Modal Body -->
                    <div id="prescriptionPreviewContent" class="bg-white p-4 rounded" style="min-width: 700px;">
                        <!-- Clinic/Doctor Info -->
                        <div class="flex flex-wrap justify-between items-center mb-4">
                            <div>
                                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo"
                                    class="h-12 mb-2">
                                <div class="text-sm text-gray-700 font-semibold">{{ setting('app_name') }}</div>
                                <div class="text-xs text-gray-500">{{ setting('address') }}</div>
                                <div class="text-xs text-gray-500">Email: {{ setting('email') }} | Phone:
                                    {{ setting('phone') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Created On:</div>
                                <div class="font-semibold text-gray-800" id="previewCreatedOn">--</div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <!-- Patient & OPD Info -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <div class="text-xs text-gray-500">OPD No:</div>
                                <div class="font-semibold text-gray-800" id="previewOpdNo">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Patient Name:</div>
                                <div class="font-semibold text-gray-800" id="previewPatientName">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Doctor:</div>
                                <div class="font-semibold text-gray-800" id="previewDoctorName">--</div>
                            </div>
                            {{-- <div>
                                <div class="text-xs text-gray-500">Case ID:</div>
                                <div class="font-semibold text-gray-800" id="previewCaseId">--</div>
                            </div> --}}
                            <div>
                                <div class="text-xs text-gray-500">Height:</div>
                                <div class="font-semibold text-gray-800" id="previewHeight">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Weight:</div>
                                <div class="font-semibold text-gray-800" id="previewWeight">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Symptoms:</div>
                                <div class="font-semibold text-gray-800" id="previewSymptoms">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Blood Pressure:</div>
                                <div class="font-semibold text-gray-800" id="previewBloodPressure">--</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Gender:</div>
                                <div class="font-semibold text-gray-800" id="previewGender">--</div>
                            </div>
                            {{-- <div>
                                <div class="text-xs text-gray-500">Age:</div>
                                <div class="font-semibold text-gray-800" id="previewAge">--</div>
                            </div> --}}
                            <div>
                                <div class="text-xs text-gray-500">Appointment Date:</div>
                                <div class="font-semibold text-gray-800" id="previewAppointmentDate">--</div>
                            </div>
                        </div>
                        <hr class="my-2">

                        <!-- Header Note -->
                        <div class="mb-2">
                            <div class="text-xs text-gray-500">Header Note:</div>
                            <div class="font-medium text-gray-700" id="previewHeaderNote">--</div>
                        </div>
                        <hr class="my-2">

                        <!-- Medicines Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm border">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Category</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Medicine Name</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Dose</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Route</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Frequency</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Duration</th>
                                        <th class="px-2 py-2 border text-xs font-semibold text-gray-600">Instructions</th>
                                    </tr>
                                </thead>
                                <tbody id="previewMedicinesTable">
                                    <!-- JS will fill rows here -->
                                </tbody>
                            </table>
                        </div>
                        <hr class="my-2">

                        <!-- Footer Note -->
                        <div class="mt-2">
                            <div class="text-xs text-gray-500">Additional Prescription:</div>
                            <div class="font-medium text-gray-700" id="previewFooterNote">--</div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function previewPrescription(id) {
                    fetch("{{ url('admin/opd/prescriptions') }}/" + id)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('previewCreatedOn').innerText = data.created_at ?
                                new Date(data.created_at).toLocaleString('en-GB', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) : '--';
                            document.getElementById('previewOpdNo').innerText = "{{ $opd_patient->opd_no }}";
                            document.getElementById('previewPatientName').innerText = "{{ $opd_patient->patient->name }}";
                            document.getElementById('previewDoctorName').innerText = "{{ $opd_patient->doctor->name }}";
                            // document.getElementById('previewCaseId').innerText = "{{ $opd_patient->case ?? 'N/A' }}";
                            document.getElementById('previewHeight').innerText = "{{ $opd_patient->height ?? 'N/A' }}";
                            document.getElementById('previewWeight').innerText = "{{ $opd_patient->weight ?? 'N/A' }}";
                            document.getElementById('previewSymptoms').innerText = "{{ $opd_patient->symptoms ?? 'N/A' }}";
                            document.getElementById('previewBloodPressure').innerText =
                                "{{ $opd_patient->blood_pressure ?? 'N/A' }}";
                            document.getElementById('previewGender').innerText = "{{ $opd_patient->patient->gender ?? '' }}";
                            // document.getElementById('previewAge').innerText = "{{ $opd_patient->patient->age ?? '' }}";
                            document.getElementById('previewAppointmentDate').innerText =
                                "{{ \Carbon\Carbon::parse($opd_patient->appointment_date)->format('jS M, Y h:i A') }}";
                            document.getElementById('previewHeaderNote').innerText = data.headernotes || '--';
                            document.getElementById('previewFooterNote').innerText = data.footernotes || '--';
                            document.getElementById('prescriptionPdfBtn').href = "{{ url('admin/opd/prescriptions') }}/" +
                                id +
                                "/pdf";

                            // Fill medicines table
                            let tbody = document.getElementById('previewMedicinesTable');
                            tbody.innerHTML = '';
                            if (data.medicines && data.medicines.length) {
                                data.medicines.forEach(function(med) {
                                    let medDisplayName = med.medicine_name || getMedicineName(med.medicine_id) || (med.medicine ? med.medicine.medicine : '') || 'N/A';
                                    let tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="px-2 py-2 border text-xs">${getCategoryName(med.category_id)}</td>
                                        <td class="px-2 py-2 border text-xs font-semibold text-gray-800">${medDisplayName}</td>
                                        <td class="px-2 py-2 border text-xs">${med.dosage || '—'}</td>
                                        <td class="px-2 py-2 border text-xs">${med.route || med.time || 'Oral'}</td>
                                        <td class="px-2 py-2 border text-xs font-semibold text-blue-700">${med.frequency || med.doseInterval || '—'}</td>
                                        <td class="px-2 py-2 border text-xs">${med.duration || '—'}</td>
                                        <td class="px-2 py-2 border text-xs text-gray-600">${med.instructions || '—'}</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                            } else {
                                let tr = document.createElement('tr');
                                tr.innerHTML =
                                    `<td colspan="7" class="text-center text-gray-400 py-4">No medicines found.</td>`;
                                tbody.appendChild(tr);
                            }

                            document.getElementById('prescriptionPreviewModal').classList.remove('hidden');
                        });

                    // Helper functions for display text
                    function getCategoryName(id) {
                        @php
                            $catArr = [];
                            foreach ($medicine_categories as $cat) {
                                $catArr[$cat->id] = $cat->medicine_category;
                            }
                        @endphp
                        const cats = @json($catArr);
                        return cats[id] || '';
                    }

                    function getMedicineName(id) {
                        @php
                            $medArr = [];
                            foreach (\App\Models\Medicine::all() as $med) {
                                $medArr[$med->id] = $med->medicine;
                            }
                        @endphp
                        const meds = @json($medArr);
                        return meds[id] || '';
                    }

                    function getDoseIntervalText(val) {
                        switch (val) {
                            case "1":
                                return "Morning";
                            case "2":
                                return "Morning & Evening";
                            case "3":
                                return "Morning, Noon, Evening";
                            case "4":
                                return "4 times a day";
                            default:
                                return "";
                        }
                    }

                    function getDurationText(val) {
                        switch (val) {
                            case "1":
                                return "1 day";
                            case "3":
                                return "3 days";
                            case "7":
                                return "1 week";
                            case "14":
                                return "2 weeks";
                            case "30":
                                return "1 month";
                            default:
                                return "";
                        }
                    }

                    function getTimeText(val) {
                        switch (val) {
                            case "0":
                                return "After Meal";
                            case "1":
                                return "Before Meal";
                            case "2":
                                return "Bed Time";
                            default:
                                return "";
                        }
                    }


                }

                function closePrescriptionPreviewModal() {
                    document.getElementById('prescriptionPreviewModal').classList.add('hidden');
                }
            </script>


            <script>
                // Tab switching functionality
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.addEventListener('click', function() {
                        // Update tab buttons
                        document.querySelectorAll('.tab-button').forEach(btn => {
                            btn.classList.remove('active', 'border-pink-500', 'text-pink-600');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });
                        this.classList.add('active', 'border-pink-500', 'text-pink-600');
                        this.classList.remove('border-transparent', 'text-gray-500');

                        // Update tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                            content.classList.add('hidden');
                        });
                        document.getElementById(this.dataset.tab).classList.remove('hidden');
                        if (this.dataset.tab === 'diagnosis-tests' && typeof fetchOpdInvestigations === 'function') {
                            fetchOpdInvestigations({{ $opd_patient->id }});
                        }
                    });
                });

                // Auto-switch to active tab from session or URL hash
                const sessionTab = "{{ session('active_tab') }}";
                if (sessionTab) {
                    const targetBtn = document.querySelector(`.tab-button[data-tab="${sessionTab}"]`);
                    if (targetBtn) targetBtn.click();
                } else if (window.location.hash) {
                    const hash = window.location.hash.substring(1);
                    if (hash === 'progress-notes' || hash.startsWith('opd-pnote-detail-')) {
                        const pnBtn = document.querySelector('.tab-button[data-tab="progress-notes"]');
                        if (pnBtn) pnBtn.click();
                    } else if (hash === 'diagnosis-tests' || hash === 'investigations') {
                        const dtBtn = document.querySelector('.tab-button[data-tab="diagnosis-tests"]');
                        if (dtBtn) dtBtn.click();
                    }
                }

                // OPD Billing and Payment Tabs JS Logic
                const tabCharges = document.getElementById('tab-charges');
                if (tabCharges) {
                    tabCharges.addEventListener('click', function() {
                        if (typeof loadOpdBillingCategories === 'function') {
                            loadOpdBillingCategories();
                        }
                        if (typeof loadTableData === 'function') {
                            loadTableData();
                        }
                        if (typeof initializeSelect2 === 'function') {
                            initializeSelect2();
                        }
                    });
                }

                const tabPayments = document.getElementById('tab-payments');
                if (tabPayments) {
                    tabPayments.addEventListener('click', function() {
                        fetchpaymentbyPatient("{{ $opd_patient->opd_no }}");
                    });
                }

                const openOpdPaymentModalBtn = document.getElementById('openOpdPaymentModalBtn');
                const closeOpdPaymentModal = document.getElementById('closeOpdPaymentModal');
                const closeOpdModalBtnBottom = document.getElementById('closeOpdModalBtnBottom');
                const opdPaymentModal = document.getElementById('OpdPaymentModal');

                if (openOpdPaymentModalBtn && opdPaymentModal) {
                    openOpdPaymentModalBtn.addEventListener('click', function() {
                        opdPaymentModal.classList.remove('hidden');
                        opdPaymentModal.classList.add('flex');
                    });
                }
                if (closeOpdPaymentModal && opdPaymentModal) {
                    closeOpdPaymentModal.addEventListener('click', function() {
                        opdPaymentModal.classList.add('hidden');
                        opdPaymentModal.classList.remove('flex');
                    });
                }
                if (closeOpdModalBtnBottom && opdPaymentModal) {
                    closeOpdModalBtnBottom.addEventListener('click', function() {
                        opdPaymentModal.classList.add('hidden');
                        opdPaymentModal.classList.remove('flex');
                    });
                }

                const tabBills = document.getElementById('tab-bills');
                if (tabBills) {
                    tabBills.addEventListener('click', function() {
                        const operationsContent = document.getElementById('content-bills');
                        operationsContent.innerHTML = `
                            <div class="custom-container">
                                <!-- Left Column: Charges Summary -->
                                <div class="left-section">
                                    <div class="bg-white p-6 rounded-lg border border-gray-200" id="chargesBillingContainer">
                                        <p class="text-center py-6 text-gray-500">Loading charges...</p>
                                    </div>
                                </div>
                                <!-- Right Column: Payments & Net Payable -->
                                <div class="right-section">
                                    <div id="paymentBillingContainer">
                                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                                            <p class="text-center py-6 text-gray-500">Loading billing summary...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        const opdNo = "{{ $opd_patient->opd_no }}";
                        fetchOpdBillDetails(opdNo);
                    });
                }

                function fetchOpdBillDetails(opdNo) {
                    var urlcharge = "{{ route('admin.OpdPayment.billcharge', ':id') }}".replace(':id', opdNo);
                    const pdfUrl = "{{ route('admin.OpdPayment.pdf', ':id') }}".replace(':id', opdNo);
                    const pdfPerDayUrl = "{{ route('admin.OpdPayment.pdf_per_day', ':id') }}".replace(':id', opdNo);

                    fetch(urlcharge)
                        .then(response => response.json())
                        .then(data => {
                            const charges = data.charges || [];
                            const consultantCharge = parseFloat(data.consultant_charge) || 0;
                            const totalOtherCharges = parseFloat(data.total_other_charges) || 0;
                            const payments = data.payments || [];
                            
                            const chargesContent = document.getElementById('chargesBillingContainer');
                            const paymentContent = document.getElementById('paymentBillingContainer');

                            let chargesHtml = `
                                <h3 class="text-lg font-medium text-gray-700 mb-4 border-b pb-2">Charges Breakdown</h3>
                                <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Particulars</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white text-sm">
                            `;

                            if (consultantCharge > 0) {
                                let consultDate = "{{ $opd_patient->appointment_date ? \Carbon\Carbon::parse($opd_patient->appointment_date)->format('Y-m-d') : \Carbon\Carbon::parse($opd_patient->created_at)->format('Y-m-d') }}";
                                chargesHtml += `
                                    <tr class="bg-pink-50">
                                        <td class="px-3 py-2 text-gray-700">${formatPaymentDate(consultDate)}</td>
                                        <td class="px-3 py-2 text-gray-700 font-medium">Consultation</td>
                                        <td class="px-3 py-2 text-gray-800 font-semibold">Standard Consultation Fee</td>
                                        <td class="px-3 py-2 text-gray-800 font-bold text-right">&#8377;${consultantCharge.toFixed(2)}</td>
                                    </tr>
                                `;
                            }

                            charges.forEach(c => {
                                const cTotal = parseFloat(c.total || 0);
                                const cType = c.charge_type ? (c.charge_type.charge_type || c.charge_type.name) : (c.charge_category?.charge_type?.charge_type || '-');
                                chargesHtml += `
                                    <tr>
                                        <td class="px-3 py-2 text-gray-700">${formatPaymentDate(c.date)}</td>
                                        <td class="px-3 py-2 text-gray-700 font-medium">${cType}</td>
                                        <td class="px-3 py-2 text-gray-700">${c.charge_category?.charge_category || 'Hospital Charge'} (${c.code || '-'}) x${c.quantity||1}</td>
                                        <td class="px-3 py-2 text-gray-700 text-right">&#8377;${cTotal.toFixed(2)}</td>
                                    </tr>
                                `;
                            });

                            chargesHtml += `
                                    <tr class="bg-gray-100 font-bold">
                                        <td colspan="3" class="px-3 py-2 text-right text-gray-800">Total Charges:</td>
                                        <td class="px-3 py-2 text-right text-pink-700">&#8377;${totalOtherCharges.toFixed(2)}</td>
                                    </tr>
                                </tbody>
                                </table>
                                </div>
                            `;
                            if(chargesContent) chargesContent.innerHTML = chargesHtml;

                            let totalPayment = 0;
                            let totalDiscount = 0;

                            let payHtml = `
                                <div class="bg-white p-6 rounded-lg border border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-700 mb-4 border-b pb-2">Payments Received & Bill Summary</h3>
                                    <div class="overflow-x-auto mb-6">
                                    <table class="min-w-full divide-y divide-gray-200 border">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mode / Reference</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Paid Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white text-sm">
                            `;

                            if (payments.length === 0) {
                                payHtml += `<tr><td colspan="3" class="px-3 py-2 text-center text-gray-500">No payment records found.</td></tr>`;
                            } else {
                                payments.forEach(p => {
                                    const pAmt = parseFloat(p.amount || 0);
                                    const pMode = p.payment_mode?.name || 'Cash';
                                    if (pMode.toLowerCase() === 'discount') {
                                        totalDiscount += pAmt;
                                    } else {
                                        totalPayment += pAmt;
                                    }
                                    payHtml += `
                                        <tr>
                                            <td class="px-3 py-2 text-gray-700">${pMode} ${p.notes ? ' <span class="text-xs text-gray-500">('+p.notes+')</span>' : ''}</td>
                                            <td class="px-3 py-2 text-gray-700">${formatPaymentDate(p.payment_date)}</td>
                                            <td class="px-3 py-2 text-right text-gray-800 font-semibold">&#8377;${pAmt.toFixed(2)}</td>
                                        </tr>
                                    `;
                                });
                            }

                            const grossTotal = totalOtherCharges;
                            const netPayable = (grossTotal - totalDiscount) - totalPayment;

                            payHtml += `
                                        <tr class="bg-gray-100 font-bold">
                                            <td colspan="2" class="px-3 py-2 text-right text-gray-800">Total Paid Amount:</td>
                                            <td class="px-3 py-2 text-right text-green-600">&#8377;${totalPayment.toFixed(2)}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>

                                    <div class="bg-pink-50 p-4 rounded-lg border border-pink-200 space-y-2 text-sm font-semibold">
                                        <div class="flex justify-between text-gray-700">
                                            <span>Total Bill Charges:</span>
                                            <span>&#8377;${grossTotal.toFixed(2)}</span>
                                        </div>
                                        ${totalDiscount > 0 ? `
                                        <div class="flex justify-between text-green-700">
                                            <span>Total Discount:</span>
                                            <span>- &#8377;${totalDiscount.toFixed(2)}</span>
                                        </div>` : ''}
                                        <div class="flex justify-between text-blue-700">
                                            <span>Total Paid:</span>
                                            <span>- &#8377;${totalPayment.toFixed(2)}</span>
                                        </div>
                                        <div class="border-t border-pink-300 pt-2 flex justify-between text-base font-bold ${netPayable > 0 ? 'text-red-600' : 'text-green-700'}">
                                            <span>Net Payable Amount (Unpaid):</span>
                                            <span>&#8377;${netPayable.toFixed(2)}</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t flex flex-wrap items-center justify-between gap-4">
                                        <div>
                                            <label for="opdBillPerDayDate" class="text-xs font-bold text-gray-700 block mb-1">Select Date for Daily Bill:</label>
                                            <select id="opdBillPerDayDate" class="px-3 py-1.5 text-xs border rounded-md shadow-sm bg-white font-medium text-gray-700 focus:ring focus:ring-pink-200">
                                            </select>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="${pdfUrl}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2.5 rounded shadow flex items-center">
                                                <i class="fas fa-print mr-1.5"></i> Print Final Bill
                                            </a>
                                            <a id="opdPrintPerDayLink" href="${pdfPerDayUrl}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded shadow flex items-center">
                                                <i class="fas fa-print mr-1.5"></i> Print Bill Per Day
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;

                            if(paymentContent) paymentContent.innerHTML = payHtml;

                            const dateSelect = document.getElementById('opdBillPerDayDate');
                            if (dateSelect) {
                                let uniqueDates = [];
                                if (consultantCharge > 0) {
                                    let appDate = "{{ $opd_patient->appointment_date ? \Carbon\Carbon::parse($opd_patient->appointment_date)->format('Y-m-d') : \Carbon\Carbon::parse($opd_patient->created_at)->format('Y-m-d') }}";
                                    uniqueDates.push(appDate);
                                }
                                charges.forEach(c => {
                                    if (c.date) uniqueDates.push(c.date.split('T')[0].split(' ')[0]);
                                });
                                uniqueDates = [...new Set(uniqueDates)].sort().reverse();
                                if (uniqueDates.length === 0) uniqueDates.push(new Date().toISOString().split('T')[0]);

                                uniqueDates.forEach(d => {
                                    let opt = document.createElement('option');
                                    opt.value = d;
                                    opt.textContent = formatPaymentDate(d);
                                    dateSelect.appendChild(opt);
                                });

                                const perDayLink = document.getElementById('opdPrintPerDayLink');
                                if (perDayLink && dateSelect.value) {
                                    perDayLink.href = `${pdfPerDayUrl}?date=${dateSelect.value}`;
                                    dateSelect.addEventListener('change', function() {
                                        perDayLink.href = `${pdfPerDayUrl}?date=${this.value}`;
                                    });
                                }
                            }
                        })
                        .catch(err => console.error("Error fetching OPD bill details:", err));
                }
            </script>
            <script>
                // Edit Diagnosis Modal Fill
                function editDiagnosis(id, reportType, reportDate, description, documentUrl = null) {
                    document.getElementById('diagnosisModalTitle').innerText = 'Edit Diagnosis';
                    document.getElementById('diagnosis_id').value = id;
                    document.getElementById('report_type').value = reportType;
                    document.getElementById('report_date').value = reportDate;
                    document.getElementById('description').value = description;
                    document.getElementById('diagnosisForm').action = "{{ url('admin/diagnosis') }}/" + id;
                    document.getElementById('diagnosis_method').value = "PUT";
                    // Show current document if exists
                    if (documentUrl) {
                        document.getElementById('diagnosis-document-link').href = documentUrl;
                        document.getElementById('diagnosis-document-preview').style.display = '';
                    } else {
                        document.getElementById('diagnosis-document-preview').style.display = 'none';
                    }
                    document.getElementById('diagnosisModal').classList.remove('hidden');
                }
                // Reset modal on open for add
                document.querySelectorAll('[onclick*="diagnosisModal"]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('diagnosisModalTitle').innerText = 'Add Diagnosis';
                        document.getElementById('diagnosisForm').reset();
                        document.getElementById('diagnosis_id').value = '';
                        document.getElementById('diagnosisForm').action = "{{ route('admin.diagnosis.store') }}";
                        document.getElementById('diagnosis_method').value = "POST";
                        document.getElementById('diagnosis-document-preview').style.display = 'none';
                    });
                });
                // Cancel button for diagnosis modal
                document.querySelectorAll('.diagnosis-cancel-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('diagnosisModal').classList.add('hidden');
                    });
                });
            </script>

            <style>
                .tab-button {
                    transition: all 0.2s ease;
                    position: relative;
                }

                .tab-button.active {
                    color: #ec4899;
                    /* pink-500 */
                    border-color: #ec4899;
                    /* pink-500 */
                }

                .tab-button:hover:not(.active) {
                    color: #be185d;
                    /* pink-700 */
                    border-color: #f9a8d4;
                    /* pink-300 */
                }
            </style>
            <script>
                function openPrescriptionModal() {
                    document.getElementById('prescriptionModalTitle').innerText = 'Add Prescription';
                    document.getElementById('prescriptionForm').reset();
                    document.getElementById('prescriptionForm').action = "{{ route('opd.prescriptions.store') }}";
                    document.getElementById('prescription_method').value = "POST";
                    document.getElementById('prescription_id').value = "";

                    let tbody = document.querySelector('.opd-prescription-item-container');
                    if (tbody) {
                        while (tbody.children.length > 1) {
                            tbody.removeChild(tbody.lastChild);
                        }
                        let firstRow = tbody.children[0];
                        if (firstRow) {
                            firstRow.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                            let numEl = firstRow.querySelector('.opd-prescription-item-number');
                            if (numEl) numEl.textContent = '1';
                            let datalist = firstRow.querySelector('.medicineDatalist');
                            let master = document.getElementById('masterMedicineList');
                            if (datalist && master) {
                                datalist.innerHTML = master.innerHTML;
                            }
                            let routeSel = firstRow.querySelector('.routeInput');
                            if (routeSel) routeSel.value = 'Oral';
                            let freqSel = firstRow.querySelector('.frequencyInput');
                            if (freqSel) freqSel.value = 'BD';
                            let durInput = firstRow.querySelector('.durationInput');
                            if (durInput) durInput.value = '5 Days';
                        }
                    }

                    document.getElementById('prescriptionModal').classList.remove('hidden');
                }

                function closePrescriptionModal() {
                    document.getElementById('prescriptionModal').classList.add('hidden');
                }

                function filterRowDatalist(row, categoryId) {
                    let datalist = row.querySelector('.medicineDatalist');
                    let master = document.getElementById('masterMedicineList');
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

                // Add new prescription row
                document.getElementById('addOpdPrescriptionItem').onclick = function() {
                    let tbody = document.querySelector('.opd-prescription-item-container');
                    let rowCount = tbody.children.length + 1;
                    let newRow = tbody.children[0].cloneNode(true);
                    let listId = 'opdMedicineList_' + Date.now() + '_' + rowCount;

                    newRow.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                    let numEl = newRow.querySelector('.opd-prescription-item-number');
                    if (numEl) numEl.textContent = rowCount;

                    let medInput = newRow.querySelector('.medicineInput');
                    let dataList = newRow.querySelector('.medicineDatalist');
                    if (medInput && dataList) {
                        medInput.setAttribute('list', listId);
                        dataList.id = listId;
                        let master = document.getElementById('masterMedicineList');
                        if (master) dataList.innerHTML = master.innerHTML;
                    }

                    let routeSel = newRow.querySelector('.routeInput');
                    if (routeSel) routeSel.value = 'Oral';
                    let freqSel = newRow.querySelector('.frequencyInput');
                    if (freqSel) freqSel.value = 'BD';
                    let durInput = newRow.querySelector('.durationInput');
                    if (durInput) durInput.value = '5 Days';

                    tbody.appendChild(newRow);
                };

                // Delete prescription row
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.deleteopdPrescription')) {
                        let tbody = document.querySelector('.opd-prescription-item-container');
                        if (tbody.children.length > 1) {
                            e.target.closest('tr').remove();
                            Array.from(tbody.children).forEach((row, i) => {
                                let numEl = row.querySelector('.opd-prescription-item-number');
                                if (numEl) numEl.textContent = i + 1;
                            });
                        } else {
                            let row = tbody.children[0];
                            row.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                        }
                    }
                });

                // Dynamic medicine filter by category
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('opdCategoryId')) {
                        let categoryId = e.target.value;
                        let row = e.target.closest('tr');
                        filterRowDatalist(row, categoryId);
                    }
                });

                function editPrescription(id) {
                    fetch("{{ url('admin/opd/prescriptions') }}/" + id)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('prescriptionModalTitle').innerText = 'Edit Prescription';
                            document.getElementById('prescriptionForm').action = "{{ url('admin/opd/prescriptions') }}/" + id;
                            document.getElementById('prescription_method').value = "PUT";
                            document.getElementById('prescription_id').value = id;
                            document.getElementById('prescription_date').value = data.prescription_date;
                            document.getElementById('headernotes').value = data.headernotes || '';
                            document.getElementById('footernotes').value = data.footernotes || '';
                            document.getElementById('status').value = data.status || 'active';

                            // Fill medicines
                            let tbody = document.querySelector('.opd-prescription-item-container');
                            tbody.innerHTML = '';
                            let master = document.getElementById('masterMedicineList');
                            let masterHtml = master ? master.innerHTML : '';

                            if (data.medicines && data.medicines.length > 0) {
                                data.medicines.forEach(function(med, idx) {
                                    let row = document.createElement('tr');
                                    let medName = med.medicine_name || (med.medicine ? med.medicine.medicine : '');
                                    let listId = 'opdMedicineList_edit_' + (idx + 1);
                                    let routeVal = med.route || med.time || 'Oral';
                                    let freqVal = med.frequency || med.doseInterval || 'BD';
                                    let durVal = med.duration || '5 Days';

                                    row.innerHTML = `
                                        <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center opd-prescription-item-number" data-item-number="${idx+1}">${idx+1}</td>
                                        <td class="hidden px-2 py-2 whitespace-nowrap">
                                            <select class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 opdCategoryId" name="category_id[]">
                                                <option value="">All</option>
                                                @foreach ($medicine_categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->medicine_category }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap" style="min-width: 190px;">
                                            <input type="text"
                                                list="${listId}"
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 medicineInput font-medium"
                                                name="medicine_name[]"
                                                placeholder="Search or type medicine..."
                                                value="${medName ? medName.replace(/"/g, '&quot;') : ''}"
                                                required
                                                autocomplete="off">
                                            <datalist id="${listId}" class="medicineDatalist">
                                                ${masterHtml}
                                            </datalist>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                            <input type="text"
                                                list="opdDoseList"
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 dosageInput font-medium"
                                                name="dosage[]"
                                                placeholder="e.g. 500 mg, 1 Tab"
                                                value="${(med.dosage || '').replace(/"/g, '&quot;')}"
                                                required
                                                autocomplete="off">
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap" style="min-width: 95px;">
                                            <select name="route[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 routeInput font-medium">
                                                <option value="Oral">Oral</option>
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
                                            <select name="dose_interval[]" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 frequencyInput font-semibold">
                                                <option value="OD">OD (Once Daily)</option>
                                                <option value="BD">BD (Twice Daily)</option>
                                                <option value="TDS">TDS (Thrice Daily)</option>
                                                <option value="QID">QID (4 Times Daily)</option>
                                                 <option value="Continuous Dose">Continuous Dose</option>
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
                                            <input type="text"
                                                list="opdDurationList"
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 durationInput"
                                                name="duration[]"
                                                placeholder="e.g. 5 Days"
                                                value="${durVal ? durVal.replace(/"/g, '&quot;') : '5 Days'}"
                                                autocomplete="off">
                                        </td>
                                        <td class="px-2 py-2" style="min-width: 130px;">
                                            <input type="text"
                                                placeholder="Remarks / Instructions..."
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 instruction"
                                                name="instructions[]"
                                                value="${med.instructions ? med.instructions.replace(/"/g, '&quot;') : ''}">
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap text-center">
                                            <button type="button" class="text-red-500 hover:text-red-700 deleteopdPrescription p-1" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    `;
                                    tbody.appendChild(row);

                                    if (med.category_id) {
                                        row.querySelector('.opdCategoryId').value = med.category_id;
                                        // filterRowDatalist(row, med.category_id);
                                    }

                                    let routeSel = row.querySelector('.routeInput');
                                    if (routeSel && routeVal) {
                                        if (!Array.from(routeSel.options).some(o => o.value === routeVal)) {
                                            routeSel.add(new Option(routeVal, routeVal, true, true));
                                        }
                                        routeSel.value = routeVal;
                                    }

                                    let freqSel = row.querySelector('.frequencyInput');
                                    if (freqSel && freqVal) {
                                        if (!Array.from(freqSel.options).some(o => o.value === freqVal)) {
                                            freqSel.add(new Option(freqVal, freqVal, true, true));
                                        }
                                        freqSel.value = freqVal;
                                    }
                                });
                            } else {
                                document.getElementById('addOpdPrescriptionItem').click();
                            }

                            document.getElementById('prescriptionModal').classList.remove('hidden');
                        });
                }

                function openVitalsModal() {
                    document.getElementById('vitalsModal').classList.remove('hidden');
                }

                function closeVitalsModal() {
                    document.getElementById('vitalsModal').classList.add('hidden');
                }
            </script>

            <!-- Vitals Update Modal -->
            <div id="vitalsModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-t-xl">
                        <div>
                            <h3 class="text-lg font-bold flex items-center">
                                <i class="fas fa-heartbeat mr-2"></i> Record Patient Vitals (Follow-up / Visit)
                            </h3>
                            <p class="text-xs text-pink-100 mt-0.5">Saves a new dated snapshot for this OPD patient</p>
                        </div>
                        <button type="button" onclick="closeVitalsModal()" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
                    </div>
                    <form action="{{ route('admin.opd.update_vitals', $opd_patient->id) }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        <div class="bg-pink-50 border border-pink-200 rounded-lg p-3 text-xs text-pink-900 flex items-start gap-2">
                            <i class="fas fa-info-circle text-pink-600 mt-0.5 text-sm"></i>
                            <div>
                                <strong>Multiple Vitals Recording:</strong> For each follow-up consultation or visit, select the date &amp; time and record new vitals. All previous records are preserved in the <strong>Vitals History</strong> tab, and prescriptions for that date will automatically reflect these vitals.
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-pink-50 rounded-lg border border-pink-100">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Date & Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="recorded_at" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 bg-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Visit Type / Shift</label>
                                <select name="shift" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 bg-white">
                                    <option value="Initial Visit">Initial Visit</option>
                                    <option value="Follow-up">Follow-up Visit</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Evening">Evening</option>
                                    <option value="Emergency">Emergency / STAT</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Recorded By</label>
                                <input type="text" name="recorded_by" value="{{ auth()->user()->name ?? 'Staff' }}" placeholder="Staff / Nurse / Doctor" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 bg-white">
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 pb-1 border-b border-gray-200 flex items-center">
                                <i class="fas fa-stethoscope mr-1.5 text-pink-500"></i> Vital Signs
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Blood Pressure</label>
                                    <input type="text" name="blood_pressure" value="{{ $opd_patient->blood_pressure ?? $opd_patient->bp ?? '' }}" placeholder="e.g. 120/80" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Pulse (bpm)</label>
                                    <input type="text" name="pulse" value="{{ $opd_patient->pulse ?? '' }}" placeholder="e.g. 72" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Temperature (°F)</label>
                                    <input type="text" name="temperature" value="{{ $opd_patient->temperature ?? '' }}" placeholder="e.g. 98.6" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">SpO2 (%)</label>
                                    <input type="text" name="SpO2" value="{{ $opd_patient->SpO2 ?? $opd_patient->spo2 ?? '' }}" placeholder="e.g. 99" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Respiratory Rate (/min)</label>
                                    <input type="text" name="respiratory_rate" value="{{ $opd_patient->respiratory_rate ?? '' }}" placeholder="e.g. 18" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">RBS (mg/dL)</label>
                                    <input type="text" name="rbs" value="{{ $opd_patient->rbs ?? '' }}" placeholder="e.g. 110" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Height (cm)</label>
                                    <input type="text" name="height" value="{{ $opd_patient->height ?? '' }}" placeholder="e.g. 165" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Weight (kg)</label>
                                    <input type="text" name="weight" value="{{ $opd_patient->weight ?? '' }}" placeholder="e.g. 60" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 pb-1 border-b border-gray-200 flex items-center">
                                <i class="fas fa-clipboard-check mr-1.5 text-pink-500"></i> Physical Examination Findings
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">General Appearance</label>
                                    <input type="text" name="general_appearance" value="{{ $opd_patient->general_appearance ?? '' }}" placeholder="e.g. NORMAL" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">CVS</label>
                                    <input type="text" name="cvs" value="{{ $opd_patient->cvs ?? '' }}" placeholder="e.g. S1S2+" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">RS</label>
                                    <input type="text" name="rs" value="{{ $opd_patient->rs ?? '' }}" placeholder="e.g. B/L WHEEZING +" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">P/A</label>
                                    <input type="text" name="pa" value="{{ $opd_patient->pa ?? '' }}" placeholder="e.g. SOFT, NT, BS+" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">CNS</label>
                                    <input type="text" name="cns" value="{{ $opd_patient->cns ?? '' }}" placeholder="e.g. E4V5M6" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Other Systems</label>
                                    <input type="text" name="other_systems" value="{{ $opd_patient->other_systems ?? '' }}" placeholder="Other findings" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Clinical Remarks / Notes</label>
                            <textarea name="notes" rows="2" placeholder="e.g. Follow-up consultation vitals, feeling better..." class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                            <button type="button" onclick="closeVitalsModal()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-5 py-2 text-sm bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-medium shadow-sm transition-colors flex items-center">
                                <i class="fas fa-save mr-1.5"></i> Save &amp; Record Vitals
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </body>
@endsection
