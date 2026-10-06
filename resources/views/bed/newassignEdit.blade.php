@extends('layouts.admin')

@section('title', 'Beds')

@section('content')
<!-- Include jQuery and Select2 (in your <head> or before </body>) -->
<style>
    .select2-container .select2-selection--single {
        height: 40px !important;
        /* Tailwind input height (p-2) = 2 * 8px + ~16px text = ~40px */
        padding: 6px 12px;
        border: 1px solid #d1d5db;
        /* Tailwind's gray-300 */
        border-radius: 0.375rem;
        /* Tailwind's rounded-md */
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 0;
        right: 8px;
    }

</style>
<!-- Form -->


<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Edit Bed Assign</h1>
        <div class="flex space-x-2">
           
            <a class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center">
                Back
            </a>
           
        </div>
    </div>
   <form action="{{ route('admin.bed.assign.update', $assign->id) }}" method="POST" id="assign" onsubmit="formSubmit2(event, this, false, 'assign')">
    @csrf
<input type="hidden" name="patient_id" id="patient_id">
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-md space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Case (Select2) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Case: <span class="text-red-500">*</span>
                </label>
                <select id="caseSelect" name="case" class="select2 w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600">

                @foreach ($cases as $case)
                    
                    <option value="{{ $case->id }}" {{ $assign->case == $case->id ? 'selected' : '' }}>{{ $case->case }}</option>
                @endforeach
                </select>
            </div>

            <!-- IPD Patient -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    IPD Patient: <span class="text-red-500">*</span>
                </label>
                <input type="text" id="ipdPatient" name="ipd_no" placeholder="Enter here..." class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600" readonly value="{{ $assign->ipd_no }}" />
            </div>

            <!-- Bed (Select2) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Bed: <span class="text-red-500">*</span>
                </label>
                <select id="bedSelect" name="bed_id" class="select2 w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600">
                    <option value="" disabled selected>Select Bed</option>
                    @foreach ($beds as $bed)
                        <option value="{{ $bed->id }}" {{ $assign->bed_id == $bed->id ? 'selected' : '' }}>{{ $bed->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assign Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Assign Date: <span class="text-red-500">*</span>
                </label>
                <input type="date" name="assigned_date" value="{{ $assign->assigned_date }}" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Discharge Date: <span class="text-red-500">*</span>
                </label>
                <input type="date" name="discharge_date" value="{{ $assign->discharged_date }}" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status: <span class="text-red-500">*</span>
                </label>
                <input type="radio" name="status" value="assigned" {{ $assign->status == 'assigned' ? 'checked' : '' }} class="mr-2" /> Assigned
                <input type="radio" name="status" value="discharged" {{ $assign->status == 'discharged' ? 'checked' : '' }} class="mr-2" /> Discharged
            </div>

        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Description: <span class="text-red-500">*</span>
            </label>
            <textarea rows="5" name="description" placeholder="Description" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-600">{{ $assign->description }}</textarea>
        </div>

        <!-- Status and Buttons -->
        <div class="flex items-center justify-between mt-4">
            

            <div class="flex space-x-3">
                <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white font-medium px-6 py-2 rounded-md shadow">Save</button>
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-6 py-2 rounded-md shadow">Cancel</button>
            </div>
        </div>
    </div>
</form>

    </div>


</div>
<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        $('#caseSelect').select2({
            placeholder: "Select Case"
            , width: 'resolve'
        });
        $('#bedSelect').select2({
            placeholder: "Select Bed"
            , width: 'resolve'
        });
    });

</script>
<script>
    caseSelect = document.getElementById('caseSelect');
    ipdPatient = document.getElementById('ipdPatient');
    caseSelect.addEventListener('change', function() {
        
        // Assuming the case object has a patient property
        var selectedCase = @json($cases);
        var selectedCaseId = this.value;
        var selectedPatient = selectedCase.find(c => c.id == selectedCaseId);

        if (selectedPatient) {
              console.log(selectedPatient);
            ipdPatient.value = selectedPatient.ipd_no; // Assuming 'patient' is the field you want to display
            document.getElementById('patient_id').value = selectedPatient.patient_id; // Set the hidden patient_id field
        } else {
            ipdPatient.value = ''; // Clear if no case is selected
        }
    });
</script>

@endsection
