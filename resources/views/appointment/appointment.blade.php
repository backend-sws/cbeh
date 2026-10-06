@extends('layouts.admin')
@section('title', 'Appointments')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header p-1">
                    <div class="flex justify-between items-center mb-1">

                        <div class="flex justify-between items-center ">
                            <div class="flex items-center space-x-2">
                                <button class="p-2 customborder rounded-lg ph ph-alarm bg-pink-200"><i></i></button>
                                <button class="p-2 customborder rounded-lg ph ph-funnel bg-pink-200"><i></i></button>
                                <input type="text" class="border p-2 rounded-lg" value="06/01/2025-06/01/2025" readonly>
                            </div>
                        </div>

                        <a href="{{route('admin.appointment.add')}}">
                        <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center">
                            + New Appointments
                        </button>
                        </a>
                    </div>
                </div>

                <div class="card-body p-6 bg-white">
                    <body class="p-6 bg-gray-100">
                        <div class="bg-white p-6 rounded-lg shadow-md">


                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="p-2">PATIENT</th>
                                        <th class="p-2">DOCTORS</th>
                                        <th class="p-2">DOCTORS DEPARTMENT</th>
                                        <th class="p-2">DATE</th>
                                        <th class="p-2">STATUS</th>
                                        <th class="p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($appointments as $appointment)
                                    <tr class="border-b">
                                        <td class="p-2">{{$appointment->patient->name}}<br><span class="text-gray-500 text-sm">{{$appointment->patient->email}}</span></td>
                                        <td class="p-2">{{$appointment->doctor->name}}<br><span class="text-gray-500 text-sm">{{$appointment->doctor->email}}</span></td>
                                        <!-- <td class="p-2">Rahul Kumar</td> -->
                                        <td class="p-2">{{$appointment->department->department_name}}</td>
                                        <td><span class="block text-pink-500 font-semibold">{{ \Carbon\Carbon::parse($appointment->date)->format('g:i A') }}</span><span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->date)->format('jS M Y') }}</span></td>
                                        <td class="p-2">
                                            @if($appointment->status)
                                            <span class="bg-green-200 text-green-700 px-2 py-1 rounded">Confirm</span>
                                            @else 
                                            <span class="bg-yellow-200 text-yellow-700 px-2 py-1 rounded">Pending</span>
                                            @endif 
                                        </td>
                                        <td class="p-2 text-center"><a href="{{route('admin.appointment.edit',$appointment->id)}}">Edit</a></td>
                                    </tr>
                                @endforeach     
                      
                                </tbody>
                            </table>

                            @include('partials.pagination',['users' => $appointments])
                        </div>
                    </body>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
