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
    <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-4 py-2">ID</th>
                <th class="border border-gray-300 px-4 py-2">Label</th>
                <th class="border border-gray-300 px-4 py-2">Access Key</th>
                <th class="border border-gray-300 px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                ['id' => 1, 'label' => 'Dashboard', 'key' => 'Dashboard'],
                ['id' => 2, 'label' => 'Calls', 'key' => 'Calls'],
                ['id' => 3, 'label' => 'Users List', 'key' => 'Users List'],
                ['id' => 4, 'label' => '*Demo', 'key' => 'Demo'],
                ['id' => 5, 'label' => 'Roles', 'key' => 'Roles'],
                ['id' => 6, 'label' => '*Roles.Index', 'key' => 'Roles.Index'],
                ['id' => 7, 'label' => '*Roles.Permission', 'key' => 'Roles.Permission'],
                ['id' => 8, 'label' => '*Roles.Role_Permission', 'key' => 'Roles.Role_Permission'],
                ['id' => 9, 'label' => '*Role_Permission', 'key' => 'Role_Permission'],
            ] as $item)
            <tr class="odd:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $item['id'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item['label'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $item['key'] }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <button class="text-main-color p-2 border fa fa-edit hover-purple-color" onclick="openModal()"></button>
                    <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center border-b pb-2">
            <h2 class="text-lg font-semibold">Create Permission</h2>
            <button onclick="closeModal()" class="text-gray-500 hover-purple-color">&times;</button>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium">Label</label>
            <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">

            <label class="block text-sm font-medium mt-3">Access Key</label>
            <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">

            <label class="block text-sm font-medium mt-3">Parent</label>
            <select class="w-full p-2 border rounded">
                <option>Select Menu Type...</option>
            </select>
        </div>

        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 text-main-color border rounded hover-purple-color">
                Create Permission
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
