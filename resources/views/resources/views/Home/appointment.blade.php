@extends('layouts.app')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800">Appointment</h1>
        <div class="flex space-x-2">

        </div>
    </div>
    <div class="p-6">
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

    @endsection
