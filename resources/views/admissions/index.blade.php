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

                            </div>
                        </div>

                        <a href="{{route('admin.admissions.create')}}">
                            <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center">
                                + New Admissions
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
                                        <th class="p-2">Admission ID</th>
                                        <th class="p-2">Patient</th>
                                        <th class="p-2">Doctor</th>
                                        <th class="p-2">Admission Date</th>
                                        <th class="p-2">Discharge Date</th>
                                        <th class="p-2">Package</th>
                                        <th class="p-2">Insurance</th>
                                        <th class="p-2">Policy No</th>
                                        <th class="p-2">Status</th>
                                        <th class="p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admissions as $admission)
                                    <tr class="border-b">
                                        <td class="p-2">{{$admission->admission_no}}</td>
                                        <td class="p-2">{{$admission->patient?$admission->patient->name:""}}<br><span class="text-gray-500 text-sm">{{$admission->patient?$admission->patient->email:""}}</span></td>
                                        <td class="p-2">{{$admission->doctor?$admission->doctor->name:""}}<br><span class="text-gray-500 text-sm">{{$admission->doctor?$admission->doctor->email:""}}</span></td>
                                        <td class="p-2">{{$admission->admission_date?$admission->admission_date:"N/A"}}</td>
                                        <td class="p-2">{{$admission->discharge_date?$admission->discharge_date:'N/A'}}</td>
                                        <td class="p-2">{{$admission->package?$admission->package:"N/A"}}</td>
                                        <td class="p-2">{{$admission->insurance?$admission->insurance:"N/A"}}</td>
                                        <td class="p-2">{{$admission->policy_no?$admission->pinsurance:"N/A"}}</td>
                                        <td class="p-2">
                                            <form action="{{ route('admin.admissions.toggleStatus', $admission->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1 rounded 
            {{ $admission->status == 'active' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                                                    {{ ucfirst($admission->status) }}
                                                </button>
                                            </form>
                                        </td>

                                        <td class="p-2">

                                            <div class="flex items-center space-x-2">


                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.admissions.delete', $admission->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="toggleadmin(true,this,'delete')"
                                                        class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>



                                        </td>
                                        <td class="p-2 text-center"><a href="{{route('admin.admissions.edit',$admission->id)}}"> <i class="fa-solid fa-edit"></i></a></td>
                                        </td>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>


                        </div>
                    </body>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection