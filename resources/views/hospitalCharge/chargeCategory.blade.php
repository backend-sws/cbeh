@extends('layouts.admin')

@section('title', 'Charge Categories')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="card bg-white p-4 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Charge Categories</h2>
      <div class="flex items-center space-x-3 mt-4 md:mt-0">
        {{-- <button class="bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 009 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
          </svg>
          Filter
        </button> --}}
        <button id="openBedTypeModalBtn" class="bg-main-color text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition-colors flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          New Charge Category
        </button>
      </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="grid grid-cols-12 bg-gray-200 px-6 py-3 border border-gray-200">
        <div class="col-span-10 font-medium text-gray-700">Charge Category</div>
        <div class="col-span-2 font-medium text-gray-700 text-right pr-2">Actions</div>
      </div>

      <!-- Table Rows -->
      <div class="divide-y">
        @foreach($chargeCategories as $chargeCategory)
          <div class="grid grid-cols-12 px-6 py-4 hover:bg-gray-50 transition-colors">
            <div class="col-span-10 text-main-color font-medium">{{ $chargeCategory->charge_category }}</div>
            <div class="col-span-2 flex justify-end space-x-2">
              <button onclick="event.preventDefault(); openEditBedTypeModal({{ $chargeCategory->id }});" 
                class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition-colors">
                <i class="fas fa-edit"></i>
              </button>
              <button onclick="deleteChargeCategory(event, {{ $chargeCategory->id }})" 
                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors">
                <i class="fas fa-trash"></i>
              </button>
              

            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- New Charge Category Modal -->
  <div id="newBedTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="flex  justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">New Charge Category</h3>
        <button id="closeBedTypeModalBtn" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.charge.chargeType.store') }}" method="POST" class="p-6 space-y-4" id="ChargeCategory" onsubmit="formSubmit2(event, this, false, 'ChargeCategory')">
        @csrf
        <div>
          <label for="charge_category" class="block text-sm font-medium text-gray-700 mb-1">Charge Category <span class="text-red-500">*</span></label>
          <input type="text" id="charge_category" name="charge_category" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color">
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500"></span></label>
          <textarea id="description" name="description" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color"></textarea>
        </div>
        <div>
          <label for="charge_type" class="block text-sm font-medium text-gray-700 mb-1">Charge Type <span class="text-red-500">*</span></label>
          <select id="charge_type" name="charge_type" required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color">
            <option value="">-- Select Charge Type --</option>
            @foreach($chargeType as $chargeTypes)
              <option value="{{$chargeTypes->id}}">{{$chargeTypes->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" id="cancelModalBtn" 
                  class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button type="submit" 
                  class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Charge Category Modal -->
  <div id="editBedTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Edit Charge Category</h3>
        <button id="closeEditBedTypeModalBtn" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form id="editCategoryForm" method="POST" class="p-6 space-y-4"  onsubmit="formSubmit2(event, this, false, 'editCategoryForm')">
        @method('PUT')
        @csrf

        <div>
          <label for="editChargeCategory" class="block text-sm font-medium text-gray-700 mb-1">Charge Category <span class="text-red-500">*</span></label>
          <input type="text" id="editChargeCategory" name="name" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color">
        </div>
        <div>
          <label for="editDescription" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500"></span></label>
          <textarea id="editDescription" name="description" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" id="cancelEditModalBtn" 
                  class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button type="submit" 
                  class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
  <script>
    // Modal Toggle Script
    $(document).ready(function() {
      // New Charge Category Modal
      $('#openBedTypeModalBtn').click(function() {
        $('#newBedTypeModal').removeClass('hidden').addClass('flex');
      });

      $('#closeBedTypeModalBtn, #cancelModalBtn').click(function() {
        $('#newBedTypeModal').removeClass('flex').addClass('hidden');
      });

      // Edit Charge Category Modal
      window.openEditBedTypeModal = function(chargeTypeId) {
        const url = `/admin/charge/get/Category/${chargeTypeId}`;
        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            $('#editChargeCategory').val(data.charge_category);
            $('#editDescription').val(data.description);
            $('#editCategoryForm').attr('action', `/admin/charge/edit-charge-cat/${chargeTypeId}`);
            // Fix: Remove 'hidden' before adding 'flex'
            $('#editBedTypeModal').removeClass('hidden').addClass('flex');
          },
          error: function() {
            alert('Error fetching charge category data.');
          }
        });
      };

      $('#closeEditBedTypeModalBtn, #cancelEditModalBtn').click(function() {
        // Fix: Remove 'flex' before adding 'hidden'
        $('#editBedTypeModal').removeClass('flex').addClass('hidden');
      });
    });
  </script>
  <script>
  function deleteChargeCategory(event, chargeCategoryId) {
    event.preventDefault();

    if (!confirm('Are you sure you want to delete this Charge Category?')) return;
var url = "{{ route('admin.charge.delete.charge.type', ':id') }}".replace(':id', chargeCategoryId);

    $.ajax({

      url: url,
      type: 'POST',
      data: {
        _method: 'DELETE',
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
       window.location.reload(); // Reload the page to reflect changes
      },
      error: function(xhr) {
        alert('Error deleting charge category.');
      }
    });
  }
</script>

</body>
@endsection