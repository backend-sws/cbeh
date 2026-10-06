@extends('layouts.admin')

@section('title', isset($bloodIssue) ? 'Edit Blood Issue' : 'New Blood Issue')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-gray-700 text-3xl font-medium">
                {{ isset($bloodIssue) ? 'Edit Blood Issue' : 'New Blood Issue' }}
            </h3>
        </div>
        <div>
            <a href="{{ route('admin.blood-issue.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to List
            </a>
        </div>
    </div>

    <div class="mt-8">
        <form action="{{ isset($bloodIssue) ? route('admin.blood-issue.update', $bloodIssue->id) : route('admin.blood-issue.store') }}"
            method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @if(isset($bloodIssue))
            @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient Selection -->
                <!-- Patient Selection -->
                <!-- Patient -->


                <!-- Doctor -->


                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="patient_name">
                        Patient *
                    </label>
                    <select name="patient_name" class="w-full border rounded p-2" required>
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->name }}">
                            {{ $patient->name }} ({{ $patient->email }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Selection -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="doctor_name">
                        Doctor *
                    </label>
                    <select name="doctor_name" class="w-full border rounded p-2" required>
                        <option value="">-- Select Doctor --</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->name }}">
                            Dr. {{ $doctor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- Blood Group -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="blood_group">
                        Blood Group *
                    </label>
                    <select name="blood_group" id="blood_group" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Blood Group</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                        <option value="{{ $group }}"
                            {{ (isset($bloodIssue) && $bloodIssue->blood_group == $group) || old('blood_group') == $group ? 'selected' : '' }}>
                            {{ $group }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Number of Bags -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="bags">
                        Number of Bags *
                    </label>
                    <input type="number" name="bags" id="bags" required min="1"
                        value="{{ isset($bloodIssue) ? $bloodIssue->bags : old('bags') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Issue Date -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="issue_date">
                        Issue Date *
                    </label>
                    <input type="date" name="issue_date" id="issue_date" required
                        value="{{ isset($bloodIssue) ? $bloodIssue->issue_date->format('Y-m-d') : old('issue_date', date('Y-m-d')) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2">₹</span>
                        <input type="number" name="amount" id="amount" required step="0.01" min="0"
                            value="{{ isset($bloodIssue) ? $bloodIssue->amount : old('amount') }}"
                            class="shadow appearance-none border rounded w-full py-2 pl-8 pr-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ isset($bloodIssue) ? $bloodIssue->notes : old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    {{ isset($bloodIssue) ? 'Update Issue' : 'Create Issue' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bloodGroupSelect = document.getElementById('blood_group');
        const bagsInput = document.getElementById('bags');
        const amountInput = document.getElementById('amount');

        // Calculate amount based on bags
        function calculateAmount() {
            const bags = parseInt(bagsInput.value) || 0;
            const amountPerBag = 1000; // Set your default amount per bag
            amountInput.value = (bags * amountPerBag).toFixed(2);
        }

        bagsInput.addEventListener('input', calculateAmount);

        // Initial calculation
        if (!amountInput.value) {
            calculateAmount();
        }
    });
</script>
@endpush

@endsection