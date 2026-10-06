@extends('layouts.admin')
@section('title', 'Schedule')
@section('content')
<div class="container mx-auto p-6 space-y-6">
    <a href="{{ route('admin.schedule.doctors_schedule') }}">
        <button
            class="text-pink-500 border-2 border-pink-500 px-6 py-3 rounded-lg shadow-md hover:bg-pink-600 hover:text-white transition-all">New
            Schedule</button>
    </a>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
        @foreach($doctors as $doctor)
        @if(isset($doctor->DoctorProfile))
        <div class="bg-white rounded-2xl shadow-lg p-6 flex w-full items-center space-x-6">
            <img src="/img/doctor.png" alt="Doctor Image" class="w-28 h-40 rounded-lg object-cover">
            <div class="flex-1 space-y-2">
                <div class="flex justify-between items-start">
                    <h2 class="text-2xl font-semibold text-gray-900">Dr. {{$doctor->name}}</h2>
                    <div class="flex items-center">
                        <span class="text-yellow-500 text-2xl">&#9733;</span>
                        <span class="text-gray-600 text-lg ml-2">{{$doctor->DoctorProfile->rating}}</span>
                    </div>
                </div>
                <p class="text-indigo-600 font-medium text-lg">{{$doctor->DoctorProfile->degree}}</p>
                <p class="text-purple-600 font-medium text-lg">{{$doctor->DoctorProfile->specialization}}</p>
                <p class="text-gray-600 text-md">{{$doctor->DoctorProfile->experience_years}} Year Experience</p>
                <p class="text-gray-600 text-md flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2c4.97 0 9 4.03 9 9 0 3.92-3.13 8.26-9 13-5.87-4.74-9-9.08-9-13 0-4.97 4.03-9 9-9z" />
                    </svg>
                    {{$doctor->DoctorProfile->hospital_name}}
                </p>
                <div class="flex justify-between items-center mt-4">
                    <p class="text-gray-700 font-medium">Consulting Fee</p>
                    <p class="text-xl font-semibold text-gray-900">&#8377; {{$doctor->DoctorProfile->consulting_fee}}
                    </p>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <div class="flex justify-between items-center mt-6">
        <select class="border p-3 rounded-lg shadow-sm">
            <option>400</option>
        </select>
        <p class="text-gray-500 text-lg">1/16 To 16 page</p>
        <div class="space-x-3">
            <button class="border px-4 py-2 rounded-lg shadow-md">&#8592;</button>
            <button class="border px-4 py-2 rounded-lg shadow-md">&#8594;</button>
        </div>
    </div>
</div>
@endsection