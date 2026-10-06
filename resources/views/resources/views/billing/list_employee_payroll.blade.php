@extends('layouts.admin')

@section('title', 'Billing')

@section('content')

<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Employee Payrolls</h2>
    <a href="{{route('admin.billing.add_employee_payroll')}}">
        <button class="bg-pink-300 customborder text-white px-4 py-2 rounded-lg">
            New Employee Payroll
        </button>
    </a>
 </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">SR NO</th>
                        <th class="p-3 text-left">PAYROLL ID</th>
                        <th class="p-3 text-left">EMPLOYEE</th>
                        <th class="p-3 text-left">MONTH</th>
                        <th class="p-3 text-left">YEAR</th>
                        <th class="p-3 text-left">NET SALARY</th>
                        <th class="p-3 text-left">STATUS</th>
                        <th class="p-3 text-left">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($employee_payrolls))
                    @foreach($employee_payrolls as $payroll)
                    <tr class="border-b">
                        <td class="p-3">{{$payroll->sr_no}}</td>
                        <td class="p-3"><span class="bg-pink-300 px-2 py-1 rounded text-white">{{$payroll->payroll_id}}</span></td>
                        <td class="p-3">Arvind Kumar<br><span class="text-gray-500 text-sm">arvind123@gmail.com</span></td>
                        <td class="p-3">{{$payroll->month}}</td>
                        <td class="p-3">{{$payroll->year}}</td>
                        <td class="p-3">ARS {{$payroll->net_salary}}</td>
                        <td class="p-3">
                            @if($payroll->status =='paid')
                            <span class="bg-green-200 text-green-700 px-2 py-1 rounded">Paid</span>
                            @else 
                            <span class="bg-red-200 text-red-700 px-2 py-1 rounded">Unpaid</span>
                            @endif 
                        </td>
                       <td class="px-4 py-2">
                    <a href="{{route('admin.billing.edit_employee_payroll',$payroll->id)}}">
                        <button class="fa fa-edit border p-1"></button>
                    </a>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
                    </tr>
                    @endforeach 
                    @endif 
                    <!-- Repeat similar rows for more entries -->
                </tbody>
            </table>
        </div>
        
          <div class="flex items-center justify-between mt-4 text-gray-600 text-sm">
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
        </div>
    </div>
    </div>
</body>



@endsection



