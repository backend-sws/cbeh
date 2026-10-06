@extends('layouts.app')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">New charges Category</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                 <button onclick="toggleModal(tru*-)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                            New charges Category
                        </button>
                {{-- <a href="#" class="px-4 py-2 bg-pink-300 text-white font-semibold rounded-md hover:bg-pink-600">
                    New IPD Patients
                </a> --}}
            </div>
        </div>
  <div class="bg-white shadow overflow-x-auto rounded-lg">
   
<body class="bg-white p-8">
  <div class="max-w-5xl mx-auto">
    <table class="min-w-full border-separate border-spacing-y-2 text-sm text-left">
      <thead>
        <tr class="text-gray-600">
          <th class="px-4 py-2">CHARGE CATEGORY ⬍</th>
          <th class="px-4 py-2">DESCRIPTION ⬍</th>
          <th class="px-4 py-2">CHARGE TYPE ⬍</th>
          <th class="px-4 py-2">ACTION</th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-gray-100">
          <td class="px-4 py-2">Ajcdjdsd</td>
          <td class="px-4 py-2">Ksdshsjb</td>
          <td class="px-4 py-2">
            <span class="bg-red-200 text-red-800 text-xs font-semibold px-3 py-1 rounded">Procedures</span>
          </td>
          <td class="px-4 py-2 flex space-x-2">
           <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-trash"></i>
                            </button>
          </td>
        </tr>
        <tr>
          <td class="px-4 py-2">HGsjknaat</td>
          <td class="px-4 py-2">Rahupasdjhda</td>
          <td class="px-4 py-2">
            <span class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded">Other</span>
          </td>
          <td class="px-4 py-2 flex space-x-2">
            <button class="text-black hover:text-blue-600"><i class="fas fa-edit">✏️</i></button>
            <button class="text-black hover:text-red-600"><i class="fas fa-trash">🗑️</i></button>
          </td>
        </tr>
        <tr class="bg-gray-100">
          <td class="px-4 py-2">Lndslnasd</td>
          <td class="px-4 py-2">N/A</td>
          <td class="px-4 py-2">
            <span class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded">Other</span>
          </td>
          <td class="px-4 py-2 flex space-x-2">
            <button class="text-black hover:text-blue-600"><i class="fas fa-edit">✏️</i></button>
            <button class="text-black hover:text-red-600"><i class="fas fa-trash">🗑️</i></button>
          </td>
        </tr>
      </tbody>
    </table>
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
        <h2 class="text-lg font-semibold">New Blood Issue</h2>
        <button onclick="toggleModal(false)" class="text-gray-500 hover:text-black text-xl">&times;</button>
      </div>

      <form class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Bed Type: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          <div>
            <label class="block text-sm font-medium">Bed Type: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          <div>
            <label class="block text-sm font-medium">Patient Name: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          <div>
            <label class="block text-sm font-medium">Doctor Name: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          <div>
            <label class="block text-sm font-medium">Blood Group: <span class="text-red-500">*</span></label>
            <input type="text" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
          <div>
            <label class="block text-sm font-medium">Amount: <span class="text-red-500">*</span></label>
            <input type="number" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter here..." />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium">Remarks: <span class="text-red-500">*</span></label>
          <textarea class="w-full border rounded px-3 py-2 mt-1" rows="3" placeholder="Enter here..."></textarea>
        </div>
        <div class="flex justify-end space-x-4 pt-4">
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



