$('#doctor').on('change', function() {
    var doctorId = $(this).val(); // Get the selected doctor ID
    if(!doctorId){
        var formId=$('.form-class').prop('id');
        var form = $("#" + formId);
        form.trigger("reset");
        return 0;
    }
    var scheduleUrl = $(this).data('get-doctor-schedule').slice(0, -1) + doctorId; 
    $.ajax({
            url: scheduleUrl,
            method: 'GET', 
            success: function(response) {
                console.log(response);
                if(response.data){
                    $('#per_patient_time').val(response.data.per_patient_time);
                    $('#monday_available_from').val(response.data.monday_available_from);
                    $('#monday_available_to').val(response.data.monday_available_to);
                    $('#tuesday_available_from').val(response.data.tuesday_available_from);
                    $('#tuesday_available_to').val(response.data.tuesday_available_to);
                    $('#wednesday_available_from').val(response.data.wednesday_available_from);
                    $('#wednesday_available_to').val(response.data.wednesday_available_to);
                    $('#thursday_available_from').val(response.data.thursday_available_from);
                    $('#thursday_available_to').val(response.data.thursday_available_to);
                    $('#friday_available_from').val(response.data.friday_available_from);
                    $('#friday_available_to').val(response.data.friday_available_to);
                    $('#saturday_available_from').val(response.data.saturday_available_from);
                    $('#saturday_available_to').val(response.data.saturday_available_to);
                    $('#sunday_available_from').val(response.data.sunday_available_from);
                    $('#sunday_available_to').val(response.data.sunday_available_to);
            }

            }
    });
});
    
    let valid_form=['add_schedule'];
    var formId=$('.form-class').prop('id');
    function validateForm(){
        if (!valid_form.includes(formId)) {
            return 0;
        }
    }
    validateForm();
    var form = $("#" + formId);

    form.validate({
    rules: {
        doctor:'required',
        per_patient_time:{
            required:true,
            number:true,
        },
    },
    messages: {
        doctor:{
            required:"Please select a doctor."
        }, 
        per_patient_time:{
            required:"Please specify the time(in minutes) allocated per patient.", 
            number:"Please enter a valid number(minutes)."
        }
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
                    content += `<span>${msg}</span>`;
                    display_msg(content,'success');
                    setTimeout(()=>{
                        window.location.reload();
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
