@extends('layouts.admin')

@section('title', 'New Patient Case')

@section('content')
<div class="p-6 bg-gray-50 font-sans">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">New Patient Case</h1>
        <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg" onclick="window.location.href='{{ route('patient-cases.index') }}'">
            Back
        </button>
    </div>

    <div class="max-w-6xl mx-auto p-6 bg-white rounded-md shadow">
        <form action="{{ route('patient-cases.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6" id="caseForm" onsubmit="formSubmit2(event, this, false, 'caseForm')">
            @csrf

            <!-- Doctor Dropdown -->
            <div x-data="dropdown({{ json_encode($doctors) }})" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                <input type="hidden" name="doctor_id" :value="selected">
                <button type="button" @click="open = !open" class="w-full bg-white border border-gray-300 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none">
                    <span x-text="getLabel(selected) || 'Select Doctor'"></span>
                    <svg class="w-4 h-4 float-right mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
                    <input type="text" x-model="search" placeholder="Search..." class="w-full border-b px-3 py-2 text-sm focus:outline-none">
                    <ul class="max-h-40 overflow-y-auto text-sm">
                        <template x-for="option in filteredOptions" :key="option.id">
                            <li @click="select(option.id)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" x-text="option.label"></li>
                        </template>
                        <li x-show="filteredOptions.length === 0" class="px-4 py-2 text-gray-400">No results</li>
                    </ul>
                </div>
            </div>

            <!-- Patient Dropdown -->
            <div x-data="dropdown({{ json_encode($patients) }})" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                <input type="hidden" name="patient_id" :value="selected">
                <button type="button" @click="open = !open" class="w-full bg-white border border-gray-300 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none">
                    <span x-text="getLabel(selected) || 'Select Patient'"></span>
                    <svg class="w-4 h-4 float-right mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
                    <input type="text" x-model="search" placeholder="Search..." class="w-full border-b px-3 py-2 text-sm focus:outline-none">
                    <ul class="max-h-40 overflow-y-auto text-sm">
                        <template x-for="option in filteredOptions" :key="option.id">
                            <li @click="select(option.id)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer" x-text="option.label"></li>
                        </template>
                        <li x-show="filteredOptions.length === 0" class="px-4 py-2 text-gray-400">No results</li>
                    </ul>
                </div>
            </div>

            <!-- Case Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Case Date <span class="text-red-500">*</span></label>
                <input type="date" name="date" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                <input type="tel" name="phone" placeholder="Enter phone" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Fee -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee <span class="text-red-500">*</span></label>
                <input type="number" name="fee" step="0.01" placeholder="Enter fee" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-4 mt-6">
                <label class="text-sm font-medium text-gray-700">Status</label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-300 peer-checked:bg-indigo-500 rounded-full relative after:content-[''] after:absolute after:left-1 after:top-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex justify-end space-x-4">
                <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-md shadow">Save</button>
                <a href="{{ route('patient-cases.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md shadow">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Alpine Dropdown Component -->
<script>
    function dropdown(options) {
        return {
            open: false,
            search: '',
            selected: '',
            options: options,
            get filteredOptions() {
                return this.options.filter(option => option.label.toLowerCase().includes(this.search.toLowerCase()));
            },
            getLabel(id) {
                const match = this.options.find(option => option.id == id);
                return match ? match.label : '';
            },
            select(id) {
                this.selected = id;
                this.open = false;
                this.search = '';
            }
        }
    }
</script>
@endsection
