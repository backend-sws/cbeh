@extends('layouts.admin')
@section('title', 'Death Certificates')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen font-sans">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Death Certificates</h1>
        <a href="{{ route('admin.death_report.create') }}" 
           class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow-sm flex items-center">
            <i class="ph ph-plus-circle mr-2"></i> New Certificate
        </a>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Name or Reg No..." class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 font-bold text-gray-600">Reg No</th>
                    <th class="p-4 font-bold text-gray-600">Patient Name</th>
                    <th class="p-4 font-bold text-gray-600">Date of Death</th>
                    <th class="p-4 font-bold text-gray-600">Cause</th>
                    <th class="p-4 font-bold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($reports as $rpt)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-red-600">{{ $rpt->reg_no }}</td>
                    <td class="p-4">{{ $rpt->patient_name }}</td>
                    <td class="p-4">{{ date('d M, Y', strtotime($rpt->death_date)) }}</td>
                    <td class="p-4 text-gray-600">{{ $rpt->cause_of_death }}</td>
                    <td class="p-4">
                        <a href="{{ route('admin.death_report.print', $rpt->id) }}" target="_blank" class="text-blue-600 hover:underline">
                            <i class="ph ph-printer"></i> Print
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $reports->links() }}</div>
    </div>
</div>
@endsection