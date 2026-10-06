@extends('layouts.admin')

@section('title', 'Charge Management')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-6">
    <div class="card bg-white p-4 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Charge Management</h1>
      <div class="flex items-center space-x-3 mt-4 md:mt-0">
        {{-- <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
          <i class="ph ph-funnel text-lg"></i>
          <span>Filter</span>
        </button> --}}
        <button id="openModalBtn" class="flex items-center space-x-2 px-4 py-2 bg-main-color text-white rounded-lg hover:bg-pink-700 transition-colors">
          <i class="ph ph-plus-circle text-lg"></i>
          <span>New Charge</span>
        </button>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Charge Category</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Charge Type</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Standard Charge</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($charges as $charge)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                  {{ $charge->code }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $charge->chargeCategory ? $charge->chargeCategory->charge_category : "N/A" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $charge->chargetype ? $charge->chargetype->name : "N/A" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ₹{{ number_format($charge->standard_charge, 2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button onclick="openEditBedModal({{ $charge->id }})" class="text-blue-600 hover:text-blue-900 p-1.5 rounded-full hover:bg-blue-50 transition-colors">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this charge?')) { document.getElementById('delete-charge-{{ $charge->id }}').submit(); }" 
                  class="text-red-600 hover:text-red-900 p-1.5 rounded-full hover:bg-red-50 transition-colors">
                  <i class="fas fa-trash"></i>
                </button>
                <form id="delete-charge-{{ $charge->id }}" action="{{ route('admin.charge.Hospitalcharge.delete', $charge->id) }}" method="POST" class="hidden">
                  @csrf
                  @method('DELETE')
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div id="newBedModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900">New Charge</h3>
              <button id="closeModalBtn" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form action="{{ route('admin.charge.Hospitalcharge.add') }}" method="POST" id="charge" onsubmit="formSubmit2(event, this, false, 'charge')">
              @csrf
              <div class="space-y-4">
                <div>
                  <label for="charge_type" class="block text-sm font-medium text-gray-700">Charge Type <span class="text-red-500">*</span></label>
                  <select id="charge_type" name="charge_type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-main-color focus:border-main-color sm:text-sm rounded-md">
                    <option value="">Select Charge Type</option>
                    @foreach($chargeTypes as $chargeType)
                      <option value="{{ $chargeType->id }}">{{ $chargeType->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="charge_category" class="block text-sm font-medium text-gray-700">Charge Category <span class="text-red-500">*</span></label>
                  <select id="charge_category" name="charge_category" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-main-color focus:border-main-color sm:text-sm rounded-md">
                    <option value="">Select Charge Category</option>
                    @foreach($chargeCategories as $chargeCategorie)
                      <option value="{{ $chargeCategorie->id }}" >{{ $chargeCategorie->charge_category }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="code" class="block text-sm font-medium text-gray-700">Code <span class="text-red-500">*</span></label>
                  <input type="text" id="code" name="code" required class="mt-1 focus:ring-main-color focus:border-main-color block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
                <div>
                  <label for="standard_charge" class="block text-sm font-medium text-gray-700">Standard Charge <span class="text-red-500">*</span></label>
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">₹</span>
                    </div>
                    <input type="number" id="standard_charge" name="standard_charge" required class="focus:ring-main-color focus:border-main-color block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                  </div>
                </div>
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea id="description" name="description" rows="3" class="mt-1 focus:ring-main-color focus:border-main-color block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-color text-base font-medium text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color sm:col-start-2 sm:text-sm">
                  Save
                </button>
                <button type="button" id="closeModalBtnBottom" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color sm:mt-0 sm:col-start-1 sm:text-sm">
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div id="editBedModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Charge</h3>
              <button id="closeEditModalBtn" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <form id="editBedForm" method="POST" onsubmit="formSubmit2(event, this, false, 'editBedForm')">
              @csrf
              @method('PUT')
              <div class="space-y-4">
                <div>
                  <label for="editBedType" class="block text-sm font-medium text-gray-700">Charge Type</label>
                  <select id="editBedType" name="charge_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-main-color focus:border-main-color sm:text-sm rounded-md">
                    @foreach($chargeTypes as $chargeType)
                      <option value="{{ $chargeType->id }}">{{ $chargeType->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div>
                  <label for="echarge_category" class="block text-sm font-medium text-gray-700">Charge Category <span class="text-red-500">*</span></label>
                  <select id="echarge_category" name="charge_category" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-main-color focus:border-main-color sm:text-sm rounded-md">
                    <option value="">Select Charge Category</option>
                    @foreach($chargeCategories as $chargeCategorie)
                      <option value="{{ $chargeCategorie->id }}">{{ $chargeCategorie->charge_category }}</option>
                    @endforeach
                  </select>
                </div>
                
                <div>
                  <label for="editBedCharge" class="block text-sm font-medium text-gray-700">Charge Amount</label>
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">₹</span>
                    </div>
                    <input type="number" id="editBedCharge" name="standard_charge" class="focus:ring-main-color focus:border-main-color block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                  </div>
                </div>
                <div>
                  <label for="editBedDescription" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea id="editBedDescription" name="description" rows="3" class="mt-1 focus:ring-main-color focus:border-main-color block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-main-color text-base font-medium text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color sm:col-start-2 sm:text-sm">
                  Update
                </button>
                <button type="button" id="closeEditModalBtnBottom" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color sm:mt-0 sm:col-start-1 sm:text-sm">
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
  <script>
    // Modal Toggle
    $(document).ready(function() {
      // New Charge Modal
      $('#openModalBtn').click(function() {
        $('#newBedModal').removeClass('hidden');
      });

      $('#closeModalBtn, #closeModalBtnBottom').click(function() {
        $('#newBedModal').addClass('hidden');
      });

      // Edit Charge Modal Logic
      window.openEditBedModal = function(chargeId) {
        const url = `/admin/charge/charge/${chargeId}`;
        
        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            console.log('Hospital Charge Data:', data);
            
            // --- FIX START ---
            // Set the Action URL for the form
            $('#editBedForm').attr('action', `/admin/charge/edit-charge/${chargeId}`);

            // Set Charge Type
            $('#editBedType').val(data.charge_type);

            // Set Charge Category 
            // Using data.charge_category because your JSON returned "charge_category": "70" 
            // and the select option value is the ID ("70").
            $('#echarge_category').val(data.charge_category);

            // Set other fields
            $('#editBedCharge').val(data.standard_charge);
            $('#editBedDescription').val(data.description);
            // --- FIX END ---

            $('#editBedModal').removeClass('hidden');
          },
          error: function() {
            alert('Error fetching charge data.');
          }
        });
      };

      $('#closeEditModalBtn, #closeEditModalBtnBottom').click(function() {
        $('#editBedModal').addClass('hidden');
      });

      // Charge Type Change Handler (For New Charge Modal)
      $('#charge_type').change(function() {
        const chargeTypeId = $(this).val();
        const chargeCategorySelect = $('#charge_category');
        
        if (!chargeTypeId) {
          chargeCategorySelect.html('<option value="">Select Charge Category</option>');
          return;
        }

        chargeCategorySelect.html('<option value="">Loading...</option>');

        const url = "{{ route('admin.charge.get.chargecatbytype', ['id' => '__ID__']) }}".replace('__ID__', chargeTypeId);

        $.get(url)
          .done(function(data) {
            let options = '<option value="">Select Charge Category</option>';
            data.forEach(function(category) {
              options += `<option value="${category.id}">${category.charge_category}</option>`;
            });
            chargeCategorySelect.html(options);
          })
          .fail(function() {
            chargeCategorySelect.html('<option value="">Error loading categories</option>');
          });
      });
    });
  </script>
</body>
@endsection