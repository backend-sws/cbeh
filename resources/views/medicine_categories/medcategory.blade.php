@extends('layouts.admin')

@section('title', 'Medicine Categories')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex card bg-white p-6 flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Medicine Categories</h1>
                    <p class="text-gray-600 mt-1">Manage all medicine categories in your system</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button
                        class="flex items-center justify-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="ph ph-funnel text-gray-500"></i>
                        <span>Filter</span>
                    </button>
                    <button onclick="toggleMedicineModal(true)"
                        class="flex items-center justify-center space-x-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="ph ph-plus-circle text-white"></i>
                        <span>New Medicine Category</span>
                    </button>
                </div>
            </div>


            <div class="bg-white rounded-xl shadow p-6">
                <!-- Alerts -->
                <div class="mb-6">
                    <x-alert type="success" :message="session('success')" />
                    <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
                    <div class="msg-container"></div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    {{-- <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th> --}}
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($medicineCategory as $category)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $category->medicine_category }}</div>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                                            <label class="inline-flex relative items-center cursor-pointer">
                                                <input type="checkbox" name="list_status" class="sr-only peer toggle-status"
                                                    data-status="{{ $category->status }}" data-id="{{ $category->id }}"
                                                    @if ($category->status) checked @endif />
                                                <div
                                                    class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:left-[4px] after:top-[3px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all">
                                                </div>
                                            </label>
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <button onclick="toggleMedicineModal(true, this, 'view')"
                                                    data-medicine-category="{{ $category->medicine_category }}"
                                                    data-status="{{ $category->status }}"
                                                    class="p-2 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-colors">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button onclick="toggleMedicineModal(true, this, 'delete')"
                                                    data-url="{{ route('admin.medicine.category.delete', $category->id) }}"
                                                    class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($medicineCategory->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            @include('partials.pagination', ['users' => $medicineCategory])
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="medicineModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 modal-header">New Medicine Category</h2>
                    <button onclick="toggleMedicineModal(false)" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form class="form-class" id="medicine_category_form" method="post"
                    action="{{ route('admin.medicine.category.save') }}">
                    @csrf
                    <div class="p-6 main-fields">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="medicine_category" placeholder="Enter category name..." required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>

                        <div class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1 status-label">Status</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" checked class="sr-only peer" required>
                                <div
                                    class="relative w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-purple-600 after:content-[''] after:absolute after:left-[4px] after:top-[3px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full">
                                </div>
                                <span class="ml-3 text-sm text-gray-600" id="status-text">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="p-6 delete-msg" style="display:none;">
                        <p class="text-gray-700">Are you sure you want to delete this medicine category?</p>
                        <p class="text-sm text-gray-500 mt-2">This action cannot be undone.</p>
                    </div>

                    <!-- Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3 btn-div">
                        <button type="button" onclick="toggleMedicineModal(false)"
                            class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Status toggle text
            $('input[name="status"]').change(function() {
                $('#status-text').text(this.checked ? 'Active' : 'Inactive');
            });

            // Toggle status handler
            $('.toggle-status').on('click', function() {
                let element = $(this);
                let id = element.data('id');
                let status = element.data('status');
                let newStatus = status ? 0 : 1;

                let formData = new FormData();
                formData.append('id', id);
                formData.append('status', newStatus);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: "{{ route('admin.medicine.category.toggle') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        display_msg(response.msg, 'success');
                        element.data('status', newStatus);
                    },
                    error: function(xhr) {
                        display_msg(xhr.responseJSON?.msg || 'An error occurred', 'error');
                    }
                });
            });
        });

        function toggleMedicineModal(show, tag = null, actionType = null) {
            const modal = $('#medicineModal');
            if (show) {
                modal.removeClass('hidden').addClass('flex');
                $('.btn-div').show();

                if (tag && actionType === 'view') {
                    let element = $(tag);
                    let category = element.data('medicine-category');
                    let status = element.data('status');

                    $('.modal-header').text('View Medicine Category');
                    $('.main-fields').show();
                    $('.delete-msg').hide();
                    $('.btn-div').hide();
                    $('input[name="medicine_category"]').val(category).prop('disabled', true);
                    setStatusToggle(status);
                    $('input[name="status"]').prop('disabled', true);

                } else if (tag && actionType === 'delete') {
                    let element = $(tag);
                    let url = element.data('url');

                    $('.modal-header').text('Delete Medicine Category');
                    $('.main-fields').hide();
                    $('.delete-msg').show();
                    $('#medicine_category_form')[0].reset();
                    $('#medicine_category_form').attr('action', url);
                    $('.btn-div button[type="submit"]').text('Delete').removeClass('bg-purple-600 hover:bg-purple-700')
                        .addClass('bg-red-600 hover:bg-red-700');

                } else {
                    // Add new
                    $('.modal-header').text('New Medicine Category');
                    $('.main-fields').show();
                    $('.delete-msg').hide();
                    $('.btn-div').show();
                    $('input[name="medicine_category"]').prop('disabled', false);
                    $('input[name="status"]').prop('disabled', false);
                    $('.btn-div button[type="submit"]').text('Save').removeClass('bg-red-600 hover:bg-red-700').addClass(
                        'bg-purple-600 hover:bg-purple-700');
                    $('#medicine_category_form').attr('action', $('#medicine_category_form').data('action'));
                }

            } else {
                modal.addClass('hidden').removeClass('flex');
                $('#medicine_category_form')[0].reset();
                $('input[name="medicine_category"]').prop('disabled', false);
                $('input[name="status"]').prop('disabled', false);
            }
        }

        function setStatusToggle(status) {
            const $status = $('input[name="status"]');
            const isChecked = $status.prop('checked');
            if ((status == 1 && !isChecked) || (status == 0 && isChecked)) {
                $status.trigger('click');
            }
            $('#status-text').text(status ? 'Active' : 'Inactive');
        }

        let valid_form = ['medicine_category_form'];
        var formId = $('.form-class').prop('id');

        function validateForm() {
            if (!valid_form.includes(formId)) {
                console.log('Form content not found!');
                return 0;
            }
            var form = $("#" + formId);
            form.validate({
                rules: {
                    medicine_category: {
                        required: true,
                        maxlength: 255,
                        normalizer: function(value) {
                            return $.trim(value);
                        }
                    },
                    status: {
                        required: false,
                    },
                },
                messages: {
                    medicine_category: {
                        required: "Please enter the medicine category",
                        maxlength: "Category must be at most 255 characters long"
                    },
                    status: {
                        required: "Please select the status",
                    },
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-red-500 text-xs mt-1');
                    error.insertAfter(element);
                },
                errorElement: 'span',
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }

        validateForm();

        function display_msg(data, msg_type = 'error') {
            let content = '';
            if (msg_type === 'success') {
                content = `
            <div class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <div class="text-sm font-medium">${data}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg p-1.5 hover:bg-green-200 inline-flex items-center justify-center" data-dismiss-target>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;
            } else {
                content = `
            <div class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <div class="text-sm font-medium">${data}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg p-1.5 hover:bg-red-200 inline-flex items-center justify-center" data-dismiss-target>
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;
            }

            $('.msg-container').html(content);

            // Add click event for dismiss buttons
            $('[data-dismiss-target]').click(function() {
                $(this).parent().fadeOut(200, function() {
                    $(this).remove();
                });
            });

            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $('.msg-container').children().fadeOut(200, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    </script>
@endpush
