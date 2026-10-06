@extends('layouts.admin')

@section('title', 'Pathology Units')

@section('content')
<body class="bg-gray-50 p-6">

    <!-- Card Header -->
    <div class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Pathology Units</h2>
                <p class="text-sm text-gray-600 mt-1">Manage all pathology test units</p>
            </div>
            <button onclick="toggleMedicineModal(true)"
                class="flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-sm font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Unit
            </button>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Body -->
        <div class="p-6">
            <!-- Alerts -->
            <x-alert type="success" :message="session('success')" />
            <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            <div class="msg-container"></div>

            <!-- Table Container -->
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Unit Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pathologyUnits as $unit)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $unit->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <!-- Edit Button -->
                                    <button title="Edit"
                                        onclick="toggleMedicineModal(true,this,'edit')"
                                        data-url="{{ route('admin.pathology.unit.update', $unit->id) }}"
                                        data-name="{{ $unit->name }}"
                                        class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button title="Delete"
                                        onclick="toggleMedicineModal(true,this,'delete')"
                                        data-url="{{ route('admin.pathology.unit.delete', $unit->id) }}"
                                        class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No pathology units found. Create your first unit!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                @include('partials.pagination', ['users' => $pathologyUnits])
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="medicineModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-lg shadow-xl transform transition-all">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 modal-header">New Pathology Unit</h3>
                <button onclick="toggleMedicineModal(false)" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form class="form-class" id="medicine_category_form"
                method="post"
                action="{{ route('admin.pathology.unit.save') }}"
                data-action="{{ route('admin.pathology.unit.save') }}">
                @csrf

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Name Input -->
                    <div class="main-fields">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Unit Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" placeholder="Enter unit name"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Delete Confirmation -->
                    <div class="my-2 delete-msg hidden text-sm text-gray-700">
                        <p>This action cannot be undone. All tests using this unit will need to be updated.</p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 px-6 py-4 bg-gray-50 rounded-b-lg">
                    <button type="button" onclick="toggleMedicineModal(false)"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <span class="modal-submit-btn">Save</span>
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
  function toggleMedicineModal(show,tag=null,actionType=null) {
    const modal = document.getElementById('medicineModal');
    if (show) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if(tag && (actionType =='edit')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          let name=element.data('name');
          if(url){
            $('.modal-header').html('Edit Pathology Unit');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $('.modal-submit-btn').text('Update');
            $('input[name="name"]').val(name);
            $('#medicine_category_form').attr('action',url);
          }
        }
      }else if(tag && (actionType =='delete')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          if(url){
            $('.modal-header').html('Delete Pathology Unit');
            $('.main-fields').hide();
            $('.delete-msg').show();
            $('.modal-submit-btn').text('Delete');
            $('#medicine_category_form')[0].reset();
            $('#medicine_category_form').attr('action',url);
          }
        }
      }

    } else {
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      $('.modal-header').html('New Pathology Unit');
      $('.main-fields').show();
      $('.delete-msg').hide();
      $('.modal-submit-btn').text('Save');
      $('#medicine_category_form').attr('action',$('#medicine_category_form').data('action'));
      $('#medicine_category_form')[0].reset();
    }
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
        name: {
          required: true,
          normalizer: function(value) {
            return $.trim(value);
          },
          maxlength:255
        },
      },
      messages: {
        name: {
          required: "Please enter unit name",
          maxlength:"Unit name cannot exceed 255 characters"
        },
      },
      errorElement: 'p',
      errorClass: 'mt-1 text-sm text-red-600',
      errorPlacement: function (error, element) {
        error.insertAfter(element);
      },
      submitHandler: function (form) {
        form.submit();
      }
    });
  }

  validateForm();
</script>
@endpush