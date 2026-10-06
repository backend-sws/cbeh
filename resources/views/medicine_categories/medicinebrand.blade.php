@extends('layouts.admin')

@section('title', 'Medicine Brands')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex card bg-white p-6 flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Medicine Company</h1>
                    <p class="text-gray-600 mt-1">Manage pharmaceutical brands in your system</p>
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
                        <span>New Company</span>
                    </button>
                </div>
            </div>

            <div class="card bg-white p-6">

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
                                        Company Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($medicineBrands as $brand)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $brand->brand }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="mailto:{{ $brand->email }}"
                                                class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $brand->email }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="tel:{{ $brand->phone }}" class="text-gray-900 hover:text-purple-600">
                                                {{ $brand->phone }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button onclick="toggleMedicineModal(true, this, 'edit')"
                                                    data-url="{{ route('admin.medicine.brand.update', $brand->id) }}"
                                                    data-brand="{{ $brand->brand }}" data-email="{{ $brand->email }}"
                                                    data-phone="{{ $brand->phone }}"
                                                    class="p-2 text-gray-500 hover:text-purple-600 rounded-lg hover:bg-purple-50 transition-colors"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button onclick="toggleMedicineModal(true, this, 'delete')"
                                                    data-url="{{ route('admin.medicine.brand.delete', $brand->id) }}"
                                                    class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="Delete">
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
                    @if ($medicineBrands->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            @include('partials.pagination', ['users' => $medicineBrands])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="medicineModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800 modal-header">New Medicine Company</h2>
                <button onclick="toggleMedicineModal(false)" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form class="form-class" id="medicine_brand_form" method="post"
                action="{{ route('admin.medicine.brand.save') }}" data-action="{{ route('admin.medicine.brand.save') }}">
                @csrf
                <div class="p-6 space-y-4 main-fields">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="brand" placeholder="Enter company name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500"></span>
                        </label>
                        <input type="email" name="email" placeholder="Enter email address"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone <span class="text-red-500"></span>
                        </label>
                        <div class="flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                +91
                            </span>
                            <input type="tel" name="phone" placeholder="Enter phone number"
                                class="flex-1 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="p-6 delete-msg" style="display:none;">
                    <div class="flex items-center justify-center text-red-500 mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-4xl mr-3"></i>
                        <p class="text-lg font-medium">Delete this brand?</p>
                    </div>
                    <p class="text-gray-600 text-center">This action cannot be undone. All related data will be permanently
                        removed.</p>
                </div>

                <!-- Buttons -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleMedicineModal(false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors submit-btn">
                        Save
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
        function toggleMedicineModal(show, tag = null, actionType = null) {
            const modal = $('#medicineModal');
            if (show) {
                modal.removeClass('hidden').addClass('flex');

                if (tag && actionType === 'edit') {
                    let element = $(tag);
                    let url = element.data('url');
                    let brand = element.data('brand');
                    let email = element.data('email');
                    let phone = element.data('phone');

                    $('.modal-header').text('Edit Medicine Brand');
                    $('.main-fields').show();
                    $('.delete-msg').hide();
                    $('input[name="brand"]').val(brand);
                    $('input[name="email"]').val(email);
                    $('input[name="phone"]').val(phone);
                    $('#medicine_brand_form').attr('action', url);
                    $('.submit-btn').text('Update');

                } else if (tag && actionType === 'delete') {
                    let element = $(tag);
                    let url = element.data('url');

                    $('.modal-header').text('Delete Medicine Brand');
                    $('.main-fields').hide();
                    $('.delete-msg').show();
                    $('#medicine_brand_form').attr('action', url);
                    $('.submit-btn').text('Delete').removeClass('bg-purple-600 hover:bg-purple-700').addClass(
                        'bg-red-600 hover:bg-red-700');

                } else {
                    $('.modal-header').text('New Medicine Brand');
                    $('.main-fields').show();
                    $('.delete-msg').hide();
                    $('#medicine_brand_form').attr('action', $('#medicine_brand_form').data('action'));
                    $('.submit-btn').text('Save').removeClass('bg-red-600 hover:bg-red-700').addClass(
                        'bg-purple-600 hover:bg-purple-700');
                }

            } else {
                modal.addClass('hidden').removeClass('flex');
                $('#medicine_brand_form')[0].reset();
            }
        }

        let valid_form = ['medicine_brand_form'];
        var formId = $('.form-class').prop('id');

        function validateForm() {
            if (!valid_form.includes(formId)) {
                console.log('Form content not found!');
                return 0;
            }
            var form = $("#" + formId);
            form.validate({
                rules: {
                    brand: {
                        required: true,
                        normalizer: function(value) {
                            return $.trim(value);
                        }
                    },
                    
                },
                messages: {
                    brand: {
                        required: "Please enter brand name",
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

        // Auto-dismiss alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.msg-container').children().fadeOut(200, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endpush
