@extends('layouts.admin')

@section('title', 'Edit Blood Donor')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Edit Blood Donor</h2>

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

        <form action="{{ route('admin.blood-donor.update', $bloodDonor->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $bloodDonor->name) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>

            <!-- Age -->
            <div>
                <label class="block font-medium text-gray-700">Age</label>
                <input type="number" name="age" value="{{ old('age', $bloodDonor->age) }}" min="18"
                       class="w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>

            <!-- Gender -->
            <div>
                <label class="block font-medium text-gray-700">Gender</label>
                <select name="gender" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="Male" {{ $bloodDonor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $bloodDonor->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ $bloodDonor->gender == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Blood Group -->
            <div>
                <label class="block font-medium text-gray-700">Blood Group</label>
                <select name="blood_group" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                        <option value="{{ $group }}" {{ $bloodDonor->blood_group == $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Phone -->
            <div>
                <label class="block font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $bloodDonor->phone) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <!-- Address -->
            <div>
                <label class="block font-medium text-gray-700">Address</label>
                <textarea name="address" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('address', $bloodDonor->address) }}</textarea>
            </div>

            <!-- Last Donation Date -->
            <div>
                <label class="block font-medium text-gray-700">Last Donation Date</label>
                <input type="date" name="last_donation_date" value="{{ old('last_donation_date', $bloodDonor->last_donation_date) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            <!-- Notes -->
            <div>
                <label class="block font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('notes', $bloodDonor->notes) }}</textarea>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
                    Update Donor
                </button>
                <a href="{{ route('admin.blood-donor.index') }}"
                   class="ml-3 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
