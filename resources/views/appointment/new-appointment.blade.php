@extends('layouts.admin')
@section('title', 'Add Appointment')
@section('content')
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto  rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold mb-4">New Appointment</h2>
            <a href="{{ url()->previous() }}">
            <button class="text-main-color customborder px-4 py-2 rounded-lg hover:bg-main-color">
                &larr; Back
            </button>
            </a>
        </div>
     <body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-4xl">
        <div class="msg-container"></div>
        <form id="add_appointment" class="form-class grid grid-cols-2 gap-4" method="post" action="{{route('admin.appointment.save')}}">
                @csrf 
            <!-- Patient -->
            <div>
                <label class="block font-medium">Patient: <span class="text-red-500">*</span></label>
                <select name="patient" id="patient"  class="w-full p-2 border rounded-lg" required="required">
                    <option value="">Select Patient Name...</option>
                    @foreach($patients as $patient)
                    <option value="{{$patient->id}}">{{$patient->name}} <span class="text-xs">({{$patient->email}})</span></option>
                    @endforeach  
                </select>
            </div>
            <!-- Doctor Department -->
            <div>
                <label class="block font-medium">Doctor Department: <span class="text-red-500">*</span></label>
                <select name="doctor_department" class="w-full border p-2 rounded" required="required">
                    <option value="">Select Department...</option>
                    @foreach($departments as $department)
                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                    @endforeach 
                </select>
            </div>
            <!-- Doctor -->
            <div>
                <label class="block font-medium">Doctor: <span class="text-red-500">*</span></label>
                <select name="doctor" id="doctor"  class="w-full p-2 border rounded-lg" required="required">
                    <option value="">Select Doctor Name...</option>
                    @foreach($doctors as $doctor)
                    <option value="{{$doctor->id}}">{{$doctor->name}} <span class="text-xs">({{$doctor->email}})</span></option>
                    @endforeach  
                </select>
            </div>
            <!-- Date -->
            <div>
                <label class="block font-medium">Date: <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="date" class="w-full border p-2 rounded">
            </div>
            <!-- Description -->
            <div class="col-span-2">
                <label class="block font-medium">Description:</label>
                <textarea name="description" class="w-full border p-2 rounded h-24" placeholder="Enter here..."></textarea>
            </div>
            <!-- Date of Birth -->
            <div>
                <label class="block font-medium">Date of Birth:</label>
                <input type="date" name="date_of_birth" class="w-full border p-2 rounded">
            </div>
            <!-- Status -->
            <div>
                <label class="block font-medium">Status: <span class="text-red-500">*</span></label>
                <div class="flex space-x-2 mt-1">
                    <input type="hidden" name="status" id="status" value="0">
                    <button type="button" class="status-success-btn px-2 py-1 border rounded bg-green-500 text-white">✔</button>
                    <button type="button" class="status-danger-btn px-2 py-1 border rounded bg-red-500 text-white">✖</button>
                </div>
            </div>
            <!-- Can You Extend -->
            <div class="col-span-2">
                <label class="block font-medium">Can You Extend: <span class="text-red-500">*</span></label>
                <input type="text" name="can_you_extend" class="w-full border p-2 rounded" placeholder="Enter here...">
            </div>
            <!-- Buttons -->
            <div class="col-span-2 flex justify-end space-x-4">
                <button type="submit" class="form-submit-btn bg-purple-600 text-white px-4 py-2 rounded">Upload</button>
                <button type="button" class="cancle-btn border px-4 py-2 rounded">Cancel</button>
            </div>
        </form>
    </div>
</body>
          
    </div>


</body>

@endsection

@push('js')
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/appointment.js')}}"></script>
@endpush 

