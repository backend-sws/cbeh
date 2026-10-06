@extends('layouts.admin')

@section('title', 'Doctor')

@section('content')



<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-4">
    <div class="flex justify-between items-center mb-4">
        <div class="space-x-2">
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">Copy</button>
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">CSV</button>
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">Excel</button>
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">PDF</button>
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">Print</button>
            <button class="text-main-color border px-3 py-1 rounded hover-purple-color">Column Visibility</button>
        </div>
        <button class="text-main-color border px-4 py-2 rounded-lg hover-purple-color">
            &larr; Back
        </button>
    </div>
   {{-- <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6"> --}}
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Sr</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Role</th>
                    <th class="border border-gray-300 px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table Rows -->
                <tr class="odd:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">1</td>
                    <td class="border border-gray-300 px-4 py-2">Demo</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                    <button class="text-main-color p-2 border fa fa-edit hover-purple-color" onclick="openModal()"></button>
                    <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                </td>
                </tr>
                <tr class="odd:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">2</td>
                    <td class="border border-gray-300 px-4 py-2">Manager</td>
                   <td class="border border-gray-300 px-4 py-2 text-center">
                    <button class="text-main-color p-2 border fa fa-edit hover-purple-color" onclick="openModal()"></button>
                    <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                </td>
                </tr>
                <tr class="odd:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">3</td>
                    <td class="border border-gray-300 px-4 py-2">Super Admin</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                    <button class="text-main-color p-2 border fa fa-edit hover-purple-color" onclick="openModal()"></button>
                    <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                </td>
                </tr>
                <tr class="odd:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">4</td>
                    <td class="border border-gray-300 px-4 py-2">Admin Creater</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                    <button class="text-main-color p-2 border fa fa-edit hover-purple-color" onclick="openModal()"></button>
                    <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 text-gray-500 text-sm text-center">
            Showing 1 to 4 of 4 entries (filtered from 5 total entries)
        </div>
    
</div>

<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center border-b pb-2">
            <h2 class="text-lg font-semibold">User Role</h2>
            <button onclick="closeModal()" class="text-gray-500 hover-purple-color">&times;</button>
        </div>

        <div>
                    <label class="block text-gray-600 mb-2">Select Role</label>
                    <select class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option>Super Admin</option>
                        <option class="bg-pink-200">Manager</option>
                        <option>Demo</option>
                    </select>
                </div>

        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 text-main-color border rounded hover-purple-color">
              save
            </button>
        </div>
    </div>
</div>

<!-- JavaScript to Control Modal -->
<script>
    function openModal() {
        document.getElementById("editModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("editModal").classList.add("hidden");
    }
</script>

@endsection
