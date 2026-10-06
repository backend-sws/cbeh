@extends('layouts.admin')
@section('title', 'Edit OPD Patient')
@section('content')
    <div class="bg-gray-50 min-h-screen p-6">
        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-md bg-green-50 text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-md bg-red-50 text-red-800 border border-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-md bg-red-50 text-red-800 border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto">
            <div class="mb-6 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center">
                <a href="{{ route('admin.opd.index') }}" class="inline-flex items-center text-main-color hover:text-pink-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to OPD Patients
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-2xl font-semibold text-gray-800">Edit OPD Patient</h2>
                </div>

                <form id="edit_opd_patient" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6" method="post"
                    action="{{ route('admin.opd.edit_save', $id) }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span
                                class="text-red-500">*</span></label>
                        <select name="patient" id="patient"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            required>
                            <option value="">Select Patient Name</option>
                            @foreach ($patients as $key)
                                <option value="{{ $key->id }}" @if ($patient->patient_id == $key->id) selected @endif>
                                    {{ $key->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">OPD No <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="opd_no" id="opd_no" value="{{ $patient->opd_no }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color"
                            readonly>
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Vital Signs</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                        <input type="text" name="height" value="{{ $patient->height }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter height">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <div class="relative">
                            <input type="text" name="weight" value="{{ $patient->weight }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="Enter weight">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                kg</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                        <input type="text" name="blood_pressure" value="{{ $patient->blood_pressure }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="e.g. 120/80">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                        <div class="relative">
                            <input type="text" name="temperature" value="{{ $patient->temperature ?? '' }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="e.g. 98.6">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                °F</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pulse (bpm)</label>
                        <div class="relative">
                            <input type="text" name="pulse" value="{{ $patient->pulse ?? '' }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="e.g. 72">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                bpm</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RBS (mg/dL)</label>
                        <div class="relative">
                            <input type="text" name="rbs" value="{{ $patient->rbs ?? '' }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="e.g. 110">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                mg/dL</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SpO2 (%)</label>
                        <div class="relative">
                            <input type="text" name="SpO2" value="{{ $patient->SpO2 }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="e.g. 98" maxlength="3">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                %</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate (/min)</label>
                        <div class="relative">
                            <input type="text" name="respiratory_rate" value="{{ $patient->respiratory_rate ?? '' }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                                placeholder="e.g. 18">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                /min</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Casuality</label>
                        <select name="casuality"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                            <option value="">Select...</option>
                            <option value="Yes" @if ($patient->casuality == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if ($patient->casuality == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Examination Findings</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">General Appearance</label>
                        <input type="text" name="general_appearance" value="{{ $patient->general_appearance ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter general appearance">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CVS</label>
                        <input type="text" name="cvs" value="{{ $patient->cvs ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter CVS findings">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RS</label>
                        <input type="text" name="rs" value="{{ $patient->rs ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter RS findings">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">P/A</label>
                        <input type="text" name="pa" value="{{ $patient->pa ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter P/A findings">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNS</label>
                        <input type="text" name="cns" value="{{ $patient->cns ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter CNS findings">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other Systems</label>
                        <input type="text" name="other_systems" value="{{ $patient->other_systems ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter other systems findings">
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Consultation Details</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span
                                class="text-red-500">*</span></label>
                        <select name="doctor" id="doctor"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            required>
                            <option value="">Select Doctor Name</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @if ($patient->doctor_id == $doctor->id) selected @endif>
                                    {{ $doctor->name }} {{ $doctor->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Standard Charge <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                ₹</div>
                            <input type="text" name="standard_charge" id="standard_charge"
                                value="{{ $patient->standard_charge }}"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color"
                                readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="tax" id="tax"
                                value="{{ $patient->tax ?? 0 }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                %</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="discount" id="discount"
                                value="{{ $patient->discount ?? 0 }}"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                %</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Final Amount (₹)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                ₹</div>
                            <input type="text" name="final_amount" id="final_amount"
                                value="{{ $patient->final_amount ?? '' }}"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color"
                                readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Mode <span
                                class="text-red-500">*</span></label>
                        <select name="payment_mode"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            required>
                            <option value="">Select Payment Mode</option>
                            <option value="Cash" @if ($patient->payment_mode == 'Cash') selected @endif>Cash</option>
                            <option value="Card" @if ($patient->payment_mode == 'Card') selected @endif>Card</option>
                            <option value="UPI" @if ($patient->payment_mode == 'UPI') selected @endif>UPI</option>
                            <option value="Net Banking" @if ($patient->payment_mode == 'Net Banking') selected @endif>Net
                                Banking</option>
                            <option value="Credit" @if ($patient->payment_mode == 'Credit') selected @endif>Credit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                        <input type="text" name="reference" value="{{ $patient->reference }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter reference">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date</label>
                        <input type="datetime-local" name="appointment_date" id="appointment_date"
                            value="{{ $patient->appointment_date ? \Carbon\Carbon::parse($patient->appointment_date)->format('Y-m-d\TH:i') : '' }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Symptoms </label>
                        <textarea name="symptoms" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter symptoms">{{ $patient->symptoms }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"
                            placeholder="Enter notes">{{ $patient->notes }}</textarea>
                    </div>

                    <div class="md:col-span-2" style="visibility: hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Is Old Patient</label>
                        <div class="flex space-x-4 mt-2">
                            <input type="hidden" name="is_old_patient" id="is_old_patient"
                                value="{{ $patient->is_old_patient }}">
                            <button type="button"
                                class="status-success-btn px-4 py-2 rounded-md border border-green-500 bg-green-50 text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Yes
                                </span>
                            </button>
                            <button type="button"
                                class="status-danger-btn px-4 py-2 rounded-md border border-red-500 bg-red-50 text-red-700 hover:bg-red-100 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    No
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end space-x-4 pt-4">
                        <button type="button"
                            class="cancel-btn px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" name="action" value="save"
                            class="px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize is_old_patient button states
            if ($('#is_old_patient').val() == 1) {
                $('.status-success-btn').addClass('bg-green-100 ring-2 ring-green-400');
            } else {
                $('.status-danger-btn').addClass('bg-red-100 ring-2 ring-red-400');
            }

            // Is Old Patient toggle
            $('.status-success-btn').on('click', function() {
                $('#is_old_patient').val(1);
                $(this).addClass('bg-green-100 ring-2 ring-green-400');
                $('.status-danger-btn').removeClass('bg-red-100 ring-2 ring-red-400');
            });

            $('.status-danger-btn').on('click', function() {
                $('#is_old_patient').val(0);
                $(this).addClass('bg-red-100 ring-2 ring-red-400');
                $('.status-success-btn').removeClass('bg-green-100 ring-2 ring-green-400');
            });

            // Doctor select - fetch charges
            $('#doctor').on('change', function() {
                let doctorId = $(this).val();
                if (doctorId) {
                    $.get("{{ url('admin/opd-patient/doctor-charge') }}/" + doctorId, function(data) {
                        $('#standard_charge').val(data.charge ?? 0);
                        calculateFinalAmount();
                    });
                } else {
                    $('#standard_charge').val('');
                    calculateFinalAmount();
                }
            });

            // Calculate final amount when tax/discount changes
            $('#tax, #discount, #standard_charge').on('input', function() {
                calculateFinalAmount();
            });

            function calculateFinalAmount() {
                let charge = parseFloat($('#standard_charge').val()) || 0;
                let taxPercent = parseFloat($('#tax').val()) || 0;
                let discountPercent = parseFloat($('#discount').val()) || 0;

                let taxAmount = (charge * taxPercent) / 100;
                let discountAmount = (charge * discountPercent) / 100;
                let finalAmount = charge + taxAmount - discountAmount;

                $('#final_amount').val(finalAmount.toFixed(2));
            }

            // SpO2 input validation - only allow numbers and limit to 3 digits
            $('input[name="SpO2"]').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 3) {
                    this.value = this.value.slice(0, 3);
                }
                // Validate SpO2 range (0-100)
                let spo2Value = parseInt(this.value);
                if (spo2Value > 100) {
                    this.value = '100';
                }
            });

            // Cancel button behavior
            $('.cancel-btn').on('click', function() {
                window.location.href = "{{ route('admin.opd.index') }}";
            });
        });
    </script>
@endpush