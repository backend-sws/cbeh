<!DOCTYPE html>
<html lang="en" <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard')</title>

<meta name="csrf-token" content="{{ csrf_token() }}">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
            }
        }
    }
</script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

{{-- @stack('css') --}}
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Custom scrollbar for sidebar
    .sidebar-scrollbar::-webkit-scrollbar {
        width: 4px !important;
    }

    .sidebar-scrollbar::-webkit-scrollbar-thumb {
        background-color: #924AEF;
        border-radius: 4px;
    }

    .sidebar-scrollbar::-webkit-scrollbar-track {
        background-color: #f3f4f6;
    } */
    .text-danger {
        color: red;
    }

    .text-success {
        color: green;
    }
</style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased overflow-x-hidden selection:bg-pink-100 selection:text-pink-900">

    <!-- Mobile Overlay -->
    <div id="overlay" onclick="closeSidebar()" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden transition-opacity duration-300"></div>

    @include('partials.header')
    <!-- Include Header -->

    <div class="flex h-screen pt-[72px] overflow-hidden">
        @include('partials.sidebar')
        <!-- Include Sidebar -->

        <div id="main-content" class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 transition-all duration-300 md:ml-64 w-full relative">
            @yield('content')
            <!-- Dynamic content -->
        </div>
    </div>


        <style>
        @media (max-width: 767px) {
            #main-content {
                padding-left: 0 !important;
                margin-left: 0 !important;
            }
            #sidebar {
                width: 80vw !important;
                min-width: 220px;
                max-width: 320px;
            }
        }
        #main-content.full-width {
            margin-left: 0 !important;
        }
        .header-full {
            width: 100vw !important;
            left: 0 !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById('sidebar');
            let overlay = document.getElementById('overlay');
            let mainContent = document.getElementById('main-content');
            let header = document.querySelector('header');

            if (window.innerWidth >= 768) {
                // Desktop toggle
                sidebar.classList.toggle('-translate-x-full');
                mainContent.classList.toggle('md:ml-64');
                mainContent.classList.toggle('md:ml-0');
                if (header) {
                    header.classList.toggle('md:w-[calc(100%-16rem)]');
                    header.classList.toggle('md:left-64');
                    header.classList.toggle('w-full');
                    header.classList.toggle('left-0');
                }
            } else {
                // Mobile toggle
                sidebar.classList.toggle('-translate-x-full');
                if (overlay) overlay.classList.toggle('hidden');
            }
        }

        function closeSidebar() {
            let sidebar = document.getElementById('sidebar');
            let overlay = document.getElementById('overlay');
            if(window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')

    {{-- @push('scripts') --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>




<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        // const ctx = document.getElementById('lineChart').getContext('2d');
        // new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: ['FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL'],
        //         datasets: [{
        //             label: 'Earned',
        //             borderColor: '#4f46e5',
        //             data: [50, 80, 40, 60, 90, 70],
        //             tension: 0.4,
        //         }, {
        //             label: 'Expenses',
        //             borderColor: '#e11d48',
        //             data: [30, 60, 50, 80, 40, 90],
        //             tension: 0.4,
        //         }]
        //     }
        // });
    </script>
    {{-- @endpush --}}

    <script>
        function initializeDataTable(tableId, ajaxUrl, columnNames, order = [
            [1, 'asc']
        ], paging = true) {
            const columnsConfig = generateDataTableColumn(columnNames);

            $(document).ready(function() {
                const table = $(tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    paging: paging,
                    ajax: {
                        url: ajaxUrl,
                        type: 'GET',
                    },
                    columns: columnsConfig,

                    createdRow: function(row, data, dataIndex) {
                        $(row).addClass('odd:bg-gray-200');
                        $(row).addClass('even:bg-gray-50');
                        $('td', row).each(function(index) {
                            $(this).addClass(
                                'border border-gray-300 px-4 py-2 text-sm text-gray-700');
                            if (index === 2) {
                                $(this).addClass('text-center');
                            }
                        });
                    },

                    drawCallback: function() {
                        // Style header
                        $(`${tableId} thead th`).addClass(
                            'border border-gray-300 px-4 py-2 text-left bg-gray-100 text-gray-700');
                        $(`${tableId} thead th:nth-child(3)`).addClass('text-center');

                        // Style pagination
                        const paginateWrapper = $(`${tableId}_paginate`);
                        paginateWrapper
                            .addClass(
                                'flex flex-wrap items-center justify-center sm:justify-end gap-1 w-full mt-2 sm:mt-0'
                            );

                        paginateWrapper.find('.paginate_button')
                            .addClass(
                                'px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition-colors shadow-sm mb-1'
                            );

                        paginateWrapper.find('.paginate_button.current')
                            .addClass('bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 hover:text-white');
                    },

                    initComplete: function() {
                        // ✅ Style search filter (top right)
                        const filterWrapper = $(`${tableId}_filter`);
                        filterWrapper
                            .addClass(
                                'flex justify-end items-center mb-4 bg-white px-4 py-2 rounded shadow-sm'
                            );
                        filterWrapper.find('input')
                            .addClass(
                                'border border-gray-300 px-3 py-1 rounded text-sm focus:outline-none focus:ring focus:border-indigo-300'
                            );

                        // ✅ Style length dropdown (bottom left)
                        const lengthDropdown = $(`${tableId}_length`);
                        lengthDropdown.empty();
                        lengthDropdown
                            .addClass('bg-white px-4 py-2 rounded shadow-sm mr-auto flex items-center');

                        lengthDropdown.append(`
                    <label for="perPage" class="mr-2 text-gray-700 dark:text-gray-400">Per Page:</label>
                    <select name="perPage" id="perPage" class="border border-gray-300 rounded p-2 py-1 w-[70px] text-sm ">
                        <option value="10" ${paging === 10 ? 'selected' : ''}>10</option>
                        <option value="25" ${paging === 25 ? 'selected' : ''}>25</option>
                        <option value="50" ${paging === 50 ? 'selected' : ''}>50</option>
                        <option value="100" ${paging === 100 ? 'selected' : ''}>100</option>
                    </select>
                `);
                    },

                    order: order,
                    scrollX: true, // Prevents horizontal overflow by allowing table body to scroll

                    // 👇 Search (top right), Table (middle), Length + Pagination (bottom)
                    dom:
                        "<'w-full overflow-hidden rounded-lg shadow ring-1 ring-black ring-opacity-5 mb-4'tr>" + // Table with Tailwind styling
                        "<'flex flex-col sm:flex-row justify-between items-center w-full px-2 py-3 bg-white gap-4'<'length-dropdown'l><'pagination-controls w-full sm:w-auto'p>>",

                    pagingType: "simple_numbers",
                    language: {
                        paginate: {
                            previous: "«",
                            next: "»"
                        }
                    }
                });
            });
        }




        const BaseTableColumns = {
            id: {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
                orderable: false,
                searchable: false
            },
            action: {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        };

        function generateDataTableColumn(columnNames) {
            const dynamicColumns = columnNames.map(name => {
                if (name === 'id') {
                    return BaseTableColumns.id
                } else if (name === 'checkbox') {
                    return {
                        data: name,
                        name: name,
                        orderable: false,
                        searchable: false
                    };
                } else {
                    return {
                        data: name,
                        name: name,
                    };
                }
            });
            return [...dynamicColumns];
        }

        function edit_swal(thiss) {
            const url = $(thiss).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    const data = response.data;

                    Swal.fire({
                        title: response.title,
                        html: `
                    <form id="edit-form" action="${response.route}" method="POST" class="text-left space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="p-4 border rounded-lg shadow-md bg-gray-100">
                            ${data}
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 transition" onclick="Swal.close()">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update</button>
                        </div>
                    </form>
                `,
                        showConfirmButton: false,
                        showCloseButton: true,
                        customClass: {
                            popup: "p-6 rounded-lg shadow-lg bg-white",
                            title: "text-xl font-semibold text-gray-800",
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                    Swal.fire({
                        title: "Error",
                        text: "Failed to fetch data. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#dc2626",
                    });
                }
            });
        }


        function formSubmit(e, formData, modal_id) {
            e.preventDefault();
            var serilizFormData = new FormData(formData);
            $.ajax({
                processData: false,
                contentType: false,
                url: formData.action,
                type: formData.method,
                data: serilizFormData,
                success: function(response) {
                    var massage = response.message;
                    if (response.status_code == 1) {
                        $('#modal' + modal_id).modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: massage ?? 'successfully.',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            location.reload();
                        });
                    } else if (response.status_code == 3) {
                        $('.available_' + modal_id).show();
                        $('#submit_' + modal_id).html('Create Shipment')
                        $('.checkBoxGroup_' + modal_id).html(response.data);
                        $('#form_' + modal_id).attr('action', $('#ship_by_shiprocket').attr('action'))
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Information',
                            text: massage ?? 'An unexpected error occurred.',
                            confirmButtonText: 'OK',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error: ', error);
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'An unexpected error occurred.',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }

        function formSubmit2(e, formData, modal_id = false, formId = null, validationRules = {}) {
            e.preventDefault();

            const $form = formId ? $("#" + formId) : $(formData);

            // Apply validation only if rules are passed
            if (Object.keys(validationRules).length > 0) {
                $form.validate({
                    rules: validationRules.rules,
                    messages: validationRules.messages,
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger text-sm');
                        error.insertAfter(element);
                    },
                    errorElement: 'label'
                });

                if (!$form.valid()) return false;
            }

            // Proceed with AJAX if valid
            const serilizFormData = new FormData(formData);
            $.ajax({
                processData: false,
                contentType: false,
                url: formData.action,
                type: formData.method,
                data: serilizFormData,
                success: function(response) {
                    const message = response.message;
                    let redirect_location = response.redirect_to;

                    if (response.status_code == 1) {
                        if (modal_id) {
                            $('#modal' + modal_id).modal('hide');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: message ?? 'Submitted successfully.',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            if (redirect_location) {
                                window.location.href = redirect_location;
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Information',
                            text: message ?? 'An unexpected error occurred.',
                            confirmButtonText: 'OK',
                        });
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response?.message || 'Something went wrong.',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }
        //
        function PostWithoutReload(e, formData, modal_id = false, formId = null, userdefineFunction = null,
            validationRules = {}) {
            console.log("post function name", userdefineFunction);
            e.preventDefault();

            const $form = formId ? $("#" + formId) : $(formData);

            // Apply validation only if rules are passed
            if (Object.keys(validationRules).length > 0) {
                $form.validate({
                    rules: validationRules.rules,
                    messages: validationRules.messages,
                    errorPlacement: function(error, element) {
                        error.addClass('text-danger text-sm');
                        error.insertAfter(element);
                    },
                    errorElement: 'label'
                });

                if (!$form.valid()) return false;
            }

            // Proceed with AJAX if valid
            const serilizFormData = new FormData(formData);
            $.ajax({
                processData: false,
                contentType: false,
                url: formData.action,
                type: formData.method,
                data: serilizFormData,
                success: function(response) {
                    const message = response.message;
                    let redirect_location = response.redirect_to;

                    if (response.status_code == 1) {
                        if (modal_id) {
                            $('#modal' + modal_id).modal('hide');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: message ?? 'Submitted successfully.',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            if (typeof userdefineFunction === "function") {
                                userdefineFunction();
                            } else if (typeof userdefineFunction === "string" && typeof window[
                                    userdefineFunction] === "function") {
                                window[userdefineFunction]();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Information',
                            text: message ?? 'An unexpected error occurred.',
                            confirmButtonText: 'OK',
                        });
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response?.message || 'Something went wrong.',
                        confirmButtonText: 'OK',
                    });
                }
            });
        }



        function display_msg(data, msg_type = 'danger') {
            let content = '';
            if (msg_type == 'success') {
                content = `
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
            } else {

                content = `
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
        $('.cancle-btn').on('click', function() {
            form.trigger("reset");
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#employee-select').select2({
                placeholder: "Select an employee",
                allowClear: true
            });
        });
    </script>
</body>

</html>
