@extends('layouts.admin')
@section('title', 'OPD Patient')
@section('content')

    <body class="bg-gray-100 p-2">


        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class=" py-4 px-4 mx-auto bg-white rounded-lg shadow-md overflow-hidden ">
            {{-- Header Section --}}
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">OPD Patients</h1>
                <div>
                    <a href="{{ route('admin.opd.add') }}"
                        class="inline-flex items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                        <i class="ph ph-plus-bold mr-2"></i> New OPD Patient
                    </a>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Name or OPD No or UHID No"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                            <option value="">All Doctors</option>
                            @foreach ($doctors as $doc)
                                <option value="{{ $doc->id }}" @if (($doctor_id ?? '') == $doc->id) selected @endif>
                                    {{ $doc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $from_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $to_date ?? '' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                            <i class="ph ph-funnel mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.opd.index') }}"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="ph ph-arrow-counter-clockwise mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OPD
                                NO</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UHID
                                NO</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[180px]">
                                Patient</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[240px]">
                                Doctor</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Appointment</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Days Left</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Charge</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($opd_patients as $patient)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('admin.opd.detail', $patient->id) }}"
                                        class="inline-block px-3 py-1 bg-pink-100 text-main-color rounded-md font-medium hover:bg-pink-200 transition-colors">
                                        {{ $patient->opd_no }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-gray-500">{{ $patient->patient_unique_id ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-[180px]">
                                        <div class="font-medium text-gray-900 text-sm line-clamp-2 break-words" title="{{ $patient->patient->name ?? 'N/A' }}">
                                            {{ $patient->patient->name ?? 'N/A' }}
                                        </div>
                                        @if (!empty($patient->patient->email))
                                            <div class="text-xs text-gray-500 truncate" title="{{ $patient->patient->email }}">
                                                {{ $patient->patient->email }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-[240px]">
                                        <div class="font-medium text-gray-900 text-sm line-clamp-2 break-words" title="{{ $patient->doctor->name ?? 'N/A' }}">
                                            {{ $patient->doctor->name ?? 'N/A' }}
                                        </div>
                                        @if (!empty($patient->doctor->email))
                                            <div class="text-xs text-gray-500 truncate" title="{{ $patient->doctor->email }}">
                                                {{ $patient->doctor->email }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-pink-600 font-medium">
                                        {{ \Carbon\Carbon::parse($patient->appointment_date)->format('g:i A') }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($patient->appointment_date)->format('jS M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $freeVisitDays = (int) setting('free_visit_day', 21); // Default to 21 if not set
                                        $expiry = \Carbon\Carbon::parse($patient->appointment_date)->addDays(
                                            $freeVisitDays,
                                        );
                                        $daysLeft = floor(now()->diffInDays($expiry, false));
                                    @endphp
                                    @if ($daysLeft > 0)
                                        <span class="text-green-600 font-semibold">{{ $daysLeft }} days left</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Expired</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center font-medium">
                                    ₹ {{ number_format($patient->standard_charge, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex flex-col justify-center items-center space-y-2">
                                        <a href="{{ route('admin.opd.blankreceipt', $patient->id) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                                            <i class="ph ph-file-text mr-1"></i> Blank Prescription
                                        </a>
                                        <a href="{{ route('admin.opd.edit', $patient->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors">
                                            <i class="ph ph-pencil mr-1"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.opd.print', $patient->id) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                            <i class="ph ph-printer mr-1"></i>  Print Receipt
                                        </a>
                                        <form action="{{ route('admin.opd.moveToIpd', $patient->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                                                onclick="return confirm('Are you sure you want to move this patient to IPD?');">
                                                <i class="ph ph-arrow-right mr-1"></i> Move to IPD
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No OPD patients found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                @include('partials.pagination', ['users' => $opd_patients])
            </div>
        </div>


    </body>

@endsection
