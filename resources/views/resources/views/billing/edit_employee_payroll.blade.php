@extends('layouts.admin')

@section('title', 'Edit Payroll')

@section('content')

<body class="bg-gray-100 p-8">

    <div class="max-w-screen-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Edit Employee Payroll</h2>
            <a href="{{ url()->previous() }}">
            <button class="bg-pink-200 text-pink-600 px-4 py-2 rounded-md">Back</button>
            </a>
        </div>
        <div class="msg-container"></div>
            <form class="p-6 bg-white shadow-md rounded-lg form-class" id="edit_employee_payroll" method="post" action="{{route('admin.billing.edit_employee_payroll_save',$id)}}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="srNo" class="block text-sm font-medium text-gray-700">Sr no: *</label>
                    <input type="text" id="srNo" name="sr_no" value="{{$employee_payroll->sr_no}}" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div>
                    <label for="payrollId" class="block text-sm font-medium text-gray-700">Payroll ID: *</label>
                    <input type="text" id="payrollId" name="payroll_id" value="{{$employee_payroll->payroll_id}}" class="mt-1 p-2 w-full border rounded-md">
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role: *</label>
                    <input type="text" id="role" name="role" value="{{$employee_payroll->role}}" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div>
                    @php 
                    $emp_options=[
                        '1' => 'Employee 1',
                        '2' => 'Employee 2',
                        '3' => 'Employee 3',
                    ];
                    @endphp 
                    <label for="employee" class="block text-sm font-medium text-gray-700">Employee: *</label>
                    <select id="employee" name="employee_id" class="mt-1 p-2 w-full border rounded-md">
                        <option  disabled>Select Employee</option>
                        @foreach($emp_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->employee_id == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    @php 
                    $month_options=[
                        '1' => 'Jan',
                        '2' => 'Feb',
                        '3' => 'Mar',
                    ];
                    @endphp 
                    <label for="month" class="block text-sm font-medium text-gray-700">Month: *</label>
                    <select id="month" name="month" class="mt-1 p-2 w-full border rounded-md">
                        <option  disabled>Select Month</option>
                        @foreach($month_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->month == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    @php 
                    $year_options=[
                        '2025' => '2025',
                        '2024' => '2024',
                        '2023' => '2023',
                    ];
                    @endphp 
                    <label for="year" class="block text-sm font-medium text-gray-700">Year: *</label>
                    <select id="year" name="year" class="mt-1 p-2 w-full border rounded-md">
                        <option  disabled>Select Year</option>
                        @foreach($year_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->year == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    @php 
                    $status_options=[
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                    ];
                    @endphp
                    <label for="status" class="block text-sm font-medium text-gray-700">Status: *</label>
                    <select id="status" name="status" class="mt-1 p-2 w-full border rounded-md">
                        <option selected disabled>Select Status</option>
                        @foreach($status_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->status == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="basicSalary" class="block text-sm font-medium text-gray-700">Basic salary: *</label>
                    <input type="text" id="basicSalary" name="basic_salary" value="{{$employee_payroll->basic_salary}}" class="mt-1 p-2 w-full border rounded-md">
                </div>
                
                <div>@php 
                    $allowance_options=[
                        '1' => 'allowance 1',
                        '2' => 'allowance 2',
                        '3' => 'allowance 3',
                    ];
                    @endphp
                    <label for="allowances" class="block text-sm font-medium text-gray-700">Allowances: *</label>
                    <select id="allowances" name="allowance" class="mt-1 p-2 w-full border rounded-md">
                        <option  disabled>Select Allowance</option>
                        @foreach($allowance_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->allowance == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                @php 
                    $deduction_options=[
                        '1' => 'Deduction 1',
                        '2' => 'Deduction 2',
                        '3' => 'Deduction 3',
                    ];
                    @endphp
                    <label for="deduction" class="block text-sm font-medium text-gray-700">Deduction: *</label>
                    <select id="deduction" name="deduction" class="mt-1 p-2 w-full border rounded-md">
                        <option disabled>Select Deduction</option>
                        @foreach($deduction_options as $value =>$name)
                        <option value="{{$value}}" @if($employee_payroll->deduction == $value) selected @endif >{{$name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label for="netSalary" class="block text-sm font-medium text-gray-700">Net Salary: *</label>
                    <input type="text" id="netSalary" name="net_salary" value="{{$employee_payroll->net_salary}}" class="mt-1 p-2 w-full border rounded-md">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md mr-2 form-submit-btn">Save</button>
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md cancle-btn">Cancel</button>
            </div>
            </form>
    </div>

</body>

@endsection

@push('js')
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/employee-payroll.js')}}"></script>
 <script>

 </script>
@endpush 


