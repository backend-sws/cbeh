@extends('layouts.admin')
@section('title', 'Birth Certificates List')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Birth Certificates</h1>
            <p class="text-sm text-gray-500">Manage and print birth records.</p>
        </div>
        <a href="{{ route('admin.birth_report.create') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 shadow-md transition-all active:scale-95">
            <i class="ph ph-plus-circle text-lg mr-2"></i> New Certificate
        </a>
    </div>

    {{-- Search & Filter Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('admin.birth_report.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            {{-- Search Input --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph ph-magnifying-glass text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Reg No, Mother Name, Father Name..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            {{-- Date Filter --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Filter by Date</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.birth_report.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg border border-gray-300 transition-colors" title="Reset">
                    <i class="ph ph-arrow-counter-clockwise text-lg"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reg No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mother & Source</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Child Info</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Birth Date</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($reports as $rpt)
                        <tr class="hover:bg-blue-50 transition-colors duration-150 group">
                            
                            {{-- Reg No --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-blue-600 group-hover:text-blue-800">{{ $rpt->reg_no }}</span>
                            </td>

                            {{-- Mother Name & Source --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-gray-800">{{ $rpt->mother_name }}</span>
                                    <div class="mt-1">
                                        @if ($rpt->ipd_patient_id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="ph ph-bed mr-1"></i> IPD
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                <i class="ph ph-pencil-simple mr-1"></i> Manual
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Child Gender & Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">
                                    {{ $rpt->child_name ?: 'Not Named' }}
                                </div>
                                <span class="text-xs text-gray-500 flex items-center mt-0.5">
                                    @if($rpt->gender == 'Male')
                                        <i class="ph ph-gender-male text-blue-500 mr-1"></i> Male
                                    @elseif($rpt->gender == 'Female')
                                        <i class="ph ph-gender-female text-pink-500 mr-1"></i> Female
                                    @else
                                        Other
                                    @endif
                                </span>
                            </td>

                            {{-- Birth Date --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-800">{{ date('d M, Y', strtotime($rpt->birth_date)) }}</div>
                                <div class="text-xs text-gray-500">{{ date('h:i A', strtotime($rpt->birth_time)) }}</div>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.birth_report.print', $rpt->id) }}" target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                                    <i class="ph ph-printer text-lg mr-1.5"></i> Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="ph ph-files text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">No birth records found.</p>
                                    <p class="text-xs text-gray-400 mt-1">Try adjusting your search or add a new certificate.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $reports->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection