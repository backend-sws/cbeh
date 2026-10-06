@extends('layouts.app')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">Item</h1>
            <div class="flex space-x-2">
            
                 <button onclick="toggleModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                           New Item category
                        </button>
               
            </div>
        </div>
  <div class="bg-white shadow overflow-x-auto rounded-lg">
 
  <div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100 text-left text-sm text-gray-500">
        <tr>
          <th class="px-6 py-3">PATIENT <i class="fa-solid fa-sort ml-1 text-xs"></i></th>
          <th class="px-6 py-3">ACTION</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
        <!-- Row 1 -->
        <tr class="bg-gray-50">
          <td class="px-6 py-4">
            <div class="font-medium">Arvind Kumar</div>
            <div class="text-sm text-gray-500">arvind2024@gmail.com</div>
          </td>
          <td class="px-6 py-4">
            <button class="text-gray-700 hover:text-green-500 rounded-md p-2">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="text-gray-700 hover:text-red-500 rounded-md p-2">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>

        <!-- Row 2 -->
        <tr>
          <td class="px-6 py-4">
            <div class="font-medium">Rahul Kumar</div>
            <div class="text-sm text-gray-500">rahul4569@gmail.com</div>
          </td>
          <td class="px-6 py-4">
            <button class="text-gray-700 hover:text-green-500 rounded-md p-2">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="text-gray-700 hover:text-red-500 rounded-md p-2">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>

        <!-- Row 3 -->
        <tr class="bg-gray-50">
          <td class="px-6 py-4">
            <div class="font-medium">Prusottam Pandit</div>
            <div class="text-sm text-gray-500">protabhja46@gmail.com</div>
          </td>
          <td class="px-6 py-4">
            <button class="text-gray-700 hover:text-green-500 rounded-md p-2">
              <i class="fa-solid fa-pen-to-square"></i>
            </button>
            <button class="text-gray-700 hover:text-red-500 rounded-md p-2">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>


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
        <h2 class="text-lg font-semibold">New Item Category</h2>
        <button onclick="toggleModal(false)" class="text-gray-500 hover:text-black text-xl">&times;</button>
      </div>

      <form class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Name: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          </div>
        <div class="flex justify-start space-x-4 pt-4">
          <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-800">Save</button>
          <button type="button" onclick="toggleModal(false)" class="bg-gray-300 text-black px-6 py-2 rounded hover:bg-gray-400">Cancel</button>
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



