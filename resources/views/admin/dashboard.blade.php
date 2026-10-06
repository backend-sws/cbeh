@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @if (auth()->user()->role == 'Admin')
        <div class="min-h-screen bg-gray-50">
            <div class="container mx-auto px-4 py-8">
                {{-- Header Section --}}
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Healthcare Dashboard</h1>
                        <p class="text-gray-600 mt-1">Comprehensive overview of your facility's performance</p>
                    </div>
                    <div class="text-sm bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                        <span class="font-medium text-gray-700">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>

                {{-- Filter Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 flex-grow">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                    <div class="relative">
                                        <input type="date" name="from" value="{{ request('from') }}"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                    <div class="relative">
                                        <input type="date" name="to" value="{{ request('to') }}"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                    </div>
                                </div>
                            </div>

                           <div class="flex flex-col lg:flex-row items-center gap-3">
    <button type="submit"
        class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-medium rounded-lg transition duration-200 flex items-center justify-center shadow-sm hover:shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
        </svg>
        Apply Filter
    </button>

    <a href="{{ route('dashboard') }}" 
       class="w-full lg:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 text-sm font-medium rounded-lg transition duration-200 border border-gray-300">
        Reset
    </a>
</div>
                        </div>
                    </form>
                </div>

                @if (setting('hide_dashboard_data', 'no') == 'yes')
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 text-center my-8 transition duration-200 hover:shadow-lg">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268-2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Dashboard Data is Hidden</h2>
                        <p class="text-gray-600 max-w-md mx-auto mb-6">Financial metrics, patient statistics, and resource counts are currently set to hidden by the administrator. You can toggle this setting back on from the system settings.</p>
                        <a href="{{ route('admin.hospital-web-settings.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Go to Settings
                        </a>
                    </div>
                @else
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Revenue -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($totalRevenue, 2) }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">All time collected amount</p>
                    </div>

                    <!-- OPD Collection -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">OPD Collection</p>
                                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($opdCollection, 2) }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Outpatient department</p>
                    </div>

                    <!-- Diagnosis Collection -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Pathology Collection</p>
                                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($pathologyCollection, 2) }}
                                </p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Tests and diagnostics</p>
                    </div>
                    {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Diagnosis Collection</p>
                        <p class="text-2xl font-bold text-gray-800">₹{{ number_format($diagnosisCollection, 2) }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">Tests and diagnostics</p>
            </div> --}}

                    <!-- IPD Collection -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">IPD Collection</p>
                                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($ipdCollection, 2) }}</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Inpatient department</p>
                    </div>
                </div>

                {{-- Today's Overview --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Today's Overview</h2>
                        <div class="mt-2 md:mt-0">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Live Data
                            </span>
                        </div>
                    </div>

                    {{-- Today's Revenue Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Today's Revenue -->
                        <div
                            class="bg-gray-50 border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <p class="text-sm font-medium text-gray-500 mb-1">Today's Revenue</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($todayRevenue, 2) }}</p>
                            <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500">Updated just now</span>
                            </div>
                        </div>

                        <!-- Today's OPD Collection -->
                        <div
                            class="bg-gray-50 border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <p class="text-sm font-medium text-gray-500 mb-1">Today's OPD</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($todayOpdCollection, 2) }}</p>
                            <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500">Outpatient dept.</span>
                            </div>
                        </div>

                        <!-- Today's Diagnosis Collection -->
                        <div
                            class="bg-gray-50 border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <p class="text-sm font-medium text-gray-500 mb-1">Today's Pathology</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($todayPathologyCollection, 2) }}
                            </p>
                            <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500">Lab & tests</span>
                            </div>
                        </div>

                        <!-- Today's IPD Collection -->
                        <div
                            class="bg-gray-50 border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <p class="text-sm font-medium text-gray-500 mb-1">Today's IPD</p>
                            <p class="text-xl font-bold text-gray-800">₹{{ number_format($todayIpdCollection, 2) }}</p>
                            <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-500">Inpatient dept.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Patient Statistics --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Patient Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Total Patients -->
                            <a href="{{ route('admin.users.user_list', 'Patient') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Total Patients</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $totalPatients }}</p>
                                        </div>
                                        <div class="bg-indigo-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Registered total</p>
                                </div>
                            </a>

                            <!-- Total OPD Patients -->
                            <a href="{{ route('admin.opd.index') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Total OPD Patients</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $totalopdPatients }}</p>
                                        </div>
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Outpatient visits</p>
                                </div>
                            </a>

                            <!-- Total IPD Patients -->
                            <a href="{{ route('admin.ipd.index') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">IPD Patients</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $totalIpdPatients }}</p>
                                        </div>
                                        <div class="bg-pink-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Admitted total</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Today's Patient Stats --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Patient Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Today's New Patients -->
                            <div
                                class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">New Patients</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $todayNewPatients }}</p>
                                    </div>
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Registered today</p>
                            </div>

                            <!-- Today's New OPD Patients -->
                            <div
                                class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">New OPD Patients</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $todayNewOpdPatients }}</p>
                                    </div>
                                    <div class="bg-teal-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Outpatient visits</p>
                            </div>

                            <!-- Today's New IPD Patients -->
                            <div
                                class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">New IPD Patients</p>
                                        <p class="text-xl font-bold text-gray-800">{{ $todayNewIpdPatients }}</p>
                                    </div>
                                    <div class="bg-amber-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Admitted today</p>
                            </div>
                        </div>
                    </div>

                    {{-- Resources Overview --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resources Overview</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Available Beds -->
                            <a href="{{ route('admin.bed.status') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">

                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Available Beds</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $available_bebs }}</p>
                                        </div>
                                        <div class="bg-cyan-100 p-2 rounded-lg">
                                            <!-- Bed Icon -->
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18M4 10V7a1 1 0 011-1h3a1 1 0 011 1v3m10 0V9a2 2 0 00-2-2h-4a2 2 0 00-2 2v1m-6 0v10m18-10v10" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Currently available</p>
                                </div>
                            </a>
                            <!-- Doctors -->
                            <a href="{{ route('admin.users.user_list', 'Doctor') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Doctors</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $doctors }}</p>
                                        </div>
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Medical staff</p>
                                </div>
                            </a>
                            <!-- Nurses -->
                            <a href="{{ route('admin.users.user_list', 'Nurse') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Nurses</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $nurses }}</p>
                                        </div>
                                        <div class="bg-pink-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Nursing staff</p>
                                </div>
                            </a>
                            <!-- Admins -->
                            <a href="{{ route('admin.users.user_list', 'Admin') }}">
                                <div
                                    class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Admins</p>
                                            <p class="text-xl font-bold text-gray-800">{{ $admins }}</p>
                                        </div>
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">Administrative staff</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Additional Staff --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Accountants -->
                        <div
                            class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <a href="{{ route('admin.users.user_list', 'Accountant') }}"
                                class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Accountants</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $accountants }}</p>
                                </div>
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                                        </path>
                                    </svg>
                                </div>
                            </a>
                            <p class="text-xs text-gray-500">Finance department</p>
                        </div>

                        <!-- Lab Technicians -->
                        <div
                            class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <a href="{{ route('admin.users.user_list', 'Lab Technician') }}"
                                class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Lab Technicians</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $labTechnians }}</p>
                                </div>
                                <div class="bg-yellow-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
                                    </svg>
                                </div>
                            </a>
                            <p class="text-xs text-gray-500">Laboratory staff</p>
                        </div>

                        <!-- Receptionists -->
                        <div
                            class="bg-white border border-gray-200 p-4 rounded-lg hover:shadow-sm transition duration-200">
                            <a href="{{ route('admin.users.user_list', 'Receptionist') }}"
                                class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Receptionists</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $receptionists }}</p>
                                </div>
                                <div class="bg-red-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                            </a>
                            <p class="text-xs text-gray-500">Front desk staff</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @else
      <div class=" bg-gray-50">
        <div class="p-12 text-center">
            <h1 class="text-xl font-semibold text-red-600">Strictly for Administrators!</h1>
            <p>Please contact your system manager for access.</p>
        </div>
        </div>
    @endif

@endsection
