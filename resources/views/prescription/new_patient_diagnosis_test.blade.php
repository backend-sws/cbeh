@extends('layouts.admin')

@section('title', 'New Patient Diagnosis Test')

@section('content')
<div class="bg-gray-50 p-6 font-sans">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Patient Diagnosis Test</h1>
        <a href="{{ url()->previous() }}" class="flex items-center space-x-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Alerts -->
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
        <div class="msg-container mb-4"></div>

        <form class="form-class" id="diagnosis_test_form" method="post" action="{{ route('admin.diagnosis.diagnosis_test.save') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Patient Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Patient Information</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                        <select name="patient" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="" disabled selected>Select patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Age <span class="text-red-500">*</span></label>
                        <input name="age" required type="number" placeholder="Enter age" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Medical Information</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                        <select name="doctor" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="" disabled selected>Select doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis Category <span class="text-red-500">*</span></label>
                        <select name="diagnosis_category" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="" disabled selected>Select diagnosis category</option>
                            @foreach($diagnosis_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->diagnosis_category }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Report Number <span class="text-red-500">*</span></label>
                        <input name="report_number" required type="text" placeholder="Enter report number" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Vital Signs</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm) <span class="text-red-500">*</span></label>
                        <input name="height" required type="text" placeholder="Enter height" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg) <span class="text-red-500">*</span></label>
                        <input name="weight" required type="text" placeholder="Enter weight" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure <span class="text-red-500">*</span></label>
                        <input name="blood_pressure" required type="text" placeholder="Enter blood pressure" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Test Results -->
                <div class="space-y-4 md:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Test Results</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Average Glucose <span class="text-red-500">*</span></label>
                            <input name="average_glucose" required type="text" placeholder="Enter value" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fasting Blood Sugar <span class="text-red-500">*</span></label>
                            <input name="fasting_blood_sugar" required type="text" placeholder="Enter value" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urine Sugar <span class="text-red-500">*</span></label>
                            <input name="urine_sugar" required type="text" placeholder="Enter value" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diabetes <span class="text-red-500">*</span></label>
                            <input name="diabetes" required type="text" placeholder="Enter value" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cholesterol <span class="text-red-500">*</span></label>
                            <input name="cholesterol" required type="text" placeholder="Enter value" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Additional Properties -->
                <div class="space-y-4 md:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Additional Properties</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" placeholder="Property name" class="border border-gray-300 rounded-md px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div class="flex-1">
                                <input type="text" placeholder="Property value" class="border border-gray-300 rounded-md px-3 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-4 py-2 rounded-md transition-colors">
                                Add Property
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-4 border-t">
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors cancel-btn">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors form-submit-btn">
                    Save Diagnosis Test
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Form validation
    let valid_form = ['diagnosis_test_form'];
    var formId = $('.form-class').prop('id');

    if (valid_form.includes(formId)) {
        var form = $("#" + formId);
        form.validate({
            rules: {
                patient: { required: true },
                doctor: { required: true },
                diagnosis_category: { required: true },
                report_number: { required: true },
                age: { required: true, number: true, min: 0 },
                height: { required: true },
                weight: { required: true },
                average_glucose: { required: true },
                fasting_blood_sugar: { required: true },
                urine_sugar: { required: true },
                blood_pressure: { required: true },
                diabetes: { required: true },
                cholesterol: { required: true }
            },
            messages: {
                patient: "Please select a patient",
                doctor: "Please select a doctor",
                diagnosis_category: "Please select a diagnosis category",
                report_number: "Please enter a report number",
                age: {
                    required: "Please enter the age",
                    number: "Please enter a valid number",
                    min: "Age cannot be negative"
                },
                height: "Please enter the height",
                weight: "Please enter the weight",
                average_glucose: "Please enter average glucose",
                fasting_blood_sugar: "Please enter fasting blood sugar",
                urine_sugar: "Please enter urine sugar",
                blood_pressure: "Please enter blood pressure",
                diabetes: "Please enter diabetes info",
                cholesterol: "Please enter cholesterol info"
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('text-red-500 text-xs mt-1');
                element.closest('.space-y-4').append(error);
            },
            highlight: function(element) {
                $(element).addClass('border-red-500').removeClass('border-gray-300 focus:ring-purple-500 focus:border-transparent');
            },
            unhighlight: function(element) {
                $(element).removeClass('border-red-500').addClass('border-gray-300 focus:ring-purple-500 focus:border-transparent');
            },
            submitHandler: function(form) {
                $('.form-submit-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Saving...');
                
                let formData = new FormData(form);
                
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
                        $('.form-submit-btn').prop('disabled', false).html('Save Diagnosis Test');
                        
                        if(response.status == 200) {
                            display_msg(response.msg, 'success');
                            setTimeout(() => {
                                window.location.href = response.redirect_to;
                            }, 2000);
                        } else if(response.status == 422) {
                            let errors = response.errors;
                            let errorHtml = '';
                            
                            for (let key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errors[key].forEach(message => {
                                        errorHtml += `<p class="text-red-500 text-sm">${message}</p>`;
                                    });
                                }
                            }
                            
                            display_msg(errorHtml);
                        } else {
                            display_msg('Something went wrong! Please try again.');
                        }
                    },
                    error: function(xhr) {
                        $('.form-submit-btn').prop('disabled', false).html('Save Diagnosis Test');
                        display_msg('An error occurred. Please try again.');
                        console.error(xhr.responseText);
                    }
                });
                
                return false;
            }
        });
    }

    // Display message function
    function display_msg(data, type = 'error') {
        let alertClass = type === 'success' ? 
            'bg-green-50 text-green-800 border-green-300' : 
            'bg-red-50 text-red-800 border-red-300';
        
        let icon = type === 'success' ?
            '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>' :
            '<path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>';
        
        let title = type === 'success' ? 'Success!' : 'Error!';
        
        let content = `
        <div class="flex items-center p-4 mb-4 text-sm rounded-lg ${alertClass}" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                ${icon}
            </svg>
            <span class="sr-only">${title}</span>
            <div>
                <span class="font-medium">${title}</span> ${data}
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-gray-100 focus:outline-none" data-dismiss-target=".msg-container > div" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
        `;
        
        $('.msg-container').html(content);
        
        // Add click handler for close button
        $('[data-dismiss-target]').on('click', function() {
            $($(this).data('dismiss-target')).remove();
        });
    }

    // Cancel button handler
    $('.cancel-btn').on('click', function() {
        $('#diagnosis_test_form')[0].reset();
    });
});
</script>
@endpush