@extends('layouts.admin')

@section('title', 'Diagnosis Test List')

@section('content')
<div class="bg-gray-50 p-6 font-sans">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Diagnosis Test</h1>
        <div class="flex space-x-3">
            <button class="flex items-center border border-main-color space-x-2 px-4 py-2 bg-pink-100 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ph ph-funnel"></i>
                <span>Filter</span>
            </button>
            <a href="{{ route('admin.diagnosis.diagnosis_test.add') }}" class="flex items-center space-x-2 px-4 py-2 bg-main-color text-white rounded-lg hover:bg-pink-700 transition-colors">
                <i class="ph ph-plus"></i>
                <span>New Patient Diagnosis Test</span>
            </a>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Alerts -->
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Report Number
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doctor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Diagnosis Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created On
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($diagnosisTest as $diagnosis)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $diagnosis->report_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $diagnosis?->patient?->name }}</div>
                            <div class="text-xs text-gray-500">{{ $diagnosis?->patient?->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $diagnosis?->doctor?->name }}</div>
                            <div class="text-xs text-gray-500">{{ $diagnosis?->doctor?->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $diagnosis?->diagnosisCategory?->diagnosis_category }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="bg-pink-100 text-pink-800 px-2 py-1 rounded-lg text-xs font-medium text-center">
                                {{ \Carbon\Carbon::parse($diagnosis->created_at)->format('g:i A') }}<br>
                                {{ \Carbon\Carbon::parse($diagnosis->created_at)->format('jS M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button class="text-gray-600 hover:text-blue-600 p-2 rounded-full hover:bg-blue-50 transition-colors">
                                <i class="fas fa-print"></i>
                            </button>
                            <a href="{{ route('admin.diagnosis.diagnosis_test.edit', $diagnosis->id) }}" class="text-gray-600 hover:text-green-600 p-2 rounded-full hover:bg-green-50 transition-colors">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <form method="post" action="{{ route('admin.diagnosis.diagnosis_test.delete', $diagnosis->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-red-600 p-2 rounded-full hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        @include('partials.pagination', ['users' => $diagnosisTest])
    </div>
</div>
@endsection