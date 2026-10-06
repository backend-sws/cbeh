@extends('layouts.admin')

@section('title', 'Edit Patient Case')

@section('content')
<div class="p-6 bg-gray-50 font-sans">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Patient Case</h1>

    <div class="max-w-6xl mx-auto p-6 bg-white rounded-md shadow">
        <form action="{{ route('patient-cases.update', $patientCase->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            @method('PUT')

            <!-- Case Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Case Date</label>
                <input type="date" name="date" value="{{ old('date', $patientCase->date) }}" class="w-full border border-gray-300 rounded-md p-2">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $patientCase->phone) }}" class="w-full border border-gray-300 rounded-md p-2">
            </div>

            <!-- Fee -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee</label>
                <input type="number" name="fee" value="{{ old('fee', $patientCase->fee) }}" class="w-full border border-gray-300 rounded-md p-2">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-md p-2">{{ old('description', $patientCase->description) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex justify-end space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow">Update</button>
                <a href="{{ route('patient-cases.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md shadow">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
