@extends('layouts.admin')
@section('title', 'Nursing MAR Station')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <span class="p-3 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-xl shadow-md">
                    <i class="fas fa-user-nurse text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ward Nursing Station — Medication Chart (MAR)</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Real-time scheduled dose monitoring & administration for all admitted patients</p>
                </div>
            </div>

            <!-- Current Staff Profile Badge -->
            <div class="flex items-center space-x-3 bg-blue-50 border border-blue-200 px-4 py-2 rounded-xl">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-xs">
                    <div class="font-bold text-gray-900">{{ auth()->user()->name }}</div>
                    <div class="text-blue-700 font-mono text-[11px]">Staff ID: EMP-{{ auth()->user()->id }}</div>
                </div>
            </div>
        </div>

        <!-- Filter Controls Bar -->
        <form method="GET" action="{{ route('admin.ipd.medication.station') }}" class="mt-6 pt-5 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                       class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Shift</label>
                <select name="shift" onchange="this.form.submit()" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="All" {{ $shift === 'All' ? 'selected' : '' }}>All Shifts (24 Hours)</option>
                    <option value="Morning" {{ $shift === 'Morning' ? 'selected' : '' }}>Morning Shift (08:00 – 14:00)</option>
                    <option value="Evening" {{ $shift === 'Evening' ? 'selected' : '' }}>Evening Shift (14:00 – 20:00)</option>
                    <option value="Night" {{ $shift === 'Night' ? 'selected' : '' }}>Night Shift (20:00 – 08:00)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="All" {{ $status === 'All' ? 'selected' : '' }}>All Statuses</option>
                    <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Due / Pending Only</option>
                    <option value="given" {{ $status === 'given' ? 'selected' : '' }}>Given Only</option>
                    <option value="not_given" {{ $status === 'not_given' ? 'selected' : '' }}>Not Given</option>
                    <option value="held" {{ $status === 'held' ? 'selected' : '' }}>Skipped / Held</option>
                    <option value="refused" {{ $status === 'refused' ? 'selected' : '' }}>Refused</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-1.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors">
                    <i class="fas fa-filter mr-1"></i> Apply Filters
                </button>
                <a href="{{ route('admin.ipd.medication.station') }}" class="py-1.5 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>

        <!-- Summary KPIs -->
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 mt-4 pt-4 border-t border-gray-100">
            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-200 text-center">
                <span class="text-[11px] text-gray-500 block">Total Doses</span>
                <span class="text-xl font-bold text-gray-800">{{ $summary['total'] }}</span>
            </div>
            <div class="bg-amber-50 p-2.5 rounded-lg border border-amber-200 text-center">
                <span class="text-[11px] text-amber-700 block">Due / Pending</span>
                <span class="text-xl font-bold text-amber-900">{{ $summary['due'] }}</span>
            </div>
            <div class="bg-green-50 p-2.5 rounded-lg border border-green-200 text-center">
                <span class="text-[11px] text-green-700 block">Given</span>
                <span class="text-xl font-bold text-green-900">{{ $summary['given'] }}</span>
            </div>
            <div class="bg-rose-50 p-2.5 rounded-lg border border-rose-200 text-center">
                <span class="text-[11px] text-rose-700 block">Not Given</span>
                <span class="text-xl font-bold text-rose-900">{{ $summary['not_given'] }}</span>
            </div>
            <div class="bg-orange-50 p-2.5 rounded-lg border border-orange-200 text-center">
                <span class="text-[11px] text-orange-700 block">Held</span>
                <span class="text-xl font-bold text-orange-900">{{ $summary['held'] }}</span>
            </div>
            <div class="bg-purple-50 p-2.5 rounded-lg border border-purple-200 text-center">
                <span class="text-[11px] text-purple-700 block">Refused</span>
                <span class="text-xl font-bold text-purple-900">{{ $summary['refused'] }}</span>
            </div>
        </div>
    </div>

    <!-- Doses Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">
                Scheduled Doses ({{ \Carbon\Carbon::parse($date)->format('d M Y') }} - Shift: {{ $shift }})
            </h3>
            <span class="text-xs text-gray-500 font-medium">Showing {{ $doses->total() }} total doses</span>
        </div>

        @if($doses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Time & Shift</th>
                            <th class="px-4 py-3 text-left">Patient & Bed</th>
                            <th class="px-4 py-3 text-left">Medicine & Dose</th>
                            <th class="px-4 py-3 text-left">Route & Instructions</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Admin Details</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($doses as $dose)
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-800">
                                    <span class="font-bold text-gray-900 block">{{ $dose->scheduled_at ? $dose->scheduled_at->format('h:i A') : '--' }}</span>
                                    <span class="text-[10px] text-gray-500">{{ $dose->shift }} Shift</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">
                                    @if($dose->ipdPatient)
                                        <a href="{{ route('admin.ipd.detail', $dose->ipdPatient->id) }}#medication_chart" class="font-bold text-blue-600 hover:underline block">
                                            {{ $dose->ipdPatient->patient->name ?? 'Patient' }}
                                        </a>
                                        <span class="text-gray-500 text-[11px]">Bed: <strong>{{ $dose->ipdPatient->beds->name ?? 'Bed N/A' }}</strong> (IPD: {{ $dose->ipdPatient->id }})</span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 block">{{ $dose->medication->medicine_name ?? 'N/A' }}</span>
                                    <span class="text-xs text-gray-500">{{ $dose->medication->dose ?? '' }} ({{ $dose->medication->dose_type ?: 'Tablet' }})</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-700 max-w-xs">
                                    <span class="font-medium text-blue-700">{{ $dose->route_administered ?: ($dose->medication->route ?? 'Oral') }}</span>
                                    @if(!empty($dose->medication->instructions))
                                        <div class="text-[10px] text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 mt-0.5 line-clamp-1">
                                            {{ $dose->medication->instructions }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($dose->status === 'given')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1 text-green-600"></i> Given
                                        </span>
                                    @elseif($dose->status === 'not_given')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <i class="fas fa-times-circle mr-1 text-rose-600"></i> Not Given
                                        </span>
                                    @elseif($dose->status === 'held')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                            <i class="fas fa-pause-circle mr-1 text-orange-600"></i> Held
                                        </span>
                                    @elseif($dose->status === 'refused')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="fas fa-user-slash mr-1 text-purple-600"></i> Refused
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fas fa-clock mr-1 text-amber-600"></i> Due
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">
                                    @if($dose->status !== 'scheduled')
                                        <div class="text-[11px] text-gray-800">
                                            By: <strong>{{ $dose->staff_name }}</strong> ({{ $dose->staff_id }})
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            At: {{ $dose->actual_administered_at ? $dose->actual_administered_at->format('h:i A') : '--' }}
                                        </div>
                                        @if($dose->remarks)
                                            <div class="text-[10px] text-gray-600 italic">"{{ $dose->remarks }}"</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center text-xs">
                                    @if($dose->ipdPatient)
                                        <a href="{{ route('admin.ipd.detail', $dose->ipdPatient->id) }}?mar_date={{ $date }}#medication_chart"
                                           class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg {{ $dose->status === 'scheduled' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                            <i class="fas {{ $dose->status === 'scheduled' ? 'fa-hand-holding-medical' : 'fa-edit' }} mr-1"></i>
                                            {{ $dose->status === 'scheduled' ? 'Administer' : 'View / Edit' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $doses->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
                <p class="font-medium text-gray-700">No medication doses match your filter criteria.</p>
                <p class="text-xs text-gray-400 mt-1">Change date or shift filters above to view other schedules.</p>
            </div>
        @endif
    </div>

</div>
@endsection
