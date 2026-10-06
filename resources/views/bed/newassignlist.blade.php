@extends('layouts.admin')

@section('title', 'Bed Assignments')

@section('content')
<body class="bg-gray-50">
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="card bg-white p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Bed Assignments</h1>
      <div class="flex items-center space-x-3 mt-4 sm:mt-0">
        <div class="relative">
          <input type="text" id="searchInput" placeholder="Search assignments..." 
                 class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        {{-- <a href="{{ route('admin.bed.newassign') }}" class="flex items-center bg-main-color text-white px-4 py-2 rounded-md hover:bg-pink-700 transition-colors duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          New Assignment
        </a> --}}
      </div>
    </div>

    <!-- Card Container -->
    <div class="card bg-white rounded-xl shadow-sm overflow-hidden">
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPD No</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discharge Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody id="assignTableBody" class="bg-white divide-y divide-gray-200">
            @foreach($assigns as $assign)
            <tr class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm bg-main-color text-white bg-opacity-50  rounded-full px-3 py-1 font-semibold text-center">
                  {{ $assign->ipd_no }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $assign->patient ? $assign->patient->name : 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $assign->bed ? $assign->bed->name : 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $assign->assigned_date }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $assign->discharged_date ?? 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($assign->status == 'assigned')
                <span class="px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Assigned</span>
                @elseif($assign->status == 'discharged')
                <span class="px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Discharged</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <div class="flex space-x-2">
                  <a href="{{ route('admin.bed.assign.edit', $assign->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </a>
                  <!-- <form action="{{ route('admin.bed.assign.delete', $assign->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this assignment?')">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </form> -->
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- New Assignment Modal -->
  <div id="newBedModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl transform transition-all">
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">New Bed Assignment</h3>
        <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="p-6">
        <form class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Case (Select2) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Case: <span class="text-red-500">*</span>
              </label>
              <select id="caseSelect" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                <option value="">Select Case</option>
                <option value="case1">Case 1</option>
                <option value="case2">Case 2</option>
                <option value="case3">Case 3</option>
              </select>
            </div>

            <!-- IPD Patient -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                IPD Patient: <span class="text-red-500">*</span>
              </label>
              <input type="text" placeholder="Enter here..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" />
            </div>

            <!-- Bed (Select2) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Bed: <span class="text-red-500">*</span>
              </label>
              <select id="bedSelect" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color">
                <option value="">Select Bed</option>
                <option value="bed1">Bed 1</option>
                <option value="bed2">Bed 2</option>
                <option value="bed3">Bed 3</option>
              </select>
            </div>

            <!-- Assign Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Assign Date: <span class="text-red-500">*</span>
              </label>
              <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color" />
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Description: <span class="text-red-500">*</span>
            </label>
            <textarea rows="5" placeholder="Description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-main-color focus:border-main-color"></textarea>
          </div>

          <!-- Status and Buttons -->
          <div class="flex items-center justify-between mt-4">
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">STATUS</label>
              <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" checked>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-main-color peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-main-color"></div>
              </label>
            </div>

            <div class="flex space-x-3">
              <button type="button" id="closeModalBtnBottom" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main-color transition-colors duration-200">
                Save
              </button>
            </div>
          </div>
        </form>
      </div>
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

    if (openModalBtn) {
      openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
      });
    }

    if (closeModalBtn) {
      closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
      });
    }

    if (closeModalBtnBottom) {
      closeModalBtnBottom.addEventListener('click', () => {
        modal.classList.add('hidden');
      });
    }

    // Search functionality
    $(document).ready(function() {
      $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#assignTableBody tr').filter(function() {
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