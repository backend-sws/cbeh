$(document).ready(function () {
        
    let valid_form=['user_add','user_edit'];
    var formId=$('.form-class').prop('id');
    if (!valid_form.includes(formId)) {
        console.log('Form content not found!');
        return 0;
    }
    var form = $("#" + formId);
    
    $.validator.addMethod("extension", function(value, element, param) {
        var validExtensions = param.split('|'); // e.g., "jpg|jpeg|png"
        var fileExtension = value.split('.').pop().toLowerCase();
        return this.optional(element) || validExtensions.includes(fileExtension);
    }, "Only JPG, JPEG, or PNG formats are allowed.");

   
    const baseRules = {
        first_name: {
            required: true,
            minlength: 1,
            maxlength: 100,
        },
        last_name: {
            required: true,
            minlength: 1,
            maxlength: 100,
        },
        email: {
            required: false,
            email: true,
            maxlength: 100,
        },
        role: {
            required: true,
        },
        dob: {
            required: false,
            maxlength: 500,
        },
        phone: {
            required: true,
            number: true,
            rangelength: [10, 10]
        },
        gender: {
            required: true,
        },
        facebook_url: false,
        instagram_url: false,
        twitter_url: false,
        linkedin_url: false,
    };
    
    const userAddRules = {
        password: {
            required: true,
            minlength: 8,
            maxlength: 50
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        },
        image: {
            required: false,
            extension: "jpg|jpeg|png",
        },
    };
    
    const userEditRules = {
        password: {
            required: false,
            minlength: 8,
            maxlength: 50
        },
        password_confirmation: {
            required: false,
            equalTo: "#password"
        },
        image: {
            required: false,
            extension: "jpg|jpeg|png",
        },
    };
    
    // Dynamically set rules based on formId
    let rules = baseRules;
    
    if (formId === 'user_add') {
        rules = { ...baseRules, ...userAddRules };
    } else if (formId === 'user_edit') {
        rules = { ...baseRules, ...userEditRules };
    }
    
    form.validate({
    rules: rules,
    messages: {
        first_name: {
            required: "First name is required.",
            minlength: "First name must be at least 1 character long.",
            maxlength: "First name cannot exceed 100 characters.",
        },
        last_name: {
            required: "Last name is required.",
            minlength: "Last name must be at least 1 character long.",
            maxlength: "Last name cannot exceed 100 characters.",
        },
        email: {
            required: "Email is required.",
            email: "Please enter a valid email address.",
            maxlength: "Email cannot exceed 100 characters.",
        },
        role: {
            required: "Role is required.",
        },
        // dob: {
        //     required: "Date of birth is required.",
        //     maxlength: "Date of birth input exceeds the maximum length.",
        // },
        phone: {
            required: "Phone number is required.",
            number:'Only number enter.',
            rangelength: "Phone number must be 10 digits.",
        },
        gender: {
            required: "Gender is required.",
        },
        password: {
            required: "Password is required.",
            minlength: "Password must be at least 8 characters long.",
            maxlength: "Password cannot exceed 50 characters.",
        },
        password_confirmation: {
            required: "Password confirmation is required.",
            equalTo: "Password confirmation must match the password.",
        },
        facebook_url: "Facebook URL is required.",
        instagram_url: "Instagram URL is required.",
        twitter_url: "Twitter URL is required.",
        linkedin_url: "LinkedIn URL is required.",
        image: {
            required: "An image is required.",
            extension: "Only JPG, JPEG, or PNG formats are allowed.",
        }
    },
    errorPlacement: function(error, element) {
        error.addClass('text-danger');
        if (element.prop("name") === "phone") {
            $('.phone-field-error').html(error);
        }
        else if (element.prop("type") === "radio") {
            $('.gender-field-error').html(error);
        }
        else if (element.prop("type") === "file") {
            $('.image-field-error').html(error);
        }
         else {
            error.insertAfter(element);
        }
    },
    errorElement: 'label',
    submitHandler: function(form) {
        $('.form-submit-btn').prop('disabled',true);
        let formData = new FormData(form);
        // let formId=form.id;
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
                // console.log("Form submitted successfully!", response);
                let content='';
                if(response.status==200){
                    $(form).hide();
                    let msg=response.msg;
                    let redirect_location=response.redirect_to;
                    content += `<span>${msg}</span>`;
                    display_msg(content,'success');
                    setTimeout(()=>{
                        window.location=redirect_location;
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
                console.error("XHR Status:", xhr.status);
                console.error("XHR Response:", xhr.responseText);
                console.error("XHR Response JSON:", xhr.responseJSON);
                let errorMsg = "There was an error submitting the form. Please try again.";
                if (xhr.status === 401) {
                    errorMsg = "Unauthorized! Please login again.";
                } else if (xhr.status === 403) {
                    errorMsg = "Forbidden! You don't have permission to update this user.";
                } else if (xhr.status === 404) {
                    errorMsg = "Route not found! Please check the URL.";
                } else if (xhr.status === 419) {
                    errorMsg = "Session expired! Please refresh and try again.";
                } else if (xhr.status === 500) {
                    errorMsg = "Server error: " + (xhr.responseJSON?.errors || xhr.responseJSON?.message || error);
                }
                alert(errorMsg);
            }
        });
        return false; // Prevent default form submission
    }
    });

    $('input[type="file"]').on('change', function() {
        if ($(this).valid()) {
            $('.image-field-error').html(''); // Clear the error message
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
                console.log('clicked',button);
                const targetSelector = button.getAttribute('data-dismiss-target');
                const alertElement = document.querySelector(targetSelector);
                if (alertElement) {
                    alertElement.classList.add('hidden');
                }
            });
        });
    }
});