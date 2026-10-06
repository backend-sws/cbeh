@extends('layouts.admin')

@section('title', 'New Prescription')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">New Prescriptions</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                <a href="{{ url()->previous() }}">
                 <button  class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                      Back
                  </button>
                </a>
            </div>
        </div>

<body class="bg-gray-50 p-10">

  <!-- Patient Details Section -->
  <div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <div class="grid grid-cols-2 gap-6 mb-6">
      <div>
        <label class="block mb-1 font-semibold">Patient <span class="text-red-500">*</span></label>
        <select name="patient" class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">Doctor <span class="text-red-500">*</span></label>
        <select name="doctor" class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">Health Insurance <span class="text-red-500">*</span></label>
        <select name="health_insurance" class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">Low Income <span class="text-red-500">*</span></label>
        <select name='low_income' class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">Reference <span class="text-red-500">*</span></label>
        <select name="reference" class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
      <div class="flex items-center space-x-3">
        <label class="font-semibold">Status</label>
        <label class="inline-flex relative items-center cursor-pointer">
          <input type="checkbox" checked class="sr-only peer">
          <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-purple-700 after:content-[''] after:absolute after:bg-white after:rounded-full after:h-5 after:w-5 after:top-0.5 after:left-[2px] after:transition-all peer-checked:after:translate-x-full"></div>
        </label>
      </div>
    </div>
    <div class="flex justify-end space-x-4">
      <button class="bg-purple-700 text-white px-6 py-2 rounded-lg">Save</button>
      <button class="bg-gray-300 px-6 py-2 rounded-lg">Cancel</button>
    </div>
  </div>

  <!-- Medicines Section -->
  <div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <div class="flex justify-between mb-4">
      <h2 class="text-lg font-bold">Medicines</h2>
      <div class="space-x-3">
        <button class="bg-pink-300 text-white px-4 py-1 rounded">New Medicine</button>
        <button class="bg-pink-300 text-white px-4 py-1 rounded">Suggest Medicine</button>
      </div>
    </div>

    <div class="grid grid-cols-6 gap-4 mb-4">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
      <input type="text" class="border rounded p-2" placeholder="Enter here...">
    </div>

    <div class="flex justify-end space-x-4">
      <button class="bg-purple-700 text-white px-6 py-2 rounded-lg">Save</button>
      <button class="bg-gray-300 px-6 py-2 rounded-lg">Cancel</button>
    </div>
  </div>

  <!-- Physical Information Section -->
  <div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <h2 class="text-lg font-bold mb-4">Physical Information</h2>
    <div class="grid grid-cols-4 gap-4 mb-6">
      <input type="text" class="border rounded p-2" placeholder="High Blood Pressure *">
      <input type="text" class="border rounded p-2" placeholder="Food Allergies *">
      <input type="text" class="border rounded p-2" placeholder="Tendency Bleed *">
      <input type="text" class="border rounded p-2" placeholder="Heart Disease *">

      <input type="text" class="border rounded p-2" placeholder="Diabetic *">
      <input type="text" class="border rounded p-2" placeholder="Added At *">
      <input type="text" class="border rounded p-2" placeholder="Female Pregnancy *">
      <input type="text" class="border rounded p-2" placeholder="Breast Feeding *">

      <input type="text" class="border rounded p-2" placeholder="Current Medication *">
      <input type="text" class="border rounded p-2" placeholder="Surgery *">
      <input type="text" class="border rounded p-2" placeholder="Accident *">
      <input type="text" class="border rounded p-2" placeholder="Others *">

      <input type="text" class="border rounded p-2" placeholder="Pulse Rate *">
      <input type="text" class="border rounded p-2" placeholder="Temperature *">
    </div>

    <textarea class="border rounded p-2 w-full mb-6" rows="3" placeholder="Problem Description *"></textarea>
  </div>

  <!-- Text, Advice, Next Visit Section -->
  <div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <textarea class="border rounded p-2 w-full mb-4" rows="3" placeholder="Text *"></textarea>
    <textarea class="border rounded p-2 w-full mb-4" rows="3" placeholder="Advice *"></textarea>

    <div class="grid grid-cols-2 gap-4 mb-6">
     <input type="text" class="border rounded p-2" placeholder="Next Visit *">
     <div>
        <label class="block mb-1 font-semibold"> <span class="text-red-500">*</span></label>
        <select class="w-full border rounded p-2">
          <option>Enter here...</option>
        </select>
      </div>
    </div>

    <div class="flex justify-end space-x-4">
      <button class="bg-purple-700 text-white px-6 py-2 rounded-lg">Save</button>
      <button class="bg-gray-300 px-6 py-2 rounded-lg">Cancel</button>
    </div>
  </div>

</body>

  
</body>



@endsection



