@extends('layouts.admin')

@section('title', 'Doctor')

@section('content')
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-6 shadow-md rounded-lg ">
        <!-- Permission List -->
        <ul class="divide-y divide-gray-300">
            <li class="flex justify-between items-center py-3">
                <span>Users</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Users Store</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Cdr Overview</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Users Edit</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Users Update</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Partner-Ip-Update</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Support Ticket Store</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Support Faq Edit</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
            <li class="flex justify-between items-center py-3">
                <span>Dashboard Index</span>
                <input type="checkbox" class="w-5 h-5 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
            </li>
        </ul>
    </div>

</body>
{{-- <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header border-0 d-flex justify-content-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 40px;">
                    &#10004;
                </div>
            </div>
            <div class="modal-body">
                <h4 class="fw-bold">Success</h4>
                <p class="text-muted">Permissions assigned successfully</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#successModal">
    Show Success Modal
</button> --}}
@endsection