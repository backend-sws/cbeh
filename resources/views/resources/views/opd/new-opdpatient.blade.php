@extends('layouts.admin')
@section('title', 'New OPD Patient')
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
            <div class="mb-4 px-4 py-3 rounded-md bg-yellow-50 text-yellow-800 border border-yellow-200">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-6xl mx-auto">
            <div class="mb-6 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center">
                <a href="{{ route('admin.opd.index') }}"
                    class="inline-flex items-center text-main-color hover:text-pink-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to OPD Patients
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-main-color to-pink-600">
                    <h2 class="text-2xl font-semibold text-black">New OPD Patient Registration</h2>
                </div>

                <div class="p-6 bg-gray-50 rounded-lg mb-6 border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-full bg-main-color text-white mr-2">1</span>
                        Patient Selection
                    </h3>
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <div class="w-full md:w-1/2">
                            <select name="patient_id" id="patient_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                required>
                                <option value="">Search by Name or UHID</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-4 w-full md:w-auto">
                            <button type="button" id="search_patient_btn"
                                class="w-full md:w-auto px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200 shadow-md flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                                Search Patient
                            </button>
                            <a href="{{ route('admin.users.add', ['name' => 'Patient']) }}"
                                class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-md flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                Add New Patient
                            </a>
                        </div>
                    </div>
                </div>

                <div id="patient_details_card" class=" mb-6 bg-white rounded-lg shadow border border-main-color hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-main-color flex items-center">
                            <span
                                class="flex items-center justify-center w-6 h-6 rounded-full bg-main-color text-white mr-2">2</span>
                            Patient Details
                        </h3>
                    </div>
                    <div id="patient_details_content" class="p-6"></div>
                </div>

                <div id="opd_form_card" class="hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-semibold text-main-color flex items-center">
                            <span
                                class="flex items-center justify-center w-6 h-6 rounded-full bg-main-color text-white mr-2">3</span>
                            OPD Registration Form
                        </h3>
                    </div>

                    <form id="add_opd_patient" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6" method="post"
                        action="{{ route('admin.opd.save') }}">
                        @csrf
                        <input type="hidden" name="patient" id="hidden_patient_id">

                        <div class="md:col-span-2">
                            <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Appointment Details</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date <span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" id="appointment_date" name="appointment_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                required>

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">OPD No <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="opd_no" id="opd_no"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span
                                    class="text-red-500">*</span></label>
                            <select name="doctor" id="doctor"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                required>
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }} {{ $doctor->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Vital Signs</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                            <div class="relative">
                                <input type="text" name="height"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="Enter height">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    cm</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <div class="relative">
                                <input type="text" name="weight"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="Enter weight">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    kg</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                            <input type="text" name="blood_pressure"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="e.g. 120/80">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                            <div class="relative">
                                <input type="text" name="temperature"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="e.g. 98.6">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    °F</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pulse (bpm)</label>
                            <div class="relative">
                                <input type="text" name="pulse"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="e.g. 72">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    bpm</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RBS (mg/dL)</label>
                            <div class="relative">
                                <input type="text" name="rbs"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="e.g. 110">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    mg/dL</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SpO2 (%)</label>
                            <div class="relative">
                                <input type="text" name="SpO2"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="e.g. 98" maxlength="3">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    %</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate (/min)</label>
                            <div class="relative">
                                <input type="text" name="respiratory_rate"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    placeholder="e.g. 18">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    /min</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Casuality</label>
                            <select name="casuality"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm">
                                <option value="">Select...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Examination Findings</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">General Appearance</label>
                            <input type="text" name="general_appearance"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter general appearance">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CVS</label>
                            <input type="text" name="cvs"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter CVS findings">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RS</label>
                            <input type="text" name="rs"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter RS findings">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">P/A</label>
                            <input type="text" name="pa"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter P/A findings">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNS</label>
                            <input type="text" name="cns"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter CNS findings">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ext.</label>
                            <input type="text" name="other_systems"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter other systems findings">
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Billing Information</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Standard Charge <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    ₹</div>
                                <input type="text" name="standard_charge" id="standard_charge"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax (%)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="tax" id="tax"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    value="0">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    %</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="discount" id="discount"
                                    class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    value="0">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    %</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Final Amount (₹)</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                    ₹</div>
                                <input type="text" name="final_amount" id="final_amount"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                    readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Mode <span
                                    class="text-red-500">*</span></label>
                            <select name="payment_mode"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                required>
                                <option value="">Select Payment Mode</option>
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="UPI">UPI</option>
                                <option value="Net Banking">Net Banking</option>
                                <option value="Credit">Credit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference</label>
                            <input type="text" name="reference"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter reference">
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-md font-medium text-gray-700 mb-3 border-b pb-2">Medical Information</h4>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Symptoms </label>
                            <textarea name="symptoms" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter symptoms"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color shadow-sm"
                                placeholder="Enter notes"></textarea>
                        </div>

                        <div class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Is Old Patient</label>
                            <div class="flex space-x-4 mt-2">
                                <input type="hidden" name="is_old_patient" id="is_old_patient" value="0">
                                <button type="button"
                                    class="status-success-btn px-4 py-2 rounded-md border border-green-500 bg-green-50 text-green-700 hover:bg-green-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 shadow-sm">
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
                                    class="status-danger-btn px-4 py-2 rounded-md border border-red-500 bg-red-50 text-red-700 hover:bg-red-100 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 shadow-sm">
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

                        <div class="md:col-span-2 flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-4">
                            <button type="button"
                                class="cancel-btn px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" name="action" value="save"
                                class="px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200 shadow-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Save
                            </button>
                            <button type="submit" name="action" value="save_print"
                                class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 shadow-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Save & Print
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for patient search
            $('#patient_id').select2({
                placeholder: 'Search by Name or UHID',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('admin.opd.patient.search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            // Hide cards initially
            $('#patient_details_card').hide();
            $('#opd_form_card').hide();

            // On Search button click
            $('#search_patient_btn').on('click', function(e) {
                e.preventDefault();
                let patientId = $('#patient_id').val();
                if (!patientId) {
                    alert('Please select a patient!');
                    return;
                }

                // Fetch patient details via AJAX
                $.get("{{ url('admin/opd-patient/patient-details') }}/" + patientId, function(data) {
                    $('#patient_details_card').show();
                    $('#opd_form_card').show();

                    // Show patient details
                    $('#patient_details_content').html(`
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><span class="font-semibold">Name:</span> ${data.name ?? '-'}</div>
                            <div><span class="font-semibold">Email:</span> ${data.email ?? '-'}</div>
                            <div><span class="font-semibold">Gender:</span> ${data.gender ?? '-'}</div>
                            <div><span class="font-semibold">Age:</span> ${data.age ?? '-'}</div>
                            <div><span class="font-semibold">Phone:</span> ${data.phone ?? '-'}</div>
                            <div><span class="font-semibold">Alternate Phone:</span> ${data.alternate_phone ?? '-'}</div>
                            <div><span class="font-semibold">Guardian Name:</span> ${data.guardian_name ?? '-'}</div>
                            <div><span class="font-semibold">Blood Group:</span> ${data.blood_group ?? '-'}</div>
                            <div><span class="font-semibold">Marital Status:</span> ${data.marital_status ?? '-'}</div>
                            <div><span class="font-semibold">Address:</span> ${data.address ?? '-'}</div>
                            <div><span class="font-semibold">Alternate Address:</span> ${data.alternate_address ?? '-'}</div>
                            <div><span class="font-semibold">Remarks:</span> ${data.remarks ?? '-'}</div>
                            <div><span class="font-semibold">Allergies:</span> ${data.allergies ?? '-'}</div>
                        </div>
                    `);

                    // Set patient_id in hidden field in OPD form
                    $('#hidden_patient_id').val(patientId);

                    // Fetch new OPD No
                    $.get('{{ url('admin/opd-patient/generate-opd-no') }}', function(data) {
                        $('#opd_no').val(data.opd_no);
                    });
                });
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

            // Cancel button behavior
            $('.cancel-btn').on('click', function() {
                window.location.href = "{{ route('admin.opd.index') }}";
            });

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


            // Auto-set current datetime for appointment date
            let now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('appointment_date').value = now.toISOString().slice(0, 16);



        });
    </script>
@endpush