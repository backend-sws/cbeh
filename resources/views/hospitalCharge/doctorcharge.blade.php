@extends('layouts.admin')

@section('title', 'Doctor Charge Management')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex card bg-white p-4 rounded-lg flex-col md:flex-row justify-between items-start md:items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Doctor Charge Management</h1>
      <div class="flex items-center space-x-3 mt-4 md:mt-0">
        {{-- <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
          </svg>
          <span>Filter</span>
        </button> --}}
        <button id="openBedTypeModalBtn" class="flex items-center space-x-2 px-4 py-2 bg-main-color text-white rounded-lg hover:bg-pink-700 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          <span>New Doctor Charge</span>
        </button>
      </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="grid grid-cols-12 bg-gray-200 px-6 py-3 border-b">
        <div class="col-span-4 font-medium text-gray-700">Doctor</div>
        <div class="col-span-4 font-medium text-gray-700">Charge</div>
        <div class="col-span-4 font-medium text-gray-700 text-right pr-2">Actions</div>
      </div>

      <!-- Table Rows -->
      <div class="divide-y">
        @foreach($doctorcharges as $doctorcharge)
          <div class="grid grid-cols-12 px-6 py-4 hover:bg-gray-50 transition-colors">
            <div class="col-span-4 text-main-color font-medium">
    {{ optional($doctorcharge->doctor)->name ?? 'N/A' }}
</div>
<div class="col-span-4 text-main-color font-medium">
    ₹{{ number_format($doctorcharge->charge, 2) }}
</div>

            <div class="col-span-4 flex justify-end space-x-2">
              <button onclick="event.preventDefault(); openEditBedTypeModal({{ $doctorcharge->id }});" 
                class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition-colors">
                <i class="fas fa-edit"></i>
              </button>
              <button onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this Doctor Charge?')) { document.getElementById('delete-charge-{{ $doctorcharge->id }}').submit(); }" 
                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors">
                <i class="fas fa-trash"></i>
              </button>
              <form id="delete-charge-{{ $doctorcharge->id }}" action="{{ route('admin.doctor.charge.delete', $doctorcharge->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
              </form>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- New Doctor Charge Modal -->
  <div id="newBedTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">New Doctor Charge</h3>
        <button id="closeBedTypeModalBtn" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.doctor.charge.add') }}" method="POST" class="p-6 space-y-4" id="chargeType" onsubmit="formSubmit2(event, this, false, 'chargeType')">
        @csrf
        <div>
          <label for="doctor" class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
          <select id="doctor" name="doctor_id" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor) 
              <option value="{{$doctor->id}}">{{$doctor->name}}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label for="charge" class="block text-sm font-medium text-gray-700 mb-1">Charge <span class="text-red-500">*</span></label>
          <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500">₹</span>
            </div>
            <input type="number" id="charge" name="charge" required
                   class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color"
                   placeholder="0.00">
          </div>
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500"></span></label>
          <textarea id="description" name="description" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color"
                    placeholder="Enter description"></textarea>
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

  <!-- Edit Doctor Charge Modal -->
  <div id="editBedTypeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Edit Doctor Charge</h3>
        <button id="closeEditBedTypeModalBtn" class="text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form id="editBedTypeForm" method="POST" class="p-6 space-y-4" onsubmit="formSubmit2(event, this, false, 'editBedTypeForm')">
        @method('PUT')
        @csrf
        <div>
          <label for="editDoctor" class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
          <select id="editDoctor" name="doctor_id" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
              <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label for="charge_category" class="block text-sm font-medium text-gray-700 mb-1">Charge <span class="text-red-500">*</span></label>
          <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500">₹</span>
            </div>
            <input type="number" id="charge_category" name="charge" required
                   class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-main-color focus:border-main-color"
                   placeholder="0.00">
          </div>
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
    $(document).ready(function() {
      // New Doctor Charge Modal
      $('#openBedTypeModalBtn').click(function() {
        $('#newBedTypeModal').removeClass('hidden').addClass('flex');
      });

      $('#closeBedTypeModalBtn, #cancelModalBtn').click(function() {
        $('#newBedTypeModal').removeClass('flex').addClass('hidden');
      });

      // Edit Doctor Charge Modal
      window.openEditBedTypeModal = function(chargeTypeId) {
        const url = `{{ route('admin.doctor.charge.edit', ['id' => '__ID__']) }}`.replace('__ID__', chargeTypeId);
        
        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            if (data && data.data) {
              $('#charge_category').val(data.data.charge);
              $('#editDoctor').val(data.data.doctor_id);
              $('#editDescription').val(data.data.description);
              $('#editBedTypeForm').attr('action', `{{ url('/admin/doctor/charge/edit') }}/${chargeTypeId}`);
              $('#editBedTypeModal').removeClass('hidden').addClass('flex');
            } else {
              alert('Invalid data format.');
            }
          },
          error: function() {
            alert('Error fetching doctor charge data.');
          }
        });
      };

      $('#closeEditBedTypeModalBtn, #cancelEditModalBtn').click(function() {
        $('#editBedTypeModal').removeClass('flex').addClass('hidden');
      });
    });
  </script>
</body>
@endsection