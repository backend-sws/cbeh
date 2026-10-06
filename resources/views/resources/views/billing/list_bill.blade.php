@extends('layouts.admin')

@section('content')

<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">BILL</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                <a href="{{route('admin.billing.add_bill')}}">
                 <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/admin/billing/newbill'">
                            New Bill
                </button>
                </a>
            </div>
        </div>

       <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">BILL ID</th>
                    <th class="py-2 px-4 text-left">PATIENT</th>
                    <th class="py-2 px-4 text-center">BILL STATUS</th>
                    <th class="py-2 px-4 text-center">BILL DATE</th>
                    <th class="py-2 px-4 text-right">AMOUNT</th>
                    <th class="py-2 px-4 text-center">ACTION</th>
                </tr>
            </thead>
            <tbody>

            @foreach($billings as $billing)
                <tr class="border-t">
                    <td class="py-2 px-4 text-sm font-medium">{{$billing->policy_no}}</td>
                    <td class="py-2 px-4 text-sm">
                        <p class="font-semibold">{{$billing->package_name}}</p>
                        <p class="text-gray-500 text-xs">{{$billing->patient_email}}</p>
                    </td>
                    <td class="py-2 px-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-200 rounded">Unpaid</span>
                    </td>
                    <td class="py-2 px-4 text-center text-sm ">{{$billing->bill_date}}</td>
                    <td class="py-2 px-4 text-right text-sm text-blue-600">RS 1000.00</td>
                    <td class="p-2 flex justify-center space-x-2">
                            <button class="text-gray-700 dark:text-gray-500 hover:text-blue-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="{{route('admin.billing.edit_bill',$billing->id)}}">
                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            </a>
                            <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    
                </tr>
            @endforeach
              
                <!-- Repeat similar rows as needed -->
            </tbody>
        </table>
    </div>

        <!-- Pagination -->

        @include('partials.pagination',['users' => $billings])
        {{--<div class="flex items-center justify-between mt-4 text-gray-600 text-sm">
            <div class="flex space-x-1">
                <button class="px-3 py-1 bg-pink-500 text-white rounded">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">4</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">5</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">6</button>
            </div>
            <div>
                <span>Show</span>
                <select class="ml-2 border border-gray-300 rounded p-1">
                    <option>16</option>
                    <option>32</option>
                    <option>64</option>
                </select>
            </div>
            <div>
                <span>Showing 1 to 10 of 3000</span>
            </div>
        </div>--}}
    </div>


@endsection
