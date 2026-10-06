@extends('layouts.admin')
@section('title', 'New IPD Patient')
@section('content')
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold mb-4">Edit Patient Admission</h2>
            <a href="{{ url()->previous() }}">
            <button class="text-main-color customborder px-4 py-2 rounded-lg hover:bg-main-color">
                &larr; Back
            </button>
            </a> 
        </div>
        <!-- Admission Fields -->
        <!-- Removed duplicated and misplaced fields. All fields are now inside the form below. -->
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-4xl">
            <div class="msg-container"></div>
            <form id="add_ipd_patient" class="form-class grid grid-cols-2 gap-4" method="post" action="{{route('admin.admissions.update', $admission->id)}}">
    @csrf 
    @method('PUT')
    <!-- Patient -->
    <div>
        <label class="block font-medium">Patient: <span class="text-red-500">*</span></label>
        <select name="patient_id" id="patient" class="w-full p-2 border rounded-lg" required="required">
            <option value="">Select Patient Name...</option>
            @foreach($patients as $patient)
            <option value="{{$patient->user->id}}" {{ $admission->patient_id == $patient->user->id ? 'selected' : '' }}>
                {{$patient->user->name}} <span class="text-xs">({{$patient->user->email}})</span>
            </option>
            @endforeach  
        </select>
    </div>
    <!-- Package -->
    <div id="package-container">
        <label class="block font-medium">Package: <span class="text-red-500">*</span></label>
        <select name="package_name" class="w-full border p-2 rounded" id="package">
            <option value="">Select Package...</option>
            @foreach($packages as $package)
            <option value="{{$package}}" {{ $admission->package_name == $package ? 'selected' : '' }}>{{$package}}</option>
            @endforeach
        </select>
    </div>
    <!-- Height -->
    <!-- <div>
        <label class="block font-medium">Height: <span class="text-red-500"></span></label>
        <input type="text" name="height" class="w-full border p-2" placeholder="Enter here..." value="{{ old('height', $admission->height) }}">
    </div> -->
    <!-- Admission Date -->
    <div>
        <label class="block font-medium">Discharge Date: <span class="text-red-500"></span></label>
        <input type="datetime-local" name="discharge_date" class="w-full border p-2 rounded" value="{{ old('discharge_date', \Carbon\Carbon::parse($admission->discharge_date)->format('Y-m-d\TH:i')) }}">
    </div>
    <!-- Admission Date -->
    <div>
        <label class="block font-medium">Admission Date: <span class="text-red-500">*</span></label>
        <input type="datetime-local" name="admission_date" class="w-full border p-2 rounded" value="{{ old('admission_date', \Carbon\Carbon::parse($admission->admission_date)->format('Y-m-d\TH:i')) }}">
    </div>
    <!-- Blood Pressure -->
    <!-- <div>
        <label class="block font-medium">Blood Pressure:</label>
        <input type="text" name="blood_pressure" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('blood_pressure', $admission->blood_pressure) }}">
    </div> -->
    <!-- Doctors -->
    <div>
        <label class="block font-medium">Doctor: <span class="text-red-500">*</span></label>
        <select name="doctor_id" id="doctor" class="w-full p-2 border rounded-lg" required="required">
            <option value="">Select Doctor Name...</option>
            @foreach($doctors as $doctor)
            <option value="{{$doctor->id}}" {{ $admission->doctor_id == $doctor->id ? 'selected' : '' }}>
                {{$doctor->name}} <span class="text-xs">({{$doctor->email}})</span>
            </option>
            @endforeach  
        </select>
    </div>
    <!-- Bed Type -->
    <div>
        <label class="block font-medium">Bed Type: <span class="text-red-500">*</span></label>
        <select name="bed_type" id="bedType" class="w-full p-2 border rounded-lg" >
            <option value="">Select Bed Type...</option>
            @foreach($bedTypes as $bedType)
            <option value="{{$bedType->id}}" {{ $admission->bed_type == $bedType->id ? 'selected' : '' }}>{{$bedType->name}}</option>
            @endforeach  
        </select>
    </div>
    <!-- Bed -->
    <div>
        <label class="block font-medium">Bed: <span class="text-red-500">*</span></label>
        <select name="bed" class="w-full p-2 border rounded-lg" id="bed">
            <option value="">Select Bed...</option>
            @foreach($beds as $bed)
            <option value="{{$bed->id}}" {{ $admission->bed_no == $bed->id ? 'selected' : '' }}>{{$bed->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- Insurance -->
    <div class="col-span-2">
        <label class="block font-medium">Insurance: <span class="text-red-500"></span></label>
        <select name="insurance" class="w-full p-2 border rounded-lg" id="insurance">
            <option value="">Select Insurance...</option>
        </select>
    </div>
    <!-- Policy -->
    <div class="col-span-2">
        <label class="block font-medium">Policy Number:</label>
        <input type="text" name="policy_no" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('policy_no', $admission->policy_no) }}">
    </div>
    <div>
        <label class="block font-medium">Agent Name:</label>
        <input type="text" name="agent" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('agent', $admission->agent) }}">
    </div>
    <div>
        <label class="block font-medium">Guardian Name:</label>
        <input type="text" name="guardian_name" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('guardian_name', $admission->guardian_name) }}">
    </div>
    <div>
        <label class="block font-medium">Guardian Relation:</label>
        <input type="text" name="guardian_relationship" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('guardian_relationship', $admission->guardian_relationship) }}">
    </div>
    <div>
        <label class="block font-medium">Guardian Contact:</label>
        <input type="text" name="guardian_phone" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('guardian_phone', $admission->guardian_phone) }}">
    </div>
    <div>
        <label class="block font-medium">Guardian Address:</label>
        <input type="text" name="guardian_address" class="w-full border p-2 rounded" placeholder="Enter here..." value="{{ old('guardian_address', $admission->guardian_address) }}">
    </div>
    <!-- Status -->
    <div>
        <label class="block font-medium">Status: <span class="text-red-500">*</span></label>
        <div class="flex space-x-2 mt-1">
            <input type="hidden" name="status" value="{{ old('status', $admission->status) }}" id="status">
            <button type="button" class="status-success-btn px-2 py-1 border rounded bg-green-500 text-white">✔</button>
            <button type="button" class="status-danger-btn px-2 py-1 border rounded bg-red-500 text-white">✖</button>
        </div>
    </div>
    <!-- Buttons -->
    <div class="col-span-2 flex justify-end space-x-4">
        <button type="submit" class="form-submit-btn bg-purple-600 border  text-white px-4 py-2 rounded">Update</button>
        <button type="button" class="cancle-btn border px-4 py-2 rounded">Cancel</button>
    </div>
</form>
        </div>
    </div>
</body>
@endsection

@push('js')
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>


<script>
    $(document).ready(function() {
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
                            $.each(beds, function(index, bed) {
                                bedSelect.append($('<option>', {
                                    value: bed.id,
                                    text: bed.name
                                }));
                            });
                        }
                    }
                });
            }
        });
    });
</script>



@endpush