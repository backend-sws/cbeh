@extends('layouts.admin')
@section('title', 'Search IPD Patient')
@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">🏥 Pharmacy IPD Management</h2>
        
        <form method="GET" action="{{ route('admin.medicine.ipd.search') }}">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:outline-none text-lg"
                    placeholder="Enter IPD Number, Patient Name or Phone..." autofocus>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition">
                    Search
                </button>
            </div>
        </form>

        @if(isset($patients) && $patients->count() > 0)
            <div class="mt-8 overflow-hidden rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">IPD No</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Patient Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Admission Date</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($patients as $ipd)
                            <tr class="hover:bg-gray-50 cursor-pointer" 
                                onclick="window.location='{{ route('admin.medicine.ipd.dashboard', $ipd->id) }}'">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">{{ $ipd->ipd_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $ipd->patient->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $ipd->patient->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($ipd->admission_date)->format('d M, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.medicine.ipd.dashboard', $ipd->id) }}" 
                                       class="text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md">
                                        Manage Medicine <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(isset($search))
            <div class="mt-8 text-center text-gray-500 py-10 bg-gray-50 rounded-lg">
                <i class="fas fa-user-slash text-4xl mb-3 text-gray-300"></i>
                <p>No admitted patient found with these details.</p>
            </div>
        @endif
    </div>
</div>
@endsection