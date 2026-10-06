@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header p-1">
                    <div class="flex justify-between items-center mb-1">
                        <div class="container mt-4 text-center">
                            <button onclick="openModal()" class="btn customborder border-main-color text-main-color px-28 py-2" style="">
                                Upload Add On
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

   <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
        <!-- Modal Header -->
        <h2 class="text-xl font-bold text-center">Upload AddOn</h2>

        <!-- Drag & Drop Box -->
        <div class="border-dashed border-2 border-gray-300 bg-gray-100 p-6 mt-2 text-center rounded-lg flex flex-col items-center justify-center">
            <div class="flex justify-center items-center w-16 h-16 rounded-full ">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#6B46C1" viewBox="0 0 256 256">
                    <path d="M160,40A88.09,88.09,0,0,0,81.29,88.67,64,64,0,1,0,72,216h88a88,88,0,0,0,0-176Zm0,160H72a48,48,0,0,1,0-96c1.1,0,2.2,0,3.29.11A88,88,0,0,0,72,128a8,8,0,0,0,16,0,72,72,0,1,1,72,72Zm37.66-93.66a8,8,0,0,1,0,11.32l-48,48a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L144,148.69l42.34-42.35A8,8,0,0,1,197.66,106.34Z"></path>
                </svg>
            </div>
            <p class="text-gray-500 mt-1">Drag & drop files or <span class="text-blue-600 cursor-pointer">Browse</span></p>
        </div>

        <!-- File Input -->
        <div class="mt-4">
            <p class="text-gray-500 text-sm">Uploading - 0/1 files</p>
            <input type="file" class="border w-full px-3 py-2 mt-2 rounded-lg">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-4">
            <button class="bg-purple-600 text-white px-6 py-2 rounded-lg">Upload</button>
            <button onclick="closeModal()" class="border px-6 py-2 rounded-lg">Cancel</button>
        </div>
    </div>
</div>


    <script>
        function openModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }
    </script>



@endsection
