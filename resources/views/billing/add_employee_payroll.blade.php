@extends('layouts.admin')

@section('title', 'New Payroll')

@section('content')

<body class="bg-gray-100 p-8">

    <div class="max-w-screen-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">New Employee Payroll</h2>
            <a href="{{ url()->previous() }}">
            <button class="bg-pink-200 text-pink-600 px-4 py-2 rounded-md">Back</button>
            </a>
        </div>
        <div class="msg-container"></div>
            <form class="p-6 bg-white shadow-md rounded-lg form-class" id="add_employee_payroll" method="post" action="{{route('admin.billing.add_employee_payroll_save')}}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="srNo" class="block text-sm font-medium text-gray-700">Sr no: *</label>
                    <input type="text" id="srNo" name="sr_no" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div>
                    <label for="payrollId" class="block text-sm font-medium text-gray-700">Payroll ID: *</label>
                    <input type="text" id="payrollId" name="payroll_id" class="mt-1 p-2 w-full border rounded-md">
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role: *</label>
                    <input type="text" id="role" name="role" class="mt-1 p-2 w-full border rounded-md">
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
                        <option selected disabled>Select Employee</option>
                        @foreach($emp_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
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
                        <option selected disabled>Select Month</option>
                        @foreach($month_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
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
                        <option selected disabled>Select Year</option>
                        @foreach($year_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
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
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="basicSalary" class="block text-sm font-medium text-gray-700">Basic salary: *</label>
                    <input type="text" id="basicSalary" name="basic_salary" class="mt-1 p-2 w-full border rounded-md">
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
                        <option selected disabled>Select Allowance</option>
                        @foreach($allowance_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
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
                        <option selected disabled>Select Deduction</option>
                        @foreach($deduction_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label for="netSalary" class="block text-sm font-medium text-gray-700">Net Salary: *</label>
                    <input type="text" id="netSalary" name="net_salary" class="mt-1 p-2 w-full border rounded-md">
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
<!-- <script src="{{asset('assets/js/bill.js')}}"></script> -->
 <script>
let valid_form=['add_employee_payroll','edit_employee_payroll'];
var formId=$('.form-class').prop('id');
function validateForm(){
    if (!valid_form.includes(formId)) {
        console.log('Form content not found!');
        return 0;
    }
}

validateForm();
// sr_no,payroll_id,role,employee_id,month,year,status,basic_salary,allowance,deduction,net_salary
var form = $("#" + formId);

form.validate({
rules: {
    sr_no:{
        required:true,
        number:true,
    },
    payroll_id:{
        required:true,
    },
    role:{
        required:true,
    },
    employee_id:{
        required:true,
    },
    month:{
        required:true,
    },
    year:{
        required:true,
    },
    status:{
        required:true,
    },
    basic_salary:{
        required:true,
        number:true,
    },
    allowance:{
        required:true,
    },
    deduction:{
        required:true,
    },
    net_salary:{
        required:true,
        number:true,
    },
},
messages: {
    sr_no: { required: "Please enter the serial number.",number:'please enter number only.' },
    payroll_id: { required: "Please enter the payroll ID." },
    role: { required: "Please enter the role." },
    employee_id: { required: "Please select the employee." },
    year: { required: "Please select the year." },
    month: { required: "Please select the month." },
    status: { required: "Please select the status." },
    basic_salary: { required: "Please enter the basic salary amount." ,number:'please enter number only.' },
    allowance: { required: "Please select the allowance." },
    deduction: { required: "Please select the deduction." },
    net_salary: { required: "Please enter the net salary amount." ,number:'please enter number only.' }
},
errorPlacement: function(error, element) {
    error.addClass('text-danger');
    error.insertAfter(element);
},
errorElement: 'label',
submitHandler: function(form) {
    $('.form-submit-btn').prop('disabled',true);
    let formData = new FormData(form);
    console.log('formData',formData);  
    $.ajax({
        url: form.action,
        method: 'POST', 
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('.form-submit-btn').prop('disabled',false);
            let content='';
            if(response.status==200){
                $(form).hide();
                let msg=response.msg;
                let redirect_location=response.redirect_to;
                content += `<span>${msg}</span>`;
                display_msg(content,'success');
                setTimeout(()=>{
                    window.location.href=redirect_location;
                }, 3000);
            }else if(response.status == 422){
                let errors=response.errors;
                for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errors[key].forEach(function(message) {
                                if (Object.keys(errors).length > 1) {
                                    content += `<p class="p-0 m-0 text-danger">${message}</p>`;
                                } else {
                                    content += `<span class="text-danger">${message}</span>`;
                                }
                            });
                        }
                    }
                    display_msg(content);
            }
            else if(response.status==500){
                display_msg(response.errors);
            }
            else{
                display_msg('Something Went Wrong !');
            }
        },
        error: function(xhr, status, error) {
            $('.form-submit-btn').prop('disabled',false);
            console.error("Form submission failed!", error);
            alert("There was an error submitting the form. Please try again.");
        }
    });
    return false; // Prevent default form submission
}
});

function display_msg(data,msg_type='danger'){
    let content='';
    if(msg_type=='success'){
        content=`
            <div id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-t-4 border-green-300 bg-green-50 dark:text-green-400 dark:bg-gray-800 dark:border-green-800" role="alert">
        <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div class="ms-3 text-sm font-medium"><strong>Success: </strong>${data}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-3" aria-label="Close">
        <span class="sr-only">Dismiss</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        </button>
    </div>
    `;
    }else{

        content=`
        <div id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div class="ms-3 text-sm font-medium"><strong>Error: </strong>${data}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        </button>
        </div>
        `;
    }
    $('.msg-container').html(content);
}

//on cancle
$('.cancle-btn').on('click',function(){
    form.trigger("reset");
});

 </script>
@endpush 


