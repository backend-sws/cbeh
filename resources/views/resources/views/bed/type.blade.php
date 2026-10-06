@extends('layouts.admin')

@section('title', 'Bed Types')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class=" card bg-white p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">Bed Types Management</h2>
      <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        {{-- <button class="flex items-center text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-md transition-colors duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z" />
          </svg>
          Filter
        </button> --}}
        <button id="openBedTypeModalBtn" class="flex items-center bg-main-color text-white px-4 py-2 rounded-md hover:bg-pink-700 transition-colors duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          New Bed Type
        </button>
      </div>
    </div>

    <!-- Card Container -->
    <div class="card bg-white rounded-xl shadow-sm overflow-hidden">
      <!-- Table Header -->
      <div class="grid grid-cols-2 font-medium text-gray-700 bg-gray-50 px-6 py-4 border-b">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          BED TYPE
        </div>
        <div class="flex justify-end items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
          </svg>
          ACTIONS
        </div>
      </div>

      <!-- Table Rows -->
      <div class="divide-y divide-gray-100">
        @foreach($bedTypes as $bedType)
          <div class="grid grid-cols-2 px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
            <div class="flex items-center text-main-color font-medium">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              {{ $bedType->name }}
            </div>
            <div class="flex justify-end items-center space-x-4">
              <button onclick="event.preventDefault(); openEditBedTypeModal({{ $bedType->id }});" class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this bed type?')) { document.getElementById('delete-bed-type-{{ $bedType->id }}').submit(); }" class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
              <form id="delete-bed-type-{{ $bedType->id }}" action="{{ route('admin.bed.type_delete', $bedType->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
              </form>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Create Bed Type Modal -->
  <div id="newBedTypeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Create New Bed Type</h3>
        <button id="closeBedTypeModalBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.bed.type_save') }}" method="POST" class="space-y-4 p-6" id="bedtype" onsubmit="formSubmit2(event, this, false, 'bedtype')">
        @csrf
        <div>
          <label for="bedType" class="block text-sm font-medium text-gray-700 mb-1">Bed Type <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="text" id="bedType" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter bed type">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500"></span></label>
          <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter description"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button id="cancelModalBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Save Bed Type
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Bed Type Modal -->
  <div id="editBedTypeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Edit Bed Type</h3>
        <button id="closeEditBedTypeModalBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.bed.type_update', '') }}" method="POST" class="space-y-4 p-6" id="editBedTypeForm" onsubmit="formSubmit2(event, this, false, 'editBedTypeForm')">
        @method('PUT')
        @csrf
        <div>
          <label for="editBedType" class="block text-sm font-medium text-gray-700 mb-1">Bed Type <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="text" id="editBedType" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter bed type">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
        </div>
        <div>
          <label for="editDescription" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
          <textarea id="editDescription" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter description"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button id="cancelEditModalBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Update Bed Type
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Modal toggle functions
    const openBtn = document.getElementById('openBedTypeModalBtn');
    const modal = document.getElementById('newBedTypeModal');
    const closeBtn = document.getElementById('closeBedTypeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');

    openBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    // Edit Bed Type Modal
    $(document).ready(function () {
      // Open Edit Bed Type Modal
      window.openEditBedTypeModal = function (bedTypeId) {
        const url = `{{ url('/admin/bed/type/edit/') }}/${bedTypeId}`;

        $.ajax({
          url: url,
          type: 'GET',
          success: function (data) {
            $('#editBedType').val(data.data.name);
            $('#editDescription').val(data.data.description);
            $('#editBedTypeModal').removeClass('hidden');
            $('#editBedTypeForm').attr('action', `{{ url('/admin/bed/type/update') }}/${bedTypeId}`);
          },
          error: function () {
            alert('Error fetching bed type data.');
          }
        });
      };

      // Close Edit Bed Type Modal
      $('#closeEditBedTypeModalBtn, #cancelEditModalBtn').on('click', function () {
        $('#editBedTypeModal').addClass('hidden');
      });
    });
  </script>
</body>

@endsection

<style>
  .card {
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  }
  .bg-main-color {
    background-color: #ec4899; /* This is the pink-500 color in Tailwind */
  }
  .text-main-color {
    color: #ec4899;
  }
  .focus\:ring-main-color:focus {
    --tw-ring-color: #ec4899;
  }
  .focus\:border-main-color:focus {
    border-color: #ec4899;
  }
</style>