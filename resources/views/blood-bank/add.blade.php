@extends('layouts.admin')

@section('title', 'Add Blood Stock')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Add Blood Stock</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.blood-bank.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Blood Group -->
            <div>
                <label for="blood_group" class="block font-medium text-gray-700">Blood Group</label>
                <select name="blood_group" id="blood_group" class="w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="">-- Select Blood Group --</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>

            <!-- Remaining Bags -->
            <div>
                <label for="remaining_bags" class="block font-medium text-gray-700">Remaining Bags</label>
                <input type="number" name="remaining_bags" id="remaining_bags" min="0"
                       class="w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block font-medium text-gray-700">Notes (optional)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm"></textarea>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('admin.blood-bank.index') }}"
                   class="ml-3 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
