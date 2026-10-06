@extends('layouts.admin')

@section('title', 'Doctors Schedule')

@section('content')

<body class="bg-gray-200 p-2">
    <div class=" bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between mb-4">
        <a href="{{ route('admin.schedule.doctors_schedule') }}" >
            <button class="bg-pink-500 text-white px-4 py-2 rounded-lg">New Schedule</button>
        </a>
            <a href="{{ url()->previous() }}">
                <button class="bg-pink-500 text-white px-4 py-2 rounded-lg">← Back</button>
            </a>
        </div>
        <div class="msg-container"></div>
        <form class="form-class" id="add_schedule" method="post" action="{{route('admin.schedule.doctor_schedule_save')}}">
            @csrf
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-medium">Doctors:</label>
                <select name="doctor" id="doctor" data-get-doctor-schedule="{{route('admin.schedule.doctor_schedule_get',['id'=>'0'])}}" value="{{old('doctor')}}"  class="w-full p-2 border rounded-lg" required="required">
                    <option value="">Select Doctors Name...</option>
                    @foreach($doctors as $doctor)
                    <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                    @endforeach  
                </select>
            </div>
            <div>
                <label class="block font-medium">Per Patient Time:</label>
                <input type="text" name="per_patient_time" id="per_patient_time" value="{{old('per_patient_time')}}" class="w-full p-2 border rounded-lg" placeholder="Per Patient Time" required="required">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">AVAILABLE ON</th>
                        <th class="border p-2">AVAILABLE TO</th>
                        <th class="border p-2">AVAILABLE FROM</th>
                        <th class="border p-2">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                
                    @foreach(["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"] as $day)
                    <tr>
                        <td class="border p-2 text-center text-capitalize">{{$day}}</td>
                        <td class="border p-2"><input type="time" name="{{$day}}_available_from" id="{{$day}}_available_from" value="{{old($day.'_available_from')}}" class="w-full p-2 border rounded-lg"></td>
                        <td class="border p-2"><input type="time" name="{{$day}}_available_to" id="{{$day}}_available_to" value="{{old($day.'_available_to')}}" class="w-full p-2 border rounded-lg"></td>
                        <td class="border p-2 text-center">
                            <button type="button" class="bg-white text-black-300 border-2 border-pink-500 p-2 rounded-lg hover:bg-pink-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                    <path fill="currentColor" d="M238 136v64a14 14 0 0 1-14 14H32a14 14 0 0 1-14-14v-64a14 14 0 0 1 14-14h48a6 6 0 0 1 0 12H32a2 2 0 0 0-2 2v64a2 2 0 0 0 2 2h192a2 2 0 0 0 2-2v-64a2 2 0 0 0-2-2h-48a6 6 0 0 1 0-12h48a14 14 0 0 1 14 14M84.24 76.24L122 38.49V128a6 6 0 0 0 12 0V38.49l37.76 37.75a6 6 0 0 0 8.48-8.48l-48-48a6 6 0 0 0-8.48 0l-48 48a6 6 0 0 0 8.48 8.48M198 168a10 10 0 1 0-10 10a10 10 0 0 0 10-10" />
                                </svg>
                            </button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="form-submit-btn bg-pink-500 text-white px-6 py-2 rounded-lg">Save</button>
        </div>
    </form>    
    </div>
</body>
@endsection

@push('js')
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/schedule.js')}}"></script>
@endpush