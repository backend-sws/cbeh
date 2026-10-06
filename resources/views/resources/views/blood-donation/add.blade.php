@extends('layouts.admin')

@section('title', 'Add Blood Donation')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Add Blood Donation</h2>

    <form action="{{ route('admin.blood-donation.store') }}" method="POST">
        @csrf

        <!-- Donor -->
        <div class="mb-4">
            <label for="donor_id" class="block text-sm font-medium text-gray-700">Select Donor</label>
            <select name="donor_id" id="donor_id" class="mt-1 block w-full border rounded p-2" required>
                <option value="">-- Select Donor --</option>
                @foreach($donors as $donor)
                    <option value="{{ $donor->id }}">{{ $donor->name }} ({{ $donor->blood_group }})</option>
                @endforeach
            </select>
            @error('donor_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Blood Group -->
        <div class="mb-4">
            <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood Group</label>
            <input type="text" name="blood_group" id="blood_group" value="{{ old('blood_group') }}" class="mt-1 block w-full border rounded p-2" required>
            @error('blood_group') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Bags -->
        <div class="mb-4">
            <label for="bags" class="block text-sm font-medium text-gray-700">No. of Bags</label>
            <input type="number" name="bags" id="bags" value="{{ old('bags') }}" min="1" class="mt-1 block w-full border rounded p-2" required>
            @error('bags') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Donation Date -->
        <div class="mb-4">
            <label for="donation_date" class="block text-sm font-medium text-gray-700">Donation Date</label>
            <input type="date" name="donation_date" id="donation_date" value="{{ old('donation_date') }}" class="mt-1 block w-full border rounded p-2" required>
            @error('donation_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Notes -->
        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" class="mt-1 block w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Donation</button>
        </div>
    </form>
</div>
@endsection
