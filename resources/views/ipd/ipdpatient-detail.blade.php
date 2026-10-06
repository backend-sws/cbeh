@extends('layouts.admin')
@section('title', 'IPD Patients')
<style>
    .custom-container {
        display: flex;
        width: 100%;
        gap: 20px;
        /* Optional spacing between columns */
    }

    .left-section,
    .right-section {
        flex: 1;
        /* This means each takes 50% of the available width */
        padding: 10px;
        box-sizing: border-box;
        border: none;
        min-height: 200px;
        /* for demo visibility */
    }

    .billing-container {
        background-color: #f9f9f9;
        padding: 10px;
    }

    .billinput {
        padding: 8px;
        width: 100px;
        background-color: rgba(225, 218, 218, 0.88);
    }
</style>
@section('content')

    <body class="bg-gray-100 p-6">

        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800">IPD Patients Details</h1>
            </div>

            <div class="mt-4">
                <div class="flex justify-between items-center mb-4">
                    <a href="{{ url()->previous() }}">
                        <button class="text-main-color customborder px-4 py-2 rounded-lg hover:bg-main-color">
                            &larr; Back
                        </button>
                    </a>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.ipd.print.service_chart', $ipd_patient->id) }}" target="_blank">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Print Service Chart
                            </button>
                        </a>
                        <a href="{{ route('admin.ipd.edit', $ipd_patient->id) }}">
                            <button class="text-main-color customborder px-4 py-2 rounded-lg hover:bg-main-color">
                                Edit
                            </button>
                        </a>
                    </div>
                </div>

                {{-- Tab Menu --}}
                <ul class="flex flex-wrap space-x-6 border-b mb-4" id="tabs">
                    @php $tabs = ['overview' => 'Overview', 'vitals' => 'Vitals Chart', 'medication_chart' => 'Ongoing Medication (MAR)', 'progress_notes' => 'Progress Notes', 'diagnosis' => 'Diagnosis Uploads',  'charges' => 'Bill Entry', 'prescriptions' => 'Prescriptions',  'payments' => 'Payments Receive', 'bills' => 'Bill Details']; @endphp
                    @foreach ($tabs as $key => $label)
                        <li class="tab-item pb-2 border-b-2 cursor-pointer hover:border-blue-500 text-gray-700"
                            data-tab="{{ $key }}" id="tab-{{ $key }}">
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>

                {{-- Tab Contents --}}
                <div id="tab-contents">
                    <div id="content-overview" class="tab-content">
                        <!-- Patient Header Card -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg shadow-md mb-6 text-white">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="flex items-center space-x-4 md:col-span-2">
                                    <img src="{{ $ipd_patient->patient->image ? asset('avatar/' . $ipd_patient->patient->image) : asset('/img/default_user.png') }}"
                                        alt="Patient Image" class="w-24 h-24 rounded-full border-4 border-white">
                                    <div>
                                        <h2 class="text-2xl font-bold">{{ $ipd_patient->patient->name }}</h2>
                                        <p class="text-blue-100">UHID: {{ $patient->patient_unique_id ?? 'N/A' }}</p>
                                        <p class="text-blue-100">IPD No: {{ $ipd_patient->ipd_no ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-100">Admission Date</p>
                                    <p class="text-lg font-semibold">{{ $ipd_patient->admission_date ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('d M Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-100">Status</p>
                                    <p class="text-lg font-semibold">{{ $ipd_patient->discharge_date ? 'Discharged' : 'Admitted' }}</p>
                                    @if($ipd_patient->discharge_date)
                                        <p class="text-xs text-blue-100">{{ \Carbon\Carbon::parse($ipd_patient->discharge_date)->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- IPD & Medical Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- IPD Details -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-hospital-alt mr-2 text-blue-500"></i>IPD Information
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Case Type:</span>
                                        <span class="font-semibold">{{ $ipd_patient->case ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Bed Assigned:</span>
                                        <span class="font-semibold">{{ $ipd_patient->beds ? $ipd_patient->beds->name : 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Bed Type:</span>
                                        <span class="font-semibold">{{ $ipd_patient->bed_type ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Admission Time:</span>
                                        <span class="font-semibold">{{ $ipd_patient->admission_date ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('g:i A') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Vital Signs -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-l-green-500">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-heartbeat mr-2 text-green-500"></i>Vital Signs
                                    </h3>
                                    <button type="button" onclick="openVitalsModal()"
                                        class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-green-50 text-green-700 hover:bg-green-100 rounded-md border border-green-200 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Edit Vitals
                                    </button>
                                </div>
                                @php
                                    $detailBmi = null;
                                    if (!empty($ipd_patient->weight) && !empty($ipd_patient->height)) {
                                        $cH = floatval(preg_replace('/[^0-9.]/', '', $ipd_patient->height));
                                        $cW = floatval(preg_replace('/[^0-9.]/', '', $ipd_patient->weight));
                                        if ($cH > 0 && $cW > 0) {
                                            $mH = ($cH < 3) ? $cH : (($cH <= 8.5) ? ($cH * 0.3048) : ($cH / 100));
                                            if ($mH > 0) $detailBmi = round($cW / ($mH * $mH), 1);
                                        }
                                    }
                                @endphp
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Blood Pressure:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->blood_pressure) ? $ipd_patient->blood_pressure : (!empty($ipd_patient->bp) ? $ipd_patient->bp : 'N/A') }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Pulse Rate:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->pulse) ? $ipd_patient->pulse . ' bpm' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Temperature:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->temperature) ? $ipd_patient->temperature . ' °F' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">SpO2:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->SpO2) ? $ipd_patient->SpO2 . ' %' : (!empty($ipd_patient->spo2) ? $ipd_patient->spo2 . ' %' : 'N/A') }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Respiratory Rate:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->respiratory_rate) ? $ipd_patient->respiratory_rate . ' /min' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">RBS:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->rbs) ? $ipd_patient->rbs . ' mg/dL' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Height:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->height) ? $ipd_patient->height . ' cm' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">Weight:</span>
                                        <span class="font-semibold text-gray-800">{{ !empty($ipd_patient->weight) ? $ipd_patient->weight . ' kg' : 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-500 text-xs block">BMI:</span>
                                        <span class="font-semibold text-gray-800">{{ $detailBmi ? $detailBmi . ' kg/m²' : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Basic Information -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 ">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-user mr-2 text-purple-500"></i>Basic Information
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Gender:</span>
                                        <span class="font-semibold">{{ ucfirst($ipd_patient->patient->gender ?? 'N/A') }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Date of Birth:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->dob ? \Carbon\Carbon::parse($ipd_patient->patient->dob)->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Age:</span>
                                        <span class="font-semibold">
                                            {{ $ipd_patient->patient->age ?? '0' }} Y 
                                            @if($ipd_patient->patient->age_month)
                                                , {{ $ipd_patient->patient->age_month }} M
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Blood Group:</span>
                                        <span class="font-semibold bg-red-100 text-red-700 px-2 py-1 rounded">{{ $ipd_patient->patient->blood_group ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Marital Status:</span>
                                        <span class="font-semibold">{{ ucfirst($ipd_patient->patient->marital_status ?? 'N/A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Aadhar No:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->adhar_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 ">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-phone mr-2 text-orange-500"></i>Contact Information
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Phone:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Alternate Phone:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->alternate_phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-semibold text-xs break-all">{{ $ipd_patient->patient->email ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Guardian Name:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->guardian_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address & Medical History -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Address -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 ">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>Address
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="border-b pb-2">
                                        <span class="text-gray-600 block mb-1">Primary Address:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->address ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 block mb-1">Alternate Address:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->alternate_address ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical History -->
                            <div class="bg-white p-6 rounded-lg shadow-md border-l-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-stethoscope mr-2 text-red-500"></i>Medical History & Symptoms
                                </h3>
                                <div class="space-y-3 text-sm">
                                    <div class="border-b pb-2">
                                        <span class="text-gray-600 block mb-1">Symptoms:</span>
                                        <span class="font-semibold">{{ $ipd_patient->symptoms ?? 'None' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-600 block mb-1">Notes:</span>
                                        <span class="font-semibold">{{ $ipd_patient->notes ?? 'N/A' }}</span>
                                    </div>
                                    <div class="border-b pb-2">
                                        <span class="text-gray-600 block mb-1">Allergies:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->allergies ?? 'None' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 block mb-1">Remarks:</span>
                                        <span class="font-semibold">{{ $ipd_patient->patient->remarks ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physical Examination Findings -->
                        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-l-blue-500 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-clipboard-check mr-2 text-blue-500"></i>Physical Examination Findings
                                </h3>
                                <button type="button" onclick="openVitalsModal()"
                                    class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-md border border-blue-200 transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit Examination
                                </button>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 bg-gray-50 p-4 rounded-lg">
                                <div>
                                    <span class="text-xs text-gray-500 block">General Appearance</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->general_appearance ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">CVS</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->cvs ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">RS (Respiratory)</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->rs ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">P/A (Abdomen)</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->pa ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">CNS</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->cns ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Other Systems</span>
                                    <span class="font-semibold text-sm text-gray-800">{{ $ipd_patient->other_systems ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Consultant Doctor -->
                        <div class="bg-white p-6 rounded-lg shadow-md border-l-4  mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-md mr-2 text-teal-500"></i>Consultant Doctor
                            </h3>
                            <div class="flex items-center space-x-6">
                                <img src="{{ $ipd_patient->doctor->image ? asset('avatar/' . $ipd_patient->doctor->image) : asset('img/doctor.png') }}"
                                    alt="Doctor Image" class="w-20 h-20 rounded-full border-2 border-teal-500">
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-gray-800">Dr. {{ $ipd_patient->doctor->name ?? 'N/A' }}</p>
                                    <p class="text-gray-600 text-sm">{{ $ipd_patient->doctor->email ?? 'N/A' }}</p>
                                    <p class="text-gray-600 text-sm">{{ $ipd_patient->doctor->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Other Sections --}}
                    @foreach (array_keys($tabs) as $key)
                        @if ($key !== 'overview')
                            <div id="content-{{ $key }}" class="tab-content hidden">
                                @if ($key === 'charges')
                                    @include('ipd.charge')
                                @elseif ($key === 'vitals')
                                    @include('ipd.vitals-tab')
                                @elseif ($key === 'medication_chart')
                                    @include('ipd.medication-chart-tab')
                                @elseif ($key === 'progress_notes')
                                    @include('ipd.progress-notes')
                                @elseif ($key === 'prescriptions')
                                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium text-gray-700">Prescriptions</h3>
                                            <button type="button"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                                onclick="openPrescriptionModal()">
                                                <i class="fas fa-plus mr-1"></i> Add Prescription
                                            </button>
                                        </div>
                                        @if (isset($prescriptions) && $prescriptions->count())
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                    <thead>
                                                        <tr class="bg-gray-50">
                                                            <th class="px-4 py-2 text-left">#</th>
                                                            <th class="px-4 py-2 text-left">IPD No</th>
                                                            <th class="px-4 py-2 text-left">Patient Name</th>
                                                            <th class="px-4 py-2 text-left">Doctor Name</th>
                                                            <th class="px-4 py-2 text-left">Created On</th>
                                                            <th class="px-4 py-2 text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach ($prescriptions as $index => $pres)
                                                            <tr>
                                                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                                <td class="px-4 py-2">{{ $ipd_patient->ipd_no }}</td>
                                                                <td class="px-4 py-2">{{ $ipd_patient->patient->name ?? '' }}</td>
                                                                <td class="px-4 py-2">Dr. {{ $ipd_patient->doctor->name ?? '' }}</td>
                                                                <td class="px-4 py-2">
                                                                    {{ \Carbon\Carbon::parse($pres->created_at)->format('jS M, Y h:i A') }}
                                                                </td>
                                                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                                                    <a href="{{ route('admin.prescription.print', $pres->id) }}?ipd_id={{ $ipd_patient->id }}"
                                                                        target="_blank"
                                                                        class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded shadow hover:bg-green-700 mr-1">
                                                                        <i class="fas fa-print mr-1"></i> Print
                                                                    </a>
                                                                    <button type="button" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-1 hover:bg-blue-200"
                                                                        onclick="previewPrescription({{ $pres->id }})">
                                                                        <i class="fas fa-eye mr-1"></i> Preview
                                                                    </button>
                                                                    <button type="button" class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded mr-1 hover:bg-yellow-200"
                                                                        onclick="editPrescription({{ $pres->id }})">
                                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                                    </button>
                                                                    <form action="{{ route('opd.prescriptions.destroy', $pres->id) }}"
                                                                        method="POST" class="inline-block"
                                                                        onsubmit="return confirm('Delete this prescription?')">
                                                                        @csrf @method('DELETE')
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
                                        @else
                                            <div class="text-center py-8 text-gray-500">
                                                <i class="fas fa-prescription-bottle-alt text-4xl text-gray-300 mb-4"></i>
                                                <p>No prescriptions found for this patient.</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-white p-6 rounded-lg shadow-md">
                                        <p class="text-gray-600">No data available for <strong>{{ $key }}</strong>
                                            yet.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>

        <!-- IPD Prescription Modal (Add/Edit) -->
        <div id="prescriptionModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 id="prescriptionModalTitle" class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i>
                        <span>Add IPD Prescription</span>
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
                    <input type="hidden" name="patient_id" value="{{ $ipd_patient->patient_id }}">
                    <input type="hidden" name="doctor_id" value="{{ $ipd_patient->doctor_id }}">
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
                            Doctor Notes
                        </label>
                        <textarea
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                            rows="2" name="headernotes" id="headernotes" placeholder="Enter any doctor notes..."></textarea>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Prescription Items Section -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-medium text-gray-700">Prescription Items</h3>
                            <button type="button" id="addIpdPrescriptionItem"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-1"></i> Add Item
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200"
                                id="ipdPrescriptionTbl">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-8">
                                            #</th>
                                        <th scope="col"
                                            class="px-2 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-28">
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
                                    class="bg-white divide-y divide-gray-200 ipd-prescription-item-container">
                                    <tr>
                                        <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center ipd-prescription-item-number"
                                            data-item-number="1">1</td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <select
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 ipdCategoryId"
                                                name="category_id[]">
                                                <option value="">All</option>
                                                @if(isset($medicine_categories))
                                                    @foreach ($medicine_categories as $cat)
                                                        <option value="{{ $cat->id }}">
                                                            {{ $cat->medicine_category }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap" style="min-width: 190px;">
                                            <input type="text"
                                                list="ipdModalMedicineList_1"
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 medicineInput font-medium"
                                                name="medicine_name[]"
                                                placeholder="Search or type medicine..."
                                                required
                                                autocomplete="off">
                                            <datalist id="ipdModalMedicineList_1" class="medicineDatalist">
                                                @if(isset($medicines))
                                                    @foreach ($medicines as $m)
                                                        <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                                                    @endforeach
                                                @endif
                                            </datalist>
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap" style="min-width: 110px;">
                                            <input type="text"
                                                list="ipdModalDosageList"
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
                                                list="ipdModalDurationList"
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
                                                class="text-red-500 hover:text-red-700 deleteIpdPrescription p-1"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Master Datalists -->
                        <datalist id="masterIpdMedicineListModal">
                            @if(isset($medicines))
                                @foreach ($medicines as $m)
                                    <option value="{{ $m->medicine }}" data-category="{{ $m->category }}">{{ $m->medicine }}</option>
                                @endforeach
                            @endif
                        </datalist>

                        <datalist id="ipdModalDosageList">
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

                        <datalist id="ipdModalDurationList">
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
                            rows="2" name="footernotes" id="footernotes" placeholder="Enter investigation advice..."></textarea>
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

        <!-- IPD Prescription Preview Modal -->
        <div id="prescriptionPreviewModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b pb-4 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i>
                        IPD Prescription Details
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
                        <i class="fas fa-print mr-2"></i> Print Slip
                    </a>
                </div>

                <!-- Modal Body -->
                <div id="prescriptionPreviewContent" class="bg-white p-4 rounded">
                    <!-- Clinic/Doctor Info -->
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <div>
                            <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo"
                                class="h-12 mb-2">
                            <div class="text-sm text-gray-700 font-semibold">{{ setting('app_name') }}</div>
                            <div class="text-xs text-gray-500">{{ setting('address') }}</div>
                            <div class="text-xs text-gray-500">Email: {{ setting('email') }} | Phone: {{ setting('phone') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">Created On:</div>
                            <div class="font-semibold text-gray-800" id="previewCreatedOn">--</div>
                        </div>
                    </div>
                    <hr class="my-2">

                    <!-- Patient & IPD Info -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <div class="text-xs text-gray-500">IPD No:</div>
                            <div class="font-semibold text-gray-800" id="previewIpdNo">{{ $ipd_patient->ipd_no }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Patient Name:</div>
                            <div class="font-semibold text-gray-800" id="previewPatientName">{{ $ipd_patient->patient->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Doctor:</div>
                            <div class="font-semibold text-gray-800" id="previewDoctorName">Dr. {{ $ipd_patient->doctor->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Bed:</div>
                            <div class="font-semibold text-gray-800" id="previewBedName">{{ $ipd_patient->beds->name ?? ($ipd_patient->bed->name ?? 'N/A') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Blood Pressure:</div>
                            <div class="font-semibold text-gray-800" id="previewBloodPressure">{{ $ipd_patient->blood_pressure ?? ($ipd_patient->bp ?? 'N/A') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Pulse:</div>
                            <div class="font-semibold text-gray-800" id="previewPulse">{{ $ipd_patient->pulse ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <hr class="my-2">

                    <!-- Header Note -->
                    <div class="mb-2">
                        <div class="text-xs text-gray-500">Doctor Notes:</div>
                        <div class="font-medium text-gray-700" id="previewHeaderNote">--</div>
                    </div>
                    <hr class="my-2">

                    <!-- Medicines Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-3 py-2 border">Category</th>
                                    <th class="px-3 py-2 border">Medicine</th>
                                    <th class="px-3 py-2 border">Dosage</th>
                                    <th class="px-3 py-2 border">Instructions</th>
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
                        <div class="text-xs text-gray-500">Investigation Advice:</div>
                        <div class="font-medium text-gray-700" id="previewFooterNote">--</div>
                    </div>
                </div>
            </div>
        </div>

        @include('prescription.pricription_detail_model')
        @include('ipd.payment')
        @include('ipd.opration')


        {{-- Diagnosis Upload Modal --}}
        <div id="diagnosisUploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity modal-overlay" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form id="diagnosisUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Upload Diagnosis Report</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type
                                        *</label>
                                    <input type="text" name="report_type" id="report_type" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="e.g., Blood Test, X-Ray, MRI Scan">
                                </div>

                                <div>
                                    <label for="report_date" class="block text-sm font-medium text-gray-700">Report Date
                                        *</label>
                                    <input type="date" name="report_date" id="report_date" required
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="document" class="block text-sm font-medium text-gray-700">Document</label>
                                    <input type="file" name="document" id="document"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <p class="mt-1 text-sm text-gray-500">Supported formats: JPG, PNG, PDF, DOC, DOCX (Max:
                                        10MB)</p>
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Additional notes about the report..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Upload Report
                            </button>
                            <button type="button" id="closeDiagnosisModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal script -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.tab-item');
                const contents = document.querySelectorAll('.tab-content');

                function activateTab(tabId) {
                    contents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    tabs.forEach(tab => {
                        tab.classList.remove('border-blue-500', 'text-blue-600', 'font-semibold');
                    });

                    const targetContent = document.getElementById('content-' + tabId);
                    const targetTab = document.getElementById('tab-' + tabId);
                    if (targetContent) targetContent.classList.remove('hidden');
                    if (targetTab) targetTab.classList.add('border-blue-500', 'text-blue-600', 'font-semibold');
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const target = this.getAttribute('data-tab');
                        activateTab(target);
                    });
                });

                // Set default active tab

                // Diagnosis Uploads Tab Handler
                const tabDiag = document.getElementById('tab-diagnosis');
                if (tabDiag) {
                    tabDiag.addEventListener('click', function() {
                        const diagnosisContent = document.getElementById('content-diagnosis');
                        if (diagnosisContent) {
                            diagnosisContent.innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Diagnosis Uploads</h3>
                <button type="button" id="openDiagnosisModalBtn" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Upload New Report
                </button>
            </div>
            <div id="diagnosisUploadsTableContainer">
                <p class="text-gray-600">Loading diagnosis uploads...</p>
            </div>
        </div>
    `;
                        }

                        const ipdPatientId = {{ $ipd_patient->id }};
                        if (typeof fetchDiagnosisUploads === 'function') fetchDiagnosisUploads(ipdPatientId);
                        if (typeof setupDiagnosisModal === 'function') setupDiagnosisModal();
                    });
                }
                @if (session('success') && (str_contains(session('success'), 'Prescription') || str_contains(session('success'), 'prescription')))
                    activateTab('prescriptions');
                @elseif (session('success') && (str_contains(session('success'), 'Medication') || str_contains(session('success'), 'medication') || str_contains(session('success'), 'Dose') || str_contains(session('success'), 'dose')))
                    activateTab('medication_chart');
                @elseif (session('success') && (str_contains(session('success'), 'Progress') || str_contains(session('success'), 'progress') || str_contains(session('success'), 'Addendum') || str_contains(session('success'), 'addendum')))
                    activateTab('progress_notes');
                @else
                    if (window.location.hash) {
                        let hashTab = window.location.hash.replace('#', '');
                        if (document.getElementById('tab-' + hashTab)) {
                            activateTab(hashTab);
                        } else {
                            activateTab('overview');
                        }
                    } else if (new URLSearchParams(window.location.search).has('mar_date')) {
                        activateTab('medication_chart');
                    } else {
                        activateTab('overview');
                    }
                @endif

                const tabPresc = document.getElementById('tab-prescriptions');
                if (tabPresc) {
                    tabPresc.addEventListener('click', function() {
                        const patientId = "{{ $ipd_patient->patient_id }}";
                        if (typeof window.fetchPrescriptionsByPatient === 'function') {
                            window.fetchPrescriptionsByPatient(patientId);
                        } else if (typeof fetchPrescriptionsByPatient === 'function') {
                            fetchPrescriptionsByPatient(patientId);
                        }
                    });
                }
                // Charges tab click handler
                const tabCharges = document.getElementById('tab-charges');
                if (tabCharges) {
                    tabCharges.addEventListener('click', function() {
                        // Initialize Select2 for charge category when tab is clicked
                        setTimeout(() => {
                            if (typeof $ !== 'undefined' && $.fn.select2) {
                                try {
                                    // Destroy existing instance if it exists
                                    if ($('#charge_category').hasClass("select2-hidden-accessible")) {
                                        $('#charge_category').select2('destroy');
                                    }
                                } catch(e) {}
                                
                                // Reinitialize Select2
                                $('#charge_category').select2({
                                    placeholder: 'Search and Select Category',
                                    allowClear: true,
                                    width: '100%'
                                }).on('change', function (e) {
                                    if (typeof fetchCategoryDetails === 'function') {
                                        fetchCategoryDetails(this.value);
                                    }
                                });
                                console.log('Select2 initialized on charges tab');
                            }
                        }, 100);
                    });
                }

                const tabPayments = document.getElementById('tab-payments');
                if (tabPayments) {
                    tabPayments.addEventListener('click', function() {
                        const operationsContent = document.getElementById('content-payments');
                        if (operationsContent) {
                            operationsContent.innerHTML = `
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Payments</h3>
                <button type="button" id="openpaymentModalBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Add New Payment
                </button>
            </div>
            <div id="PaymentTableContainer"></div>
        </div>
    `;
                        }

                        const Id = "{{ $ipd_patient->ipd_no }}";
                        if (typeof fetchpaymentbyPatient === 'function') fetchpaymentbyPatient(Id);

                        // Wait until the DOM has rendered before attaching event listeners
                        setTimeout(() => {
                            const openBtn = document.getElementById('openpaymentModalBtn');
                            const closeBtn = document.getElementById('closeIpdPaymentModal');
                            const closeBottomBtn = document.getElementById('closeIpdModalBtnBottom');
                            const modal = document.getElementById('IpdPaymentModal');

                            if (openBtn && modal) {
                                openBtn.addEventListener('click', () => {
                                    modal.classList.remove('hidden');
                                    modal.classList.add('flex');
                                });
                            }

                            if (closeBtn && modal) {
                                closeBtn.addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                });
                            }

                            if (closeBottomBtn && modal) {
                                closeBottomBtn.addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                });
                            }
                        }, 0); // defer to ensure newly injected button is ready
                    });
                }
            });
        </script>
        <script>
            function fetchPrescriptionsByPatient(patientId) {
                var urlpre = "{{ route('admin.prescription.prescription_by_patient', ':patientId') }}";
                    urlpre = urlpre.replace(':patientId', patientId);
                    fetch(urlpre)
                        .then(response => response.json())
                        .then(data => {
                            const prescriptionsContent = document.getElementById('content-prescriptions');
                            if (!prescriptionsContent) return;

                            let tableHtml = `
                                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-medium text-gray-700">Prescriptions</h3>
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                            onclick="openPrescriptionModal()">
                                            <i class="fas fa-plus mr-1"></i> Add Prescription
                                        </button>
                                    </div>
                            `;

                            if (Array.isArray(data) && data.length > 0) {
                                tableHtml += `
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    <th class="px-4 py-2 text-left">#</th>
                                                    <th class="px-4 py-2 text-left">IPD No</th>
                                                    <th class="px-4 py-2 text-left">Patient Name</th>
                                                    <th class="px-4 py-2 text-left">Doctor Name</th>
                                                    <th class="px-4 py-2 text-left">Created On</th>
                                                    <th class="px-4 py-2 text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                `;

                                data.forEach((prescription, idx) => {
                                    let createdDate = prescription.created_at ? new Date(prescription.created_at).toLocaleString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '--';
                                    tableHtml += `
                                        <tr>
                                            <td class="px-4 py-2">${idx + 1}</td>
                                            <td class="px-4 py-2">{{ $ipd_patient->ipd_no }}</td>
                                            <td class="px-4 py-2">${prescription.patient?.name ?? '{{ $ipd_patient->patient->name ?? "" }}'}</td>
                                            <td class="px-4 py-2">${prescription.doctor?.name ? 'Dr. ' + prescription.doctor.name : 'Dr. {{ $ipd_patient->doctor->name ?? "" }}'}</td>
                                            <td class="px-4 py-2">${createdDate}</td>
                                            <td class="px-4 py-2 text-center whitespace-nowrap">
                                                <a href="{{ route('admin.prescription.print', '') }}/${prescription.id}?ipd_id={{ $ipd_patient->id }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded shadow hover:bg-green-700 mr-1">
                                                    <i class="fas fa-print mr-1"></i> Print
                                                </a>
                                                <button type="button" class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-1 hover:bg-blue-200" onclick="previewPrescription(${prescription.id})">
                                                    <i class="fas fa-eye mr-1"></i> Preview
                                                </button>
                                                <button type="button" class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded mr-1 hover:bg-yellow-200" onclick="editPrescription(${prescription.id})">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </button>
                                                <form action="{{ route('opd.prescriptions.destroy', '') }}/${prescription.id}" method="POST" class="inline-block" onsubmit="return confirm('Delete this prescription?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded hover:bg-red-200">
                                                        <i class="fas fa-trash mr-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                });

                                tableHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                `;
                            } else {
                                tableHtml += `
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-prescription-bottle-alt text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-600">No prescriptions found for this patient.</p>
                                    </div>
                                `;
                            }

                            tableHtml += `</div>`;
                            prescriptionsContent.innerHTML = tableHtml;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }

                function openPrescriptionModal() {
                    document.getElementById('prescriptionModalTitle').innerHTML = '<i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i><span>Add IPD Prescription</span>';
                    document.getElementById('prescriptionForm').reset();
                    document.getElementById('prescriptionForm').action = "{{ route('opd.prescriptions.store') }}";
                    document.getElementById('prescription_method').value = "POST";
                    document.getElementById('prescription_id').value = "";

                    let tbody = document.querySelector('.ipd-prescription-item-container');
                    if (tbody) {
                        while (tbody.children.length > 1) {
                            tbody.removeChild(tbody.lastChild);
                        }
                        let firstRow = tbody.children[0];
                        if (firstRow) {
                            firstRow.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                            let numEl = firstRow.querySelector('.ipd-prescription-item-number');
                            if (numEl) numEl.textContent = '1';
                            let datalist = firstRow.querySelector('.medicineDatalist');
                            let master = document.getElementById('masterIpdMedicineListModal');
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
                    let master = document.getElementById('masterIpdMedicineListModal');
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

                // Add new prescription item row
                document.addEventListener('click', function(e) {
                    if (e.target && (e.target.id === 'addIpdPrescriptionItem' || e.target.closest('#addIpdPrescriptionItem'))) {
                        let tbody = document.querySelector('.ipd-prescription-item-container');
                        let rowCount = tbody.children.length + 1;
                        let newRow = tbody.children[0].cloneNode(true);
                        let listId = 'ipdModalMedicineList_' + Date.now() + '_' + rowCount;

                        newRow.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                        let numEl = newRow.querySelector('.ipd-prescription-item-number');
                        if (numEl) numEl.textContent = rowCount;

                        let medInput = newRow.querySelector('.medicineInput');
                        let dataList = newRow.querySelector('.medicineDatalist');
                        if (medInput && dataList) {
                            medInput.setAttribute('list', listId);
                            dataList.id = listId;
                            let master = document.getElementById('masterIpdMedicineListModal');
                            if (master) dataList.innerHTML = master.innerHTML;
                        }

                        let routeSel = newRow.querySelector('.routeInput');
                        if (routeSel) routeSel.value = 'Oral';
                        let freqSel = newRow.querySelector('.frequencyInput');
                        if (freqSel) freqSel.value = 'BD';
                        let durInput = newRow.querySelector('.durationInput');
                        if (durInput) durInput.value = '5 Days';

                        tbody.appendChild(newRow);
                    }
                });

                // Delete prescription row
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.deleteIpdPrescription')) {
                        let tbody = document.querySelector('.ipd-prescription-item-container');
                        if (tbody.children.length > 1) {
                            e.target.closest('tr').remove();
                            Array.from(tbody.children).forEach((row, i) => {
                                let numEl = row.querySelector('.ipd-prescription-item-number');
                                if (numEl) numEl.textContent = i + 1;
                            });
                        } else {
                            let row = tbody.children[0];
                            row.querySelectorAll('input, select, textarea').forEach(el => el.value = '');
                            let routeSel = row.querySelector('.routeInput');
                            if (routeSel) routeSel.value = 'Oral';
                            let freqSel = row.querySelector('.frequencyInput');
                            if (freqSel) freqSel.value = 'BD';
                            let durInput = row.querySelector('.durationInput');
                            if (durInput) durInput.value = '5 Days';
                        }
                    }
                });

                // Dynamic medicine filter by category
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('ipdCategoryId')) {
                        let categoryId = e.target.value;
                        let row = e.target.closest('tr');
                        filterRowDatalist(row, categoryId);
                    }
                });

                function editPrescription(id) {
                    fetch("{{ url('admin/opd/prescriptions') }}/" + id)
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('prescriptionModalTitle').innerHTML = '<i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i><span>Edit IPD Prescription</span>';
                            document.getElementById('prescriptionForm').action = "{{ url('admin/opd/prescriptions') }}/" + id;
                            document.getElementById('prescription_method').value = "PUT";
                            document.getElementById('prescription_id').value = id;
                            document.getElementById('prescription_date').value = data.prescription_date;
                            document.getElementById('headernotes').value = data.headernotes || '';
                            document.getElementById('footernotes').value = data.footernotes || '';
                            document.getElementById('status').value = data.status || 'active';

                            let tbody = document.querySelector('.ipd-prescription-item-container');
                            tbody.innerHTML = '';
                            let master = document.getElementById('masterIpdMedicineListModal');
                            let masterHtml = master ? master.innerHTML : '';

                            if (data.medicines && data.medicines.length > 0) {
                                data.medicines.forEach(function(med, idx) {
                                    let row = document.createElement('tr');
                                    let medName = med.medicine_name || (med.medicine ? med.medicine.medicine : '');
                                    let listId = 'ipdModalMedicineList_edit_' + (idx + 1);
                                    let routeVal = med.route || med.time || 'Oral';
                                    let freqVal = med.frequency || med.doseInterval || 'BD';
                                    let durVal = med.duration || '5 Days';

                                    row.innerHTML = `
                                        <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-500 text-center ipd-prescription-item-number" data-item-number="${idx+1}">${idx+1}</td>
                                        <td class="px-2 py-2 whitespace-nowrap">
                                            <select class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-blue-500 ipdCategoryId" name="category_id[]">
                                                <option value="">All</option>
                                                @if(isset($medicine_categories))
                                                    @foreach ($medicine_categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->medicine_category }}</option>
                                                    @endforeach
                                                @endif
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
                                                list="ipdModalDosageList"
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
                                                list="ipdModalDurationList"
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 durationInput"
                                                name="duration[]"
                                                placeholder="e.g. 5 Days"
                                                value="${durVal.replace(/"/g, '&quot;')}"
                                                autocomplete="off">
                                        </td>
                                        <td class="px-2 py-2" style="min-width: 130px;">
                                            <input type="text"
                                                placeholder="Remarks / Instructions..."
                                                class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 instruction"
                                                name="instructions[]"
                                                value="${(med.instructions || '').replace(/"/g, '&quot;')}">
                                        </td>
                                        <td class="px-2 py-2 whitespace-nowrap text-center">
                                            <button type="button" class="text-red-500 hover:text-red-700 deleteIpdPrescription p-1" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    `;
                                    tbody.appendChild(row);

                                    if (med.category_id) {
                                        row.querySelector('.ipdCategoryId').value = med.category_id;
                                        filterRowDatalist(row, med.category_id);
                                    }
                                    let rEl = row.querySelector('.routeInput');
                                    if (rEl && routeVal) rEl.value = routeVal;
                                    let fEl = row.querySelector('.frequencyInput');
                                    if (fEl && freqVal) fEl.value = freqVal;
                                });
                            } else {
                                openPrescriptionModal();
                            }

                            document.getElementById('prescriptionModal').classList.remove('hidden');
                        });
                }

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
                            document.getElementById('previewIpdNo').innerText = "{{ $ipd_patient->ipd_no }}";
                            document.getElementById('previewPatientName').innerText = "{{ $ipd_patient->patient->name }}";
                            document.getElementById('previewDoctorName').innerText = "Dr. {{ $ipd_patient->doctor->name }}";
                            document.getElementById('previewBedName').innerText = "{{ $ipd_patient->beds->name ?? ($ipd_patient->bed->name ?? 'N/A') }}";
                            document.getElementById('previewBloodPressure').innerText = "{{ $ipd_patient->blood_pressure ?? ($ipd_patient->bp ?? 'N/A') }}";
                            document.getElementById('previewPulse').innerText = "{{ $ipd_patient->pulse ?? 'N/A' }}";
                            document.getElementById('previewHeaderNote').innerText = data.headernotes || '--';
                            document.getElementById('previewFooterNote').innerText = data.footernotes || '--';
                            document.getElementById('prescriptionPdfBtn').href = "{{ route('admin.prescription.print', '') }}/" + id + "?ipd_id={{ $ipd_patient->id }}";

                            let tbody = document.getElementById('previewMedicinesTable');
                            tbody.innerHTML = '';
                            if (data.medicines && data.medicines.length) {
                                data.medicines.forEach(function(med) {
                                    let medDisplayName = med.medicine_name || getMedicineName(med.medicine_id) || (med.medicine ? med.medicine.medicine : '') || 'N/A';
                                    let tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="px-3 py-2 border">${getCategoryName(med.category_id)}</td>
                                        <td class="px-3 py-2 border font-medium">${medDisplayName}</td>
                                        <td class="px-3 py-2 border">${med.dosage || ''}</td>
                                        <td class="px-3 py-2 border">${med.instructions || '--'}</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                            } else {
                                let tr = document.createElement('tr');
                                tr.innerHTML = `<td colspan="4" class="text-center text-gray-400 py-4">No medicines found.</td>`;
                                tbody.appendChild(tr);
                            }

                            document.getElementById('prescriptionPreviewModal').classList.remove('hidden');
                        });
                }

                function closePrescriptionPreviewModal() {
                    document.getElementById('prescriptionPreviewModal').classList.add('hidden');
                }

                function getCategoryName(id) {
                    const cats = @json($medicine_categories ? $medicine_categories->pluck('medicine_category', 'id') : []);
                    return cats[id] || '';
                }

                function getMedicineName(id) {
                    const meds = @json($medicines ? $medicines->pluck('medicine', 'id') : []);
                    return meds[id] || '';
                }

                window.openPrescriptionModal = openPrescriptionModal;
                window.closePrescriptionModal = closePrescriptionModal;
                window.filterRowDatalist = filterRowDatalist;
                window.editPrescription = editPrescription;
                window.previewPrescription = previewPrescription;
                window.closePrescriptionPreviewModal = closePrescriptionPreviewModal;
                window.fetchPrescriptionsByPatient = fetchPrescriptionsByPatient;
                window.getCategoryName = getCategoryName;
                window.getMedicineName = getMedicineName;
        </script>
        <script>
            // Fetch diagnosis by patient ID
            function fetchDiagnosisByPatient(patientId) {
                fetch(`admin/diagnosis/test/Diagnosisbypatient/${patientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Process and display the diagnosis data
                            console.log(data.data);
                        } else {
                            console.error('Failed to fetch diagnosis data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        </script>
        <script>
            const tabBills = document.getElementById('tab-bills');
            if (tabBills) {
                tabBills.addEventListener('click', function() {
                    const operationsContent = document.getElementById('content-bills');
                    if (operationsContent) {
                        operationsContent.innerHTML = `
        <div class="custom-container">
            <!-- Left Column -->
            <div class="left-section">
                <div class="billing-container" id="chargesBillingContainer">
                    <!-- Charges content will be inserted here dynamically -->
                </div>
               
            </div>

            <!-- Right Column -->
            <div class="right-section">
                <div id="payment">
                </div>
            </div>
        </div>
    `;
                    }

                    const Id = "{{ $ipd_patient->patient_id }}";
                    const ipdNo = "{{ $ipd_patient->ipd_no }}";
                    if (typeof fetchchargesforbilling === 'function') fetchchargesforbilling(Id, ipdNo);
                    if (typeof fetchpaymentforbill === 'function') fetchpaymentforbill(Id, ipdNo);
                });
            }
        </script>
        <script>
            function formatDate(rawDate) {
                // Remove ordinal suffixes: st, nd, rd, th
                const cleanDate = rawDate.replace(/(\d+)(st|nd|rd|th)/, '$1');

                // Convert to Date object
                const date = new Date(cleanDate);

                if (isNaN(date)) return rawDate; // fallback if date is invalid

                // Format output: 23 Dec 2021
                const options = {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                };
                return date.toLocaleDateString('en-GB', options);
            }
        </script>
        <script>
            function fetchchargesforbilling(id, ipdNo) {
                console.log("ipd id", id);

                var urlcharge = "{{ route('admin.ipd.charge', ':id') }}";
                urlcharge = urlcharge.replace(':id', ipdNo);

                fetch(urlcharge)
                    .then(response => response.json())
                    .then(data => {
                        console.log("charges data", data);

                        const charges = data.data;
                        const chargesContent = document.getElementById('chargesBillingContainer');
                        const totalcharges = document.getElementById('totel-charge');

                        if (charges.length > 0) {
                            let tableHtml = `
                    <div class="bg-white p-6 ">
                    <h1>Charge</h1>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Charge Type</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Code</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                                  
                                   
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                `;

                            // Get delete route base (without ID)
                            const deleteRouteTemplate = "{{ route('admin.charge.delete', ':id') }}";
                            var totalCharge = 0;
                            charges.forEach(charge => {
                                const deleteUrl = deleteRouteTemplate.replace(':id', charge.id);
                                totalCharge += parseFloat(charge.total);

                                tableHtml += `
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${formatDate(charge.date)}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${charge.charge_type?charge.charge_type.name: '-'}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${charge.code ?? '-'}</td>
                            <td class="px-4 py-2 text-sm text-gray-700"> &#8377;${charge.total ?? '-'}</td>
                            
                        </tr>
                       
                    `;
                            });

                            tableHtml += ` <tr>
                        <td>Total Charge</td><td></td><td></td><td> &#8377;${totalCharge}</td>
                        </tr>
                            </tbody>
                        </table>
                       
                    </div>
                `;

                            chargesContent.innerHTML = tableHtml;


                        } else {
                            chargesContent.innerHTML = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Charge Type</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Code</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Standard Charge</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Applied Charge</th>
                              
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-sm text-gray-700 text-center">No charges found for this patient.</td>
                            </tr>
                        </tbody>
                    </table>
                `;
                        }
                    })
                    .catch(error => console.error('Error fetching charges data:', error));
            }

            // Optional: utility function to format date
            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }

            function fetchpaymentforbill(id, ipd_no) {
                console.log("ipd id", id);
                var ipd_id = {{ $ipd_patient->id }}
                var urldischarge = "{{ route('admin.ipd.discharge.patient', ':id') }}";
                urldischarge = urldischarge.replace(':id', ipd_id);
                var urlcharge = "{{ route('admin.Payment.billcharge', ':id') }}";
                urlcharge = urlcharge.replace(':id', ipd_no);
                const pdfRouteTemplate = "{{ route('admin.Payment.pdf', ['id' => ':id']) }}";
                const pdfPerDayRouteTemplate = "{{ route('admin.Payment.pdf_per_day', ['id' => ':id']) }}";
                const pdfUrl = pdfRouteTemplate.replace(':id', ipd_no);
                const pdfPerDayUrl = pdfPerDayRouteTemplate.replace(':id', ipd_no);
                const dischargeRouteTemplate = "{{ route('admin.ipd.discharge', ['id' => ':id']) }}";
                const dischargeUrl = dischargeRouteTemplate.replace(':id', id);

                const dischargeDate = @json($ipd_patient->discharge_date);


                let totalPayment = 0;
                let totalDiscount = 0;

                fetch(urlcharge)
                    .then(response => response.json())
                    .then(data => {
                        console.log("bill charge=", data)
                        const ipdPayments = data.payments || [];
                        // const bedCharge = parseFloat(data.bed_charges_from_today) || 0;
                        const totalOtherCharges = parseFloat(data.total_other_charges) || 0;

                        const grossTotal = totalOtherCharges;

                        const PaymentTableContainer = document.getElementById('payment');

                        // Start table HTML
                        let tableHtml = `
                <div class="bg-white p-6">
                    <h1>Payment</h1>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Payment Mode</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Paid Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
            `;

                        ipdPayments.forEach(payment => {
                            const paymentAmount = parseFloat(payment.amount || 0);
                            const paymentMode = payment.payment_mode?.name || 'N/A';
                            const formattedDate = formatDate(payment.payment_date);

                            // Check if payment mode is discount
                            if (paymentMode.toLowerCase() === 'discount') {
                                totalDiscount += paymentAmount;
                            } else {
                                totalPayment += paymentAmount;
                            }

                            tableHtml += `
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700">${paymentMode}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">${formattedDate}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">&#8377;${paymentAmount}</td>
                    </tr>
                `;
                        });

                        const netPayable = (grossTotal - totalDiscount) - totalPayment;

                        tableHtml += `
                <tr><td>Total Payment</td><td></td><td> &#8377;${totalPayment}</td></tr>
                <tr><th>Bill Summary</th><th></th><th></th></tr>
               
                <tr><td>Total Charges:</td><td></td><td>&#8377;${totalOtherCharges.toFixed(2)}</td></tr>
                <tr><td>Gross Total:</td><td></td><td>&#8377;<span id="grossTotal">${grossTotal.toFixed(2)}</span></td></tr>
                <tr><td>Discount:</td><td></td><td>&#8377;<span id="discountTotal">${totalDiscount.toFixed(2)}</span></td></tr>
            {{--     <tr><td>Discount By:</td><td></td><td><input type="text"  class="billinput" id="discountby" /></td></tr>
                <tr><td>Discount In Percentage (%) :</td><td></td><td><input type="number" class="billinput" value="0" id="discount" /></td></tr>
                <tr><td>Tax In Percentage (%) :</td><td></td><td><input type="number" value="0" class="billinput" id="tax" /></td></tr>
                <tr><td>Other Charges:</td><td></td><td><input type="number" value="0" class="billinput" id="otherchage" /></td></tr>   --}}
               
                <tr><td>Paid Amount:</td><td></td><td>&#8377;${totalPayment}</td></tr>
                <tr><td>Net Payable Amount (Unpaid):</td><td></td><td>&#8377;<span id="netPayable">${netPayable.toFixed(2)}</span></td></tr>
                </tbody>
                </table>
                <br/>
<div style="margin-top: 10px; margin-bottom: 5px;">
    <label for="billPerDayDate" style="font-size: 14px; font-weight: bold;">Select Date for Day Bill: </label>
    <select id="billPerDayDate" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc; min-width: 150px;">
        <!-- Options populated dynamically -->
    </select>
</div>
<a id="printBillBtn" href="${pdfUrl}" target="_blank">
    <button style="background:green; padding:10px; border-radius:8px; color:white">Print Final Bill</button>
</a>
<a id="printBillPerDayBtn" href="${pdfPerDayUrl}" target="_blank">
    <button style="background:blue; padding:10px; border-radius:8px; color:white">Print Bill Per Day</button>
</a>

            `;
                        if (!dischargeDate) {
                            tableHtml += `
                    <form action="${urldischarge}" method="POST" id="ipddischarge" onsubmit="formSubmit2(event, this, false, 'ipddischarge')">
                        @csrf
                        @method('PUT')
                        <button type="submit" style="margin-top: 10px; background: red; color: white; padding: 8px 12px; border-radius: 6px;">Discharge Patient</button>
                    </form>
                `;
                        }

                        // Inject HTML first
                        PaymentTableContainer.innerHTML = tableHtml;

                        // Populate the Date Select Box
                        const dateSelect = document.getElementById('billPerDayDate');
                        const allCharges = data.charges || [];
                        const uniqueDates = [...new Set(allCharges.map(c => {
                            // Extract YYYY-MM-DD from charge date
                            return c.date.split('T')[0];
                        }))].sort().reverse(); // Show latest dates first

                        if (uniqueDates.length > 0) {
                            uniqueDates.forEach(date => {
                                const option = document.createElement('option');
                                option.value = date;
                                option.text = formatDate(date); // Use existing formatDate JS helper
                                dateSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = new Date().toISOString().split('T')[0];
                            option.text = "No charges found";
                            dateSelect.appendChild(option);
                        }

                        // Add event listeners after elements are in the DOM
                        const discountInput = document.getElementById('discount');
                        const taxInput = document.getElementById('tax');
                        const otherChargeInput = document.getElementById('otherchage');
                        const discountby = document.getElementById('discountby');
                        const printBillBtn = document.getElementById('printBillBtn');
                        const printBillPerDayBtn = document.getElementById('printBillPerDayBtn');

                        const calculateBill = () => {
                            let finalTotal = 0;
                            const discount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;
                            const tax = taxInput ? (parseFloat(taxInput.value) || 0) : 0;
                            const otherCharge = otherChargeInput ? (parseFloat(otherChargeInput.value) || 0) : 0;
                            const discountByName = discountby ? (discountby.value || 'N/A') : 'N/A';
                            
                            let discountedTotal = grossTotal - (discount / 100 * grossTotal);
                            let taxedTotal = discountedTotal + (tax / 100 * discountedTotal);
                            
                            finalTotal = taxedTotal + otherCharge;

                              if (netPayableElem) {
                                // Net Payable = (Gross Total - Waiver/Discount Payments) - Cash/Online Payments
                                let unpaidAmount = (finalTotal - totalDiscount) - totalPayment;
                                netPayableElem.innerText = unpaidAmount.toFixed(2);
                            }

                            // Update PDF URLs with parameters
                            const selectedDay = dateSelect.value;
                            const params = `?discount=${discount}&tax=${tax}&otherCharge=${otherCharge}&finalTotal=${finalTotal.toFixed(2)}&discountBy=${encodeURIComponent(discountByName)}`;
                            
                            const updatedPdfUrl = pdfUrl + params;
                            const updatedPdfPerDayUrl = pdfPerDayUrl + params + `&date=${selectedDay}`;

                            if (printBillBtn) printBillBtn.setAttribute('href', updatedPdfUrl);
                            if (printBillPerDayBtn) printBillPerDayBtn.setAttribute('href', updatedPdfPerDayUrl);
                        };

                        // Initial calculation to set URLs
                        calculateBill();

                        // Re-calculate when date changes
                        dateSelect.addEventListener('change', calculateBill);

                        if (discountInput) discountInput.addEventListener('input', calculateBill);
                        if (taxInput) taxInput.addEventListener('input', calculateBill);
                        if (otherChargeInput) otherChargeInput.addEventListener('input', calculateBill);
                        if (discountby) discountby.addEventListener('input', calculateBill);
                    })
                    .catch(error => console.error('Error fetching payment data:', error));
            }

            // Utility to format date
            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }


            // Diagnosis Upload Functions
            function setupDiagnosisModal() {
                const modal = document.getElementById('diagnosisUploadModal');
                const openBtn = document.getElementById('openDiagnosisModalBtn');
                const closeBtn = document.getElementById('closeDiagnosisModal');
                const form = document.getElementById('diagnosisUploadForm');

                if (openBtn) {
                    openBtn.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                        document.getElementById('report_date').valueAsDate = new Date();
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        form.reset();
                    });
                }

                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        form.reset();
                    }
                });

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    uploadDiagnosisReport();
                });
            }

            function fetchDiagnosisUploads(ipdPatientId) {
                const url = "{{ route('admin.ipd.diagnosis_uploads.index', ':id') }}".replace(':id', ipdPatientId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('diagnosisUploadsTableContainer');

                        if (data.status === 'success' && data.data.length > 0) {
                            let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;
                            let downloadRouteTemplate =
                                "{{ route('admin.ipd.diagnosis_uploads.download', ['id' => ':id']) }}";

                            data.data.forEach((item, index) => {
                                const reportDate = new Date(item.report_date).toLocaleDateString();
                                const downloadUrl = item.document ? downloadRouteTemplate.replace(':id', item.id) :
                                    '#';
                                const documentName = item.document ? item.document.split('/').pop() : 'No document';

                                tableHtml += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.report_type}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${reportDate}</td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
    ${item.document ? 
        `<a href="${downloadUrl}" class="text-blue-600 hover:text-blue-900" target="_blank">
                    <i class="fas fa-download mr-1"></i>${documentName}
                </a>` : 
        '<span class="text-gray-400">No document</span>'
    }
</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${item.description || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="deleteDiagnosisUpload(${item.id})" 
                                    class="text-red-600 hover:text-red-900 ml-2" title="Delete Report">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                            });

                            tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
                            container.innerHTML = tableHtml;
                        } else {
                            container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No diagnosis reports uploaded yet.</p>
                        <p class="text-gray-400 text-sm mt-2">Click "Upload New Report" to add the first one.</p>
                    </div>
                `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching diagnosis uploads:', error);
                        const container = document.getElementById('diagnosisUploadsTableContainer');
                        container.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p>Failed to load diagnosis reports.</p>
                </div>
            `;
                    });
            }

            function uploadDiagnosisReport() {
                const ipdPatientId = {{ $ipd_patient->id }};
                const form = document.getElementById('diagnosisUploadForm');
                const formData = new FormData(form);

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';
                submitBtn.disabled = true;

                const url = "{{ route('admin.ipd.diagnosis_uploads.store', ':id') }}".replace(':id', ipdPatientId);

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('diagnosisUploadModal').classList.add('hidden');
                            form.reset();
                            // Show success notification
                            const notification = document.createElement('div');
                            notification.className =
                                'fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white bg-green-500 z-50';
                            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Report uploaded successfully!</span>
                </div>
            `;
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);

                            fetchDiagnosisUploads(ipdPatientId);
                        } else {
                            throw new Error(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error uploading report:', error);
                        // Show error notification
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white bg-red-500 z-50';
                        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Failed to upload report!</span>
            </div>
        `;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            }

            function deleteDiagnosisUpload(id) {
                if (!confirm('Are you sure you want to delete this diagnosis report?')) return;

                const url = "{{ route('admin.ipd.diagnosis_uploads.destroy', ':id') }}".replace(':id', id);

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Refresh the diagnosis tab
                            document.getElementById('tab-diagnosis').click();

                            // Show success notification
                            const notification = document.createElement('div');
                            notification.className =
                                'fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white bg-green-500 z-50';
                            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Report deleted successfully!</span>
                </div>
            `;
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting report:', error);
                        // Show error notification
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white bg-red-500 z-50';
                        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Failed to delete report!</span>
            </div>
        `;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                    });
            }

            function openVitalsModal() {
                const modal = document.getElementById('vitalsModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }
            window.openVitalsModal = openVitalsModal;

            function closeVitalsModal() {
                const modal = document.getElementById('vitalsModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }
            window.closeVitalsModal = closeVitalsModal;
        </script>

        <!-- Vitals Update Modal -->
        <div id="vitalsModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-xl">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-heartbeat mr-2"></i> Update Patient Vitals & Examination Findings
                    </h3>
                    <button type="button" onclick="closeVitalsModal()" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
                </div>
                <form action="{{ route('admin.ipd.update_vitals', $ipd_patient->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Date & Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="recorded_at" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Ward Round / Shift</label>
                            <select name="shift" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="Morning">Morning Shift (8 AM)</option>
                                <option value="Evening">Evening Shift (2 PM)</option>
                                <option value="Night">Night Shift (8 PM)</option>
                                <option value="Doctor Round">Doctor / Consultant Round</option>
                                <option value="Emergency">Emergency / STAT</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Recorded By</label>
                            <input type="text" name="recorded_by" value="{{ auth()->user()->name ?? 'Staff' }}" placeholder="Staff / Nurse name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 bg-white">
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 pb-1 border-b border-gray-200 flex items-center">
                            <i class="fas fa-stethoscope mr-1.5 text-blue-500"></i> Vital Signs
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Blood Pressure</label>
                                <input type="text" name="blood_pressure" value="{{ $ipd_patient->blood_pressure ?? $ipd_patient->bp ?? '' }}" placeholder="e.g. 120/80" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pulse (bpm)</label>
                                <input type="text" name="pulse" value="{{ $ipd_patient->pulse ?? '' }}" placeholder="e.g. 72" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Temperature (°F)</label>
                                <input type="text" name="temperature" value="{{ $ipd_patient->temperature ?? '' }}" placeholder="e.g. 98.6" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">SpO2 (%)</label>
                                <input type="text" name="SpO2" value="{{ $ipd_patient->SpO2 ?? $ipd_patient->spo2 ?? '' }}" placeholder="e.g. 99" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Respiratory Rate (/min)</label>
                                <input type="text" name="respiratory_rate" value="{{ $ipd_patient->respiratory_rate ?? '' }}" placeholder="e.g. 18" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">RBS (mg/dL)</label>
                                <input type="text" name="rbs" value="{{ $ipd_patient->rbs ?? '' }}" placeholder="e.g. 110" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Height (cm)</label>
                                <input type="text" name="height" value="{{ $ipd_patient->height ?? '' }}" placeholder="e.g. 165" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Weight (kg)</label>
                                <input type="text" name="weight" value="{{ $ipd_patient->weight ?? '' }}" placeholder="e.g. 60" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3 pb-1 border-b border-gray-200 flex items-center">
                            <i class="fas fa-clipboard-check mr-1.5 text-blue-500"></i> Physical Examination Findings
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">General Appearance</label>
                                <input type="text" name="general_appearance" value="{{ $ipd_patient->general_appearance ?? '' }}" placeholder="e.g. NORMAL" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">CVS</label>
                                <input type="text" name="cvs" value="{{ $ipd_patient->cvs ?? '' }}" placeholder="e.g. S1S2+" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">RS</label>
                                <input type="text" name="rs" value="{{ $ipd_patient->rs ?? '' }}" placeholder="e.g. B/L WHEEZING +" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">P/A</label>
                                <input type="text" name="pa" value="{{ $ipd_patient->pa ?? '' }}" placeholder="e.g. SOFT, NT, BS+" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">CNS</label>
                                <input type="text" name="cns" value="{{ $ipd_patient->cns ?? '' }}" placeholder="e.g. E4V5M6" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Other Systems</label>
                                <input type="text" name="other_systems" value="{{ $ipd_patient->other_systems ?? '' }}" placeholder="Other findings" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Clinical Remarks / Notes</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Patient comfortable, IV fluids running, no acute distress..." class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                        <button type="button" onclick="closeVitalsModal()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-colors">Save Vitals</button>
                    </div>
                </form>
            </div>
        </div>
    </body>

@endsection
