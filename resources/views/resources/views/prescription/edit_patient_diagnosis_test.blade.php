@extends('layouts.admin')

@section('title', 'Edit Patient Diagnosis Test')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800">Edit Patient Diagnosis Test</h1>
        <div class="flex space-x-2">
            <a href="{{ url()->previous() }}">
                <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center">
                    Back
                </button>
            </a>


        </div>
    </div>

    <body class="bg-gray-100 p-8">

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <!-- msg -->
            <x-alert type="success" :message="session('success')" />
            <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            <div class="msg-container"></div>
            <form class="form-class" id="diagnosis_test_form" method="post" action="{{route('admin.diagnosis.diagnosis_test.update',$id)}}">
                @csrf 
                <div  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- First Row -->
                <div>
                    <label class="block text-sm font-medium mb-1">Patient: <span class="text-red-500">*</span></label>
                    <select name="patient" required class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option disabled>Select patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @if($diagnosisTest->patient?->id == $patient->id) selected @endif >{{ $patient->name }} ({{ $patient->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Doctor: <span class="text-red-500">*</span></label>
                    <select name="doctor" required class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                       <option disabled >Select doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @if($diagnosisTest->doctor?->id == $doctor->id) selected @endif >{{ $doctor->name }} ({{ $doctor->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Diagnosis Category: <span class="text-red-500">*</span></label>
                    <select name="diagnosis_category" required class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option disabled >Select diagnosis category</option>
                        @foreach($diagnosis_categories as $category)
                            <option value="{{ $category->id }}" @if($diagnosisTest->diagnosisCategory?->id == $category->id) selected @endif >{{ $category->diagnosis_category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Report Number: <span class="text-red-500">*</span></label>
                    <input name="report_number" value="{{$diagnosisTest->report_number}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Second Row -->
                <div>
                    <label class="block text-sm font-medium mb-1">Age: <span class="text-red-500">*</span></label>
                    <input name="age" value="{{$diagnosisTest->age}}" required type="number" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Height: <span class="text-red-500">*</span></label>
                    <input name="height" value="{{$diagnosisTest->height}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Weight: <span class="text-red-500">*</span></label>
                    <input name="weight" value="{{$diagnosisTest->weight}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Average Glucose: <span class="text-red-500">*</span></label>
                    <input name="average_glucose" value="{{$diagnosisTest->average_glucose}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Third Row -->
                <div>
                    <label class="block text-sm font-medium mb-1">Fasting Blood Sugar: <span class="text-red-500">*</span></label>
                    <input name="fasting_blood_sugar" value="{{$diagnosisTest->fasting_blood_sugar}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Urine Sugar: <span class="text-red-500">*</span></label>
                    <input name="urine_sugar" value="{{$diagnosisTest->urine_sugar}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Blood Pressure: <span class="text-red-500">*</span></label>
                    <input name="blood_pressure" value="{{$diagnosisTest->blood_pressure}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Diabetes: <span class="text-red-500">*</span></label>
                    <input name="diabetes" value="{{$diagnosisTest->diabetes}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Fourth Row -->
                <div>
                    <label class="block text-sm font-medium mb-1">Cholesterol: <span class="text-red-500">*</span></label>
                    <input name="cholesterol" value="{{$diagnosisTest->cholesterol}}" required type="text" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
            <div>
                <!-- Add other diagnosis property -->
                <div class="mt-10">
                    <p class="text-sm font-semibold mb-3">Add other diagnosis property</p>
                    <div class="bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                        <div class="flex-1 grid grid-cols-2 gap-4">
                            <input type="text" placeholder="#DIAGNOSIS PROPERTY NAME" class="border rounded-md px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <input type="text" placeholder="#DIAGNOSIS PROPERTY VALUE" class="border rounded-md px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <button type="button" class="bg-purple-700 hover:bg-purple-800 text-white text-sm px-4 py-2 rounded-md ml-4">ADD</button>
                    </div>
                </div>

                <!-- Save and Cancel Buttons -->
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white text-sm px-8 py-2 rounded-md form-submit-btn" >Save</button>
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm px-8 py-2 rounded-md cancle-btn">Cancel</button>
                </div>
            </div>
        </form>
        </div>

    </body>
   
</body>

@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
let valid_form = ['diagnosis_test_form'];
  var formId = $('.form-class').prop('id');

function validateForm() {
        if (!valid_form.includes(formId)) {
        console.log('Form content not found!');
        return 0;
        }
}   
validateForm();
    var form = $("#" + formId);
    form.validate({
        rules: {
        patient: {
          required: true
        },
        doctor: {
          required: true
        },
        diagnosis_category: {
          required: true
        },
        report_number: {
          required: true
        },
        age: {
          required: true,
          number: true,
          min: 0
        },
        height: {
          required: true
        },
        weight: {
          required: true
        },
        average_glucose: {
          required: true
        },
        fasting_blood_sugar: {
          required: true
        },
        urine_sugar: {
          required: true
        },
        blood_pressure: {
          required: true
        },
        diabetes: {
          required: true
        },
        cholesterol: {
          required: true
        }
      },
      messages: {
        patient: {
          required: "Please select a patient"
        },
        doctor: {
          required: "Please select a doctor"
        },
        diagnosis_category: {
          required: "Please select a diagnosis category"
        },
        report_number: {
          required: "Please enter a report number"
        },
        age: {
          required: "Please enter the age",
          number: "Age must be a number",
          min: "Age cannot be negative"
        },
        height: {
          required: "Please enter the height"
        },
        weight: {
          required: "Please enter the weight"
        },
        average_glucose: {
          required: "Please enter average glucose"
        },
        fasting_blood_sugar: {
          required: "Please enter fasting blood sugar"
        },
        urine_sugar: {
          required: "Please enter urine sugar"
        },
        blood_pressure: {
          required: "Please enter blood pressure"
        },
        diabetes: {
          required: "Please enter diabetes info"
        },
        cholesterol: {
          required: "Please enter cholesterol info"
        }
      },
      errorPlacement: function (error, element) {
        error.addClass('text-danger text-sm');
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
    // click event on close button added
    const closeButtons = document.querySelectorAll('[data-dismiss-target]');
    closeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-dismiss-target');
            const alertElement = document.querySelector(targetSelector);
            if (alertElement) {
                alertElement.classList.add('hidden');
            }
        });
    });
}

    //on cancle
    $('.cancle-btn').on('click',function(){
        form.trigger("reset");
    });

</script>
@endpush
