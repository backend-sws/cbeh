@extends('layouts.admin')

@section('title', 'Doctor')

@section('content')
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <button class="text-main-color border px-4 py-2 rounded-lg hover:bg-main-color">
                + New Patient Smart Cards Template
            </button>
            <button class="text-main-color border px-4 py-2 rounded-lg hover:bg-main-color">
                &larr; Back
            </button>
        </div>
       <div class="overflow-x-auto">
 
    <div class="bg-main-light border-main-color border p-6 rounded-xl w-full max-w-3xl">
        <div class="flex justify-between items-start  p-4 rounded-xl ">
            <div class="text-gray-700 w-2/3">
                <p class="text-sm">C/569 India, Bihar, Patna-800032</p>
                <p class="text-sm">Boring Road --</p>
                <h2 class="font-bold text-lg mt-2">NAME</h2>
                <p class="text-xl font-semibold">ANYPERSON/JOHN B.</p>
                <h2 class="font-bold text-lg mt-2">DATE OF BIRTH</h2>
                <p class="text-xl font-semibold">01/11/2001</p>
                <div class="mt-4">
                    <p><strong>Email:</strong> subodhkumar123@gmail.com</p>
                    <p><strong>Phone:</strong> 7669325861</p>
                </div>
                <div class="mt-4">
                    <p><strong>Blood Group:</strong> A1+</p>
                    <p><strong>Address:</strong> M.g Nagar, Kankarbagh Patna</p>
                </div>
            </div>
            <div>
                <img src="https://via.placeholder.com/100x100" alt="QR Code" class="border border-gray-300 p-2 rounded-md">
                <p class="text-center text-sm mt-2">SMART CARD</p>
            </div>
        </div>
         </div>
        <div class="bg-white p-4 mt-6 rounded-xl shadow-md">
            <label class="block text-gray-700 font-semibold">Name:</label>
            <input type="text" class="border border-gray-300 rounded-md w-full p-2 mt-1" placeholder="Enter here...">
            <div class="flex flex-wrap gap-4 mt-4">
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">Phone</span>
    </div>
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">E-mail</span>
    </div>
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">Date Of Birth</span>
    </div>
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">Blood Group</span>
    </div>
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">Address</span>
    </div>
    <div class="flex items-center">
        <img src="/img/wright_icon.png" alt="Checked" class="w-5 h-5">
        <span class="ml-2">Patient Unique ID</span>
    </div>
</div>

            {{-- <div class="flex flex-wrap gap-4 mt-4">
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">Phone</span>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">E-mail</span>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">Date Of Birth</span>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">Blood Group</span>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">Address</span>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" checked class="text-main-color w-5 h-5">
                    <span class="ml-2">Patient Unique ID</span>
                </div>
            </div> --}}
            
            <div class="flex justify-between mt-6">
                <button class="px-6 py-2 bg-purple-light text-purple-color rounded-md font-semibold hover-purple-color">Save</button>
                <button class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold">Cancel</button>
            </div>
       
    </div>

</div>

    </div>
</body>

@endsection

