@extends('layouts.app')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">Charges</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                 <button onclick="toggleModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                            New Doctor OPD Charge 
                        </button>
                {{-- <a href="#" class="px-4 py-2 bg-pink-300 text-white font-semibold rounded-md hover:bg-pink-600">
                    New IPD Patients
                </a> --}}
            </div>
        </div>
  <div class="bg-white shadow overflow-x-auto rounded-lg">
   
<body class="bg-white p-8">
  <div class="max-w-5xl mx-auto">
   
<body class="bg-gray-100 p-8">
  <div class="max-w-6xl mx-auto bg-white shadow rounded overflow-x-auto">
   <table class="min-w-full text-sm text-left border-separate border-spacing-y-2">
      <thead class="text-gray-500 uppercase text-xs bg-white">
        <tr>
          <th class="px-4 py-3">Patient </th>
          <th class="px-4 py-3">Standard Charge </th>
          <th class="px-4 py-3">Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Row 1 -->
        <tr class="bg-gray-100">
          <td class="px-4 py-3">
            <div class="text-sm font-medium text-gray-900">Arvind Kumar</div>
            <div class="text-sm text-gray-500">arvind2024@gmail.com</div>
          </td>
          <td class="px-4 py-3 text-purple-700 font-medium">AR$ 100.00.00</td>
          <td class="px-4 py-3 flex space-x-2">
            <button class="text-gray-700 hover:text-green-500 p-2 rounded-md border border-main-color">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="text-gray-700 hover:text-red-500 p-2 rounded-md border border-main-color">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>

        <!-- Row 2 -->
        <tr class="bg-white">
          <td class="px-4 py-3">
            <div class="text-sm font-medium text-gray-900">Rahul Kumar</div>
            <div class="text-sm text-gray-500">rahul4569@gmail.com</div>
          </td>
          <td class="px-4 py-3 text-purple-700 font-medium">AR$ 1,000,000</td>
          <td class="px-4 py-3 flex space-x-2">
            <button class="text-gray-700 hover:text-green-500 p-2 rounded-md border border-main-color">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="text-gray-700 hover:text-red-500 p-2 rounded-md border border-main-color">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

  </div>
</body>


  </div>
   <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 mb-8 text-gray-600 text-sm">
            <div class="flex space-x-1">
                <button class="px-3 py-1 bg-pink-500 text-white rounded">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">4</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">5</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">6</button>
            </div>
            <div>
                <span>Show</span>
                <select class="ml-2 border border-gray-300 rounded p-1">
                    <option>16</option>
                    <option>32</option>
                    <option>64</option>
                </select>
            </div>
            <div>
                <span>Showing 1 to 10 of 3000</span>
            </div>
        </div>
        <br>
</body>

<!-- Modal -->
  <div id="bloodIssueModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">New Doctor OPD Charge</h2>
        <button onclick="toggleModal(false)" class="text-gray-500 hover:text-black text-xl">&times;</button>
      </div>

       {{-- <h2 class="text-xl font-semibold mb-4"></h2> --}}

      <!-- Form -->
      <form class="space-y-4">
        <!-- Doctor Select -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Doctor:</label>
          <select class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option>Select Doctor...</option>
            <!-- Add options dynamically -->
          </select>
        </div>

        <!-- Standard Charge -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Standard Charge:</label>
          <input type="text" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Enter charge..." />
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-start space-x-3 pt-2">
          <button type="submit" class="bg-indigo-900 text-white px-4 py-2 rounded hover:bg-indigo-800">Save</button>
        <button type="button" onclick="toggleModal(false)" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>

          {{-- <button type="button" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</button> --}}
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Toggle Script -->
  <script>
    function toggleModal(show) {
      const modal = document.getElementById('bloodIssueModal');
      if (show) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      } else {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  </script>
@endsection



