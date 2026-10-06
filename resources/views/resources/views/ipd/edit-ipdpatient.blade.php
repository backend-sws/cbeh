@extends('layouts.admin')
@section('title', 'Edit IPD Patient')
@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                height: 42px;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 40px;
                padding-left: 16px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9ca3af;
            }
        </style>
    @endpush

    <body class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 py-8">
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
            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit IPD Patient</h1>
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-main-color hover:text-pink-700 mt-4 sm:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <form method="POST" action="{{ route('admin.ipd.edit_save', $patient->id) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    @csrf

                    <!-- Patient -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                        <select name="patient" id="patient" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color select2" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $key)
                            <option value="{{ $key->id }}" @if($patient->patient_id == $key->id) selected @endif>
                                {{ $key->name }} <span class="text-xs text-gray-500">{{ $key->email }}</span>
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Case -->
                    <div id="case-container">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Case</label>
                        <input type="text" name="case" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" 
                            placeholder="Enter case details" value="{{$patient->case?$patient->case:''}}">
                    </div>

                    <!-- IPD No -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">IPD No <span class="text-red-500">*</span></label>
                        <input type="text" name="ipd_no" value="{{ $patient->ipd_no }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color" readonly>
                    </div>

                    <!-- Admission Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admission Date <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="admission_date" value="{{ \Carbon\Carbon::parse($patient->admission_date)->format('Y-m-d\TH:i') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" required>
                    </div>

                    <!-- Doctor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                        <select name="doctor" id="doctor" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @if($patient->doctor_id == $doctor->id) selected @endif>
                                {{ $doctor->name }} <span class="text-xs text-gray-500">({{ $doctor->email }})</span>
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bed Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bed Type  <span class="text-red-500">*</span></label>
                        <select name="bed_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" id="bedType">
                            <option value="">Select Bed Type</option>
                            @foreach($bedtype as $type)
                            <option value="{{ $type->id }}" @if($type->id == $patient->bed_type) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bed -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bed <span class="text-red-500">*</span></label>
                        <select name="bed" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" id="bed" required>
                            <option value="">Select Bed</option>
                            @foreach($beds as $bed)
                            <option value="{{ $bed->id }}" @if($bed->id == $patient->bed) selected @endif>{{ $bed->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4 mt-2">
                            <input type="hidden" name="status" value="{{ $patient->status }}" id="status">
                            <button type="button" class="status-success-btn px-4 py-2 rounded-md border border-green-500 @if($patient->status == 1) bg-green-100 ring-2 ring-green-400 @else bg-green-50 @endif text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Active
                                </span>
                            </button>
                            <button type="button" class="status-danger-btn px-4 py-2 rounded-md border border-red-500 @if($patient->status == 0) bg-red-100 ring-2 ring-red-400 @else bg-red-50 @endif text-red-700 hover:bg-red-100 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Inactive
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Vital Signs Section -->
                    <div class="md:col-span-2 mt-2">
                        <h4 class="text-md font-semibold text-gray-700 mb-2 border-b pb-2 flex items-center">
                            <i class="fas fa-heartbeat mr-2 text-pink-600"></i> Vital Signs
                        </h4>
                    </div>

                    <!-- Height -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                        <div class="relative">
                            <input type="text" name="height" value="{{ old('height', $patient->height) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter height">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                cm</div>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <div class="relative">
                            <input type="text" name="weight" value="{{ old('weight', $patient->weight) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter weight">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                kg</div>
                        </div>
                    </div>

                    <!-- Blood Pressure -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                        <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $patient->blood_pressure ?? $patient->bp) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="e.g. 120/80">
                    </div>

                    <!-- Temperature -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                        <div class="relative">
                            <input type="text" name="temperature" value="{{ old('temperature', $patient->temperature) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 98.6">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                °F</div>
                        </div>
                    </div>

                    <!-- Pulse -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pulse (bpm)</label>
                        <div class="relative">
                            <input type="text" name="pulse" value="{{ old('pulse', $patient->pulse) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 72">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                bpm</div>
                        </div>
                    </div>

                    <!-- RBS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RBS (mg/dL)</label>
                        <div class="relative">
                            <input type="text" name="rbs" value="{{ old('rbs', $patient->rbs) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 110">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                mg/dL</div>
                        </div>
                    </div>

                    <!-- SpO2 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SpO2 (%)</label>
                        <div class="relative">
                            <input type="text" name="SpO2" value="{{ old('SpO2', $patient->SpO2) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 98" maxlength="3">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                %</div>
                        </div>
                    </div>

                    <!-- Respiratory Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate (/min)</label>
                        <div class="relative">
                            <input type="text" name="respiratory_rate" value="{{ old('respiratory_rate', $patient->respiratory_rate) }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 18">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                /min</div>
                        </div>
                    </div>

                    <!-- Examination Findings Section -->
                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-semibold text-gray-700 mb-2 border-b pb-2 flex items-center">
                            <i class="fas fa-clipboard-check mr-2 text-pink-600"></i> Physical Examination Findings
                        </h4>
                    </div>

                    <!-- General Appearance -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">General Appearance</label>
                        <input type="text" name="general_appearance" value="{{ old('general_appearance', $patient->general_appearance) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter general appearance">
                    </div>

                    <!-- CVS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CVS</label>
                        <input type="text" name="cvs" value="{{ old('cvs', $patient->cvs) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter CVS findings">
                    </div>

                    <!-- RS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RS</label>
                        <input type="text" name="rs" value="{{ old('rs', $patient->rs) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter RS findings">
                    </div>

                    <!-- P/A -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">P/A</label>
                        <input type="text" name="pa" value="{{ old('pa', $patient->pa) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter P/A findings">
                    </div>

                    <!-- CNS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNS</label>
                        <input type="text" name="cns" value="{{ old('cns', $patient->cns) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter CNS findings">
                    </div>

                    <!-- Other Systems -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other Systems</label>
                        <input type="text" name="other_systems" value="{{ old('other_systems', $patient->other_systems) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                            placeholder="Enter other systems findings">
                    </div>

                    <!-- Symptoms -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Symptoms </label>
                        <textarea name="symptoms" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" >{{ $patient->symptoms }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">{{ $patient->notes }}</textarea>
                    </div>

                    <!-- Form Buttons -->
                    <div class="md:col-span-2 flex justify-end space-x-4 pt-4">
                        <a href="{{ url()->previous() }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                            Update Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Search by name or email",
                allowClear: true
            });

            // Bed Type change handler
            $('#bedType').change(function() {
                var bedTypeId = $(this).val();
                if (bedTypeId) {
                    $.ajax({
                        url: '/admin/bed/allbeds/' + bedTypeId,
                        type: 'GET',
                        success: function(response) {
                            if (response.status === 'success') {
                                var beds = response.data;
                                var bedSelect = $('#bed');
                                bedSelect.empty();
                                bedSelect.append('<option value="">Select Bed</option>');
                                $.each(beds, function(index, bed) {
                                    bedSelect.append($('<option>', {
                                        value: bed.id,
                                        text: bed.name
                                    }));
                                });
                            }
                        }
                    });
                } else {
                    $('#bed').empty().append('<option value="">Select Bed</option>');
                }
            });

            // Patient change handler - load cases
            $('#patient').on('change', function() {
                var patientId = $(this).val();
                if (patientId) {
                    $.ajax({
                        url: '/admin/patients/' + patientId + '/cases',
                        method: 'GET',
                        success: function(response) {
                            let html = `
                                <label class="block text-sm font-medium text-gray-700 mb-1">Case <span class="text-red-500">*</span></label>
                                <select name="case" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" required>
                                    <option value="">Select Case</option>`;
                            response.forEach(function(caseItem) {
                                html += `<option value="${caseItem.id}">${caseItem.description}</option>`;
                            });
                            html += `</select>`;
                            $('#case-container').html(html);
                        },
                        error: function() {
                            $('#case-container').html(`
                                <label class="block text-sm font-medium text-gray-700 mb-1">Case <span class="text-red-500">*</span></label>
                                <input type="text" name="case" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter case details" value="{{$cases?$cases->description:''}}" required>
                            `);
                        }
                    });
                } else {
                    $('#case-container').html(`
                        <label class="block text-sm font-medium text-gray-700 mb-1">Case <span class="text-red-500">*</span></label>
                        <input type="text" name="case" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter case details" value="{{$cases?$cases->description:''}}" required>
                    `);
                }
            });

            // Status toggle buttons
            $('.status-success-btn').on('click', function() {
                $('#status').val(1);
                $(this).addClass('bg-green-100 ring-2 ring-green-400').removeClass('bg-green-50');
                $('.status-danger-btn').addClass('bg-red-50').removeClass('bg-red-100 ring-2 ring-red-400');
            });

            $('.status-danger-btn').on('click', function() {
                $('#status').val(0);
                $(this).addClass('bg-red-100 ring-2 ring-red-400').removeClass('bg-red-50');
                $('.status-success-btn').addClass('bg-green-50').removeClass('bg-green-100 ring-2 ring-green-400');
            });
        });
    </script>
@endpush