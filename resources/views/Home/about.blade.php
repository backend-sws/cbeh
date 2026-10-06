@extends('layouts.app')

@section('title', 'Blood')

@section('content')

<div class="bg-gray-50 p-6 font-sans">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">About Us Details</h1>
    </div>

    <div class="bg-white p-6 rounded-md shadow-md">
        <h2 class="text-xl font-semibold mb-4">About Us Details</h2>

        <!-- No backend form -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">
                Title: <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter here..." />
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                Description: <span class="text-red-500">*</span>
            </label>
            <textarea id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter here..."></textarea>
        </div>

        <div class="mb-4">
            <label for="mission" class="block text-gray-700 text-sm font-bold mb-2">
                Mission: <span class="text-red-500">*</span>
            </label>
            <textarea id="mission" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter here..."></textarea>
        </div>

        <!-- Image upload with preview -->
        <div class="mb-4">
            <label for="upload-image" class="block text-gray-700 text-sm font-bold mb-2">
                Upload Image:
            </label>
            <div class="relative w-24 h-24 border rounded-md flex items-center justify-center bg-gray-200 overflow-hidden">
                <img id="image-preview" class="hidden w-full h-full object-cover" />
                <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10 text-gray-500" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25V12a.75.75 0 001.5 0V6.75a3.75 3.75 0 117.5 0V12a.75.75 0 001.5 0V6.75A5.25 5.25 0 0012 1.5zm-2.25 9a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zM6.75 15.75a.75.75 0 000 1.5h10.5a.75.75 0 000-1.5H6.75zM3 18a.75.75 0 000 1.5h18a.75.75 0 000-1.5H3z" clip-rule="evenodd" />
                </svg>
                <input type="file" id="upload-image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)" />
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                Save
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline" type="button">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Image preview script -->
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const icon = document.getElementById('upload-icon');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection
<div class="p-6">
  <h2 class="text-xl font-semibold mb-4">Appointment</h2>
  <div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">Title : <span class="text-red-500">*</span></label>
    <input type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter here...">
  </div>
  <div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">Description: <span class="text-red-500">*</span></label>
    <textarea id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter here..."></textarea>
  </div>
  <div class="flex justify-end">
    <button type="button" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Save
    </button>
    <button type="button" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Cancel
    </button>
  </div>
</div>