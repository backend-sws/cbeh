@extends('layouts.admin')

@section('title', 'Blood')

@section('content')

<body class="bg-gray-100 p-6">

    <!-- Patient Info -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block mb-1 font-medium">Patient</label>
                <select id="patient-select" class="select2 w-full border rounded px-3 py-2">
                    <option value="">Select Patient</option>
                    <option value="1">John Doe</option>
                    <option value="2">Jane Smith</option>
                    <option value="3">Alice Johnson</option>
                </select>
            </div>

            <!-- Doctor Dropdown -->
            <div>
                <label class="block mb-1 font-medium">Doctor <span class="text-red-500">*</span></label>
                <select id="doctor-select" class="select2 w-full border rounded px-3 py-2">
                    <option value="">Select Doctor</option>
                    <option value="1">Dr. Adams</option>
                    <option value="2">Dr. Carter</option>
                    <option value="3">Dr. Kumar</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Health Insurance <span class="text-red-500">*</span></label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Low Income <span class="text-red-500">*</span></label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Reference <span class="text-red-500">*</span></label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div class="flex items-center space-x-2 mt-6">
                <label class="font-medium">Status</label>
                <input type="checkbox" class="toggle" checked>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <button class="bg-purple-600 text-white px-4 py-2 rounded">Save</button>
            <button class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
        </div>
    </div>

    <!-- Medicines -->

    <div class="bg-white p-6 rounded shadow mb-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Medicines</h2>
            <div class="space-x-2">
                <button id="openModalBtn" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">New Medicine</button>
                {{-- <button class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">New Medicine</button> --}}
                <button class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">Suggest Medicines</button>
            </div>
        </div>

        <!-- Table Header -->
        <div class="grid grid-cols-6 gap-2 bg-gray-100 p-2 font-semibold text-sm mb-2">
            <span>MEDICINES<span class="text-red-500">*</span></span>
            <span>DOSAGE</span>
            <span>DOSE DURATION<span class="text-red-500">*</span></span>
            <span>TIME<span class="text-red-500">*</span></span>
            <span>DOSE INTERVAL<span class="text-red-500">*</span></span>
            <div class="flex justify-between items-center">
                <span>COMMENT</span>
                <button onclick="addMedicineRow()" class="bg-purple-700 text-white text-xs px-3 py-1 rounded hover:bg-purple-800">ADD</button>
            </div>
        </div>

        <!-- Dynamic Input Rows -->
        <div id="medicineRows"></div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-800">Save</button>
            <button class="bg-gray-300 text-black px-6 py-2 rounded hover:bg-gray-400">Cancel</button>
        </div>
    </div>





    <!-- Physical Information -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="font-semibold mb-4">Physical Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block mb-1 font-medium">High Blood Pressure <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Food Allergies <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Tendency Bleed <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Heart Disease <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Diabetic <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Added At <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Female Pregnancy <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Breast Feeding <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Current Medication <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Surgery <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Accident <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Others <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Pulse Rate <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            <div>
                <label class="block mb-1 font-medium">Temperature <span class="text-red-500">*</span>:</label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Problem Description <span class="text-red-500">*</span>:</label>
            <textarea class="w-full border rounded px-3 py-2" placeholder="Enter here..."></textarea>
        </div>
    </div>


    <!-- Final Notes -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="mb-4">
            <label class="block mb-1 font-medium">Tset : <span class="text-red-500">*</span></label>
            <textarea class="w-full border rounded px-3 py-2" placeholder="Enter here..."></textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-medium">Advice : <span class="text-red-500">*</span></label>
            <textarea class="w-full border rounded px-3 py-2" placeholder="Enter here..."></textarea>
        </div>
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label class="block mb-1 font-medium">Next Visit : <span class="text-red-500">*</span></label>
                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Enter here...">
            </div>
            {{-- <select class="w-full border rounded px-3 py-2">
                <option>Select an option</option>
                <!-- Add actual options here -->
            </select> --}}
        </div>
        <div class="flex justify-end space-x-4">
            <button class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-800">Save</button>
            <button class="bg-gray-300 text-black px-6 py-2 rounded hover:bg-gray-400">Cancel</button>
        </div>
    </div>


</body>




<div id="medicineModal" class="fixed inset-0 flex items-center justify-center hidden modal-bg z-50 bg-black bg-opacity-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md md:max-w-3xl p-6 md:p-8 overflow-auto max-h-[90vh]">
    <div class="flex justify-between items-center mb-6 border-b pb-3">
      <h3 class="text-xl md:text-2xl font-bold text-gray-800">New Medicine</h3>
      <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700 text-3xl font-semibold transition-colors">&times;</button>
    </div>

    <form class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Medicine <span class="text-red-500">*</span></label>
          <input type="text" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Medicine" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
          <select class="select2 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="" disabled selected>Select Category</option>
            <option value="antibiotic">Antibiotic</option>
            <option value="painkiller">Painkiller</option>
            <option value="vitamin">Vitamin</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
          <select class="select2 w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="" disabled selected>Select Brand</option>
            <option value="brandA">Brand A</option>
            <option value="brandB">Brand B</option>
            <option value="brandC">Brand C</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Salt Composition <span class="text-red-500">*</span></label>
          <input type="text" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Salt Composition" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Buying Price <span class="text-red-500">*</span></label>
          <input type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Buying Price" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Selling Price <span class="text-red-500">*</span></label>
          <input type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Selling Price" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Side Effects</label>
        <textarea rows="3" class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Side Effects"></textarea>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
        <textarea rows="3" class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Description"></textarea>
      </div>

      <div class="flex justify-end space-x-4 mt-8">
        <button id="cancelBtn" type="button" class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">Cancel</button>
        <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Save</button>
      </div>
    </form>
  </div>
</div>



<script>
    function initializeSelect2() {
        $('.select2').select2({
            width: '100%'
            , dropdownParent: $('body')
        });
    }

    function addMedicineRow() {
        const container = document.getElementById('medicineRows');
        const row = document.createElement('div');
        row.className = "grid grid-cols-6 gap-2 mb-2 items-center";
        row.innerHTML = `
        <select class="select2 border rounded px-2 py-1 w-full">
          <option value="">Select Medicine</option>
          <option>Paracetamol</option>
          <option>Ibuprofen</option>
          <option>Amoxicillin</option>
        </select>
        <input type="text" class="border rounded px-2 py-1" placeholder="Dos">
        <select class="border rounded px-2 py-1">
          <option>Only one day</option>
          <option>3 days</option>
          <option>7 days</option>
        </select>
        <select class="border rounded px-2 py-1">
          <option>After Meal</option>
          <option>Before Meal</option>
        </select>
        <select class="border rounded px-2 py-1">
          <option>Daily morning</option>
          <option>Every 6 hours</option>
        </select>
        <div class="flex items-center space-x-2">
          <textarea rows="1" class="border rounded px-2 py-1 w-full resize-none" placeholder="Comment"></textarea>
          <button class="text-gray-700 hover:text-red-600" onclick="removeRow(this)">
            <i class="fas fa-trash"></i>
          </button>
        </div>`;

        container.appendChild(row);
        initializeSelect2(); // Re-initialize Select2 for new element
    }

    function removeRow(button) {
        button.closest('.grid').remove();
    }

    // Initial Select2 setup for any pre-existing elements
    $(document).ready(() => {
        initializeSelect2();
    });

</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
            , placeholder: 'Select an option'
            , allowClear: true
        });

        $('#openModalBtn').click(function() {
            $('#medicineModal').removeClass('hidden');
            $('.select2').select2(); // Re-initialize in case modal content was hidden
        });

        $('#closeModalBtn, #cancelBtn').click(function() {
            $('#medicineModal').addClass('hidden');
        });
    });

</script>
@endsection
