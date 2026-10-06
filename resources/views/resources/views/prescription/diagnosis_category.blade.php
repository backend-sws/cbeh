@extends('layouts.admin')

@section('title', 'Diagnosis Category List')

@section('content')
<div class="bg-gray-50 p-6 font-sans">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Diagnosis Category</h1>
        <div class="flex space-x-3">
            <button class="flex items-center border border-main-color space-x-2 px-4 py-2 bg-pink-100 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ph ph-funnel"></i>
                <span>Filter</span>
            </button>
            <button onclick="toggleDiagnosisCategory(true)" class="flex items-center border border-main-color space-x-2 px-4 py-2 bg-pink-100 text-main-color rounded-lg hover:bg-pink-200 transition-colors">
                <i class="ph ph-plus"></i>
                <span>New Diagnosis Category</span>
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Alerts -->
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diagnosis Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($diagnosisCategory as $diagnosis)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $diagnosis->diagnosis_category }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                            <button onclick="toggleDiagnosisCategory(true,this,'edit')" 
                                    data-url="{{ route('admin.diagnosis.diagnosis_category.update', $diagnosis->id) }}"
                                    data-diagnosis-category="{{ $diagnosis->diagnosis_category }}"
                                    data-description="{{ $diagnosis->description }}"
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleDiagnosisCategory(true,this,'delete')" 
                                    data-url="{{ route('admin.diagnosis.diagnosis_category.delete', $diagnosis->id) }}"
                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        @include('partials.pagination', ['users' => $diagnosisCategory])
    </div>
</div>

<!-- Modal -->
<div id="DiagnosisCategory" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-lg shadow-xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800 modal-header">New Diagnosis Category</h2>
            <button onclick="toggleDiagnosisCategory(false)" class="text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
        </div>

        <!-- Modal Form -->
        <form class="space-y-4 form-class" id="diagnosis_category_form" method="post" 
              action="{{ route('admin.diagnosis.diagnosis_category.save') }}" 
              data-action="{{ route('admin.diagnosis.diagnosis_category.save') }}">
            @csrf
            <div class="p-6">
                <div class="main-fields space-y-4">
                    <!-- Diagnosis Category Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Diagnosis Category <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="diagnosis_category" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                  placeholder="Enter description..."></textarea>
                    </div>
                </div>

                <!-- Delete Confirmation -->
                <div class="delete-msg py-4 text-center" style="display:none;">
                    <p class="text-lg font-medium text-gray-800">Are you sure you want to delete this record?</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t px-6 py-4 flex justify-end space-x-3">
                <button type="button" onclick="toggleDiagnosisCategory(false)" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
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
    function toggleDiagnosisCategory(show, tag = null, actionType = null) {
        const modal = document.getElementById('DiagnosisCategory');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if(tag && (actionType == 'edit')) {
                let element = $(tag);
                if(element) {
                    let url = element.data('url');
                    let diagnosis_category = element.data('diagnosis-category');
                    let description = element.data('description');
                    
                    if(url) {
                        $('.modal-header').html('Edit Diagnosis Category');
                        $('.main-fields').show();
                        $('.delete-msg').hide();
                        $('input[name="diagnosis_category"]').val(diagnosis_category);
                        $('textarea[name="description"]').val(description);
                        $('#diagnosis_category_form').attr('action', url);
                    }
                }
            } else if(tag && (actionType == 'delete')) {
                let element = $(tag);
                if(element) {
                    let url = element.data('url');
                    if(url) {
                        $('.modal-header').html('Delete Diagnosis Category');
                        $('.main-fields').hide();
                        $('.delete-msg').show();
                        $('#diagnosis_category_form')[0].reset();
                        $('#diagnosis_category_form').attr('action', url);
                    }
                }
            }
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            $('.modal-header').html('New Diagnosis Category');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $('#diagnosis_category_form').attr('action', $('#diagnosis_category_form').data('action'));
            $('#diagnosis_category_form')[0].reset();
        }
    }

    // Form Validation
    $(document).ready(function() {
        $('#diagnosis_category_form').validate({
            rules: {
                diagnosis_category: {
                    required: true,
                    maxlength: 255
                },
                description: {
                    required: true,
                    maxlength: 500
                }
            },
            messages: {
                diagnosis_category: {
                    required: "Please enter diagnosis category",
                    maxlength: "Category name must be less than 255 characters"
                },
                description: {
                    required: "Please enter description",
                    maxlength: "Description must be less than 500 characters"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('text-red-500 text-xs mt-1');
                element.after(error);
            },
            highlight: function (element) {
                $(element).addClass('border-red-500').removeClass('border-gray-300');
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-500').addClass('border-gray-300');
            }
        });
    });
</script>
@endpush