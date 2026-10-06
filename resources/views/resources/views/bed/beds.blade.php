@extends('layouts.admin')

@section('title', 'Beds Management')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="card bg-white p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Beds Management</h1>
      <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <div class="relative">
          <input type="text" id="searchInput" placeholder="Search beds..." 
                 class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <button id="openModalBtn" class="flex items-center bg-main-color text-white px-4 py-2 rounded-md hover:bg-pink-700 transition-colors duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          New Bed
        </button>
      </div>
    </div>

    <!-- Card Container -->
    <div class="card bg-white rounded-xl shadow-sm overflow-hidden">
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BED CODE</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BED</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BED TYPE</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CHARGE</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AVAILABLE</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ACTIONS</th>
            </tr>
          </thead>
          <tbody id="bedTableBody" class="bg-white divide-y divide-gray-200">
            @foreach ($beds as $bed)
            <tr class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm bg-main-color bg-opacity-50 text-white rounded-full px-3 py-1 font-semibold text-center">
                  {{ $bed->code }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bed->name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $bed->bedType ? $bed->bedType->name : '' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                Rs {{ number_format($bed->charge, 2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if ($bed->bed_assigns->count() > 0 && $bed->bed_assigns->last()->status == 'assigned')
                  <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </div>
                @else
                  <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="flex space-x-2">
                  <button onclick="openEditBedModal({{ $bed->id }})" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this bed?')) { document.getElementById('delete-bed-{{ $bed->id }}').submit(); }" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                  <form id="delete-bed-{{ $bed->id }}" action="{{ route('admin.bed.delete', $bed->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Create Bed Modal -->
  <div id="newBedModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Create New Bed</h3>
        <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="{{ route('admin.bed.store') }}" method="POST" class="space-y-4 p-6" id="addbed" onsubmit="formSubmit2(event, this, false, 'addbed')">
        @csrf
        <div>
          <label for="bed" class="block text-sm font-medium text-gray-700 mb-1">Bed <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="text" id="bed" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter bed name">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
        </div>
        <div>
          <label for="bedType" class="block text-sm font-medium text-gray-700 mb-1">Bed Type <span class="text-red-500">*</span></label>
          <div class="relative">
            <select id="bedType" name="bed_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color appearance-none">
              <option value="">Select bed type</option>
              @foreach ($bedTypes as $bedType)
                <option value="{{ $bedType->id }}">{{ $bedType->name }}</option>
              @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>
        <div>
          <label for="charge" class="block text-sm font-medium text-gray-700 mb-1">Charge <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="text" id="charge" name="charge" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter charge amount in rupees">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <span class="text-gray-500"></span>
            </div>
          </div>
        </div>
        <div>
          <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
          <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" placeholder="Enter any notes"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button id="closeModalBtnBottom" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Save Bed
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Bed Modal -->
  <div id="editBedModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Edit Bed</h3>
        <button id="closeEditModalBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form action="" method="POST" class="space-y-4 p-6" id="editBedForm" onsubmit="formSubmit2(event, this, false, 'editBedForm')">
        @csrf
        <div>
          <label for="editBedName" class="block text-sm font-medium text-gray-700 mb-1">Bed Name <span class="text-red-500">*</span></label>
          <input type="text" id="editBedName" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
        </div>
        <div>
          <label for="editBedType" class="block text-sm font-medium text-gray-700 mb-1">Bed Type <span class="text-red-500">*</span></label>
          <select id="editBedType" name="bedtype_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
            @foreach ($bedTypes as $bedType)
              <option value="{{ $bedType->id }}">{{ $bedType->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label for="editBedCharge" class="block text-sm font-medium text-gray-700 mb-1">Charge <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="text" id="editBedCharge" name="charge" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              {{-- <span class="text-gray-500">Rs</span> --}}
            </div>
          </div>
        </div>
        <div>
          <label for="editBedDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea id="editBedDescription" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"></textarea>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
          <button id="closeEditModalBtnBottom" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
            Update Bed
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Modal toggle functions
    const openModalBtn = document.getElementById('openModalBtn');
    const modal = document.getElementById('newBedModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModalBtnBottom = document.getElementById('closeModalBtnBottom');

    openModalBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    closeModalBtnBottom.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    // Edit Bed Modal
    $(document).ready(function() {
      // Open Edit Modal
      window.openEditBedModal = function(bedId) {
        const url = `{{ url('/admin/bed/edit/') }}/${bedId}`;

        $.ajax({
          url: url,
          type: 'GET',
          success: function(data) {
            const bed = data.data;
            $('#editBedName').val(bed.name);
            $('#editBedType').val(bed.bedtype_id);
            $('#editBedCharge').val(bed.charge);
            $('#editBedDescription').val(bed.description);
            $('#editBedForm').attr('action', `{{ url('/admin/bed/update') }}/${bedId}`);
            
            // Show the modal
            $('#editBedModal').removeClass('hidden');
          },
          error: function() {
            alert('Error fetching bed data.');
          }
        });
      };

      // Close Edit Modal
      $('#closeEditModalBtn, #closeEditModalBtnBottom').on('click', function() {
        $('#editBedModal').addClass('hidden');
      });

      // Search functionality
      $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#bedTableBody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });
    });
  </script>

  <style>
    .card {
      border-radius: 0.75rem;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .bg-main-color {
      background-color: #ec4899; /* Pink-500 */
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
</body>
@endsection