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
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Full Name</th>
                <th class="px-4 py-2">Phone</th>
                <th class="px-4 py-2">Date Of Birth</th>
                <th class="px-4 py-2">Blood Group</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Patient Unique ID</th>
                <th class="px-4 py-2">E-mail</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white text-center">
                <td class="px-4 py-2">Arvind Kumar</td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/wright_icon.png" alt="Check Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <button class="fa fa-edit border p-1"></button>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
            </tr>
            <tr class="bg-gray-100 text-center">
                <td class="px-4 py-2">Rahul Kumar</td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <img src="/img/cut_icon.png" alt="Cross Icon" class="mx-auto">
                </td>
                <td class="px-4 py-2">
                    <button class="fa fa-edit border p-1"></button>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

    </div>
</body>

@endsection