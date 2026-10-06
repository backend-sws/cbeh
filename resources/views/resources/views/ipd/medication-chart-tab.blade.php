@php
    $currentDate = $mar_date ?? now()->toDateString();
    $prevDate = \Carbon\Carbon::parse($currentDate)->subDay()->toDateString();
    $nextDate = \Carbon\Carbon::parse($currentDate)->addDay()->toDateString();

    $todayDoses = $mar_doses_today ?? collect();
    $morningDoses = $todayDoses->where('shift', 'Morning');
    $eveningDoses = $todayDoses->where('shift', 'Evening');
    $nightDoses = $todayDoses->where('shift', 'Night');

    $activeMeds = $active_medications ?? collect();
    $activeCount = $activeMeds->where('status', 'active')->count();

    $dueCount = $todayDoses->where('status', 'scheduled')->count();
    $givenCount = $todayDoses->where('status', 'given')->count();
    $notGivenCount = $todayDoses->where('status', 'not_given')->count();
    $heldCount = $todayDoses->where('status', 'held')->count();
    $refusedCount = $todayDoses->where('status', 'refused')->count();
@endphp

<div class="space-y-6">

    <!-- Top Action & Date Bar -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Left: Title & Date Selector -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center space-x-2">
                    <span class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                        <i class="fas fa-pills text-xl"></i>
                    </span>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Ongoing Medication & MAR Chart</h2>
                        <p class="text-xs text-gray-500">Medication Administration Record & Real-time Dose Tracking</p>
                    </div>
                </div>

                <!-- Date Picker & Controls -->
                <div class="flex items-center space-x-1 bg-gray-50 border border-gray-200 rounded-lg p-1 ml-0 md:ml-4">
                    <a href="?mar_date={{ $prevDate }}" 
                       class="px-2.5 py-1 text-xs font-medium text-gray-600 hover:text-blue-600 hover:bg-white rounded transition-colors"
                       title="Previous Day">
                        <i class="fas fa-chevron-left mr-1"></i> Prev
                    </a>
                    <form method="GET" action="" class="inline-flex items-center">
                        <input type="date" name="mar_date" value="{{ $currentDate }}" 
                               onchange="this.form.submit()" 
                               class="text-xs font-semibold px-2 py-1 border border-gray-300 rounded bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </form>
                    <a href="?mar_date={{ $nextDate }}" 
                       class="px-2.5 py-1 text-xs font-medium text-gray-600 hover:text-blue-600 hover:bg-white rounded transition-colors"
                       title="Next Day">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                    @if($currentDate !== now()->toDateString())
                        <a href="?mar_date={{ now()->toDateString() }}" 
                           class="px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-100 hover:bg-blue-200 rounded transition-colors">
                            Today
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.ipd.medication.print', $ipd_patient->id) }}" target="_blank"
                   class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                    <i class="fas fa-print mr-1.5 text-gray-500"></i> Print MAR Sheet
                </a>

                <button type="button" onclick="openAddMedicationModal()"
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-plus-circle mr-1.5"></i> Prescribe / Add Medication
                </button>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mt-5 pt-5 border-t border-gray-100">
            <div class="bg-blue-50/60 p-3 rounded-lg border border-blue-100">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-blue-700">Active Medicines</span>
                    <i class="fas fa-prescription-bottle-alt text-blue-400"></i>
                </div>
                <div class="text-2xl font-bold text-blue-900 mt-1">{{ $activeCount }}</div>
                <div class="text-[11px] text-blue-600">Prescribed courses</div>
            </div>

            <div class="bg-amber-50/60 p-3 rounded-lg border border-amber-100">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-amber-700">Due / Scheduled</span>
                    <i class="fas fa-clock text-amber-400"></i>
                </div>
                <div class="text-2xl font-bold text-amber-900 mt-1">{{ $dueCount }}</div>
                <div class="text-[11px] text-amber-600">Pending doses today</div>
            </div>

            <div class="bg-green-50/60 p-3 rounded-lg border border-green-100">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-green-700">Given</span>
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="text-2xl font-bold text-green-900 mt-1">{{ $givenCount }}</div>
                <div class="text-[11px] text-green-600">Successfully administered</div>
            </div>

            <div class="bg-rose-50/60 p-3 rounded-lg border border-rose-100">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-rose-700">Not Given / Held</span>
                    <i class="fas fa-pause-circle text-rose-400"></i>
                </div>
                <div class="text-2xl font-bold text-rose-900 mt-1">{{ $notGivenCount + $heldCount }}</div>
                <div class="text-[11px] text-rose-600">Missed or withheld</div>
            </div>

            <div class="bg-purple-50/60 p-3 rounded-lg border border-purple-100">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-purple-700">Refused</span>
                    <i class="fas fa-user-slash text-purple-400"></i>
                </div>
                <div class="text-2xl font-bold text-purple-900 mt-1">{{ $refusedCount }}</div>
                <div class="text-[11px] text-purple-600">Refused by patient</div>
            </div>
        </div>
    </div>

    <!-- Active Doctor Prescription Orders (Medication Registry) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i> Active Prescribed Medications
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Medications currently active and scheduled for this patient</p>
            </div>
            <span class="text-xs px-2.5 py-1 bg-blue-100 text-blue-800 font-semibold rounded-full">
                {{ $activeCount }} Active
            </span>
        </div>

        @if($activeMeds->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Medicine & Dose</th>
                            <th class="px-4 py-3 text-left">Dose Type & Route</th>
                            <th class="px-4 py-3 text-left">Frequency</th>
                            <th class="px-4 py-3 text-left">Duration</th>
                            <th class="px-4 py-3 text-left">Doctor Instructions</th>
                            <th class="px-4 py-3 text-left">Prescribed By</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($activeMeds as $med)
                            <tr class="hover:bg-blue-50/30 transition-colors {{ $med->status !== 'active' ? 'opacity-60 bg-gray-50' : '' }}">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-capsules text-blue-500 mr-2 text-xs"></i>
                                        {{ $med->medicine_name }}
                                    </div>
                                    @if($med->dose)
                                        <div class="text-xs text-gray-500 mt-0.5">Strength: <span class="font-medium text-gray-700">{{ $med->dose }}</span></div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700">
                                    <div class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-medium">
                                        {{ $med->dose_type ?: 'Tablet' }}
                                    </div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Route: <span class="font-medium text-blue-600">{{ $med->route ?: 'Oral' }}</span></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold 
                                        {{ in_array($med->frequency, ['STAT', 'SOS']) ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $med->frequency ?: 'OD' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                    <div>{{ $med->duration ?: '5 Days' }}</div>
                                    @if($med->start_date)
                                        <div class="text-[11px] text-gray-400">From {{ \Carbon\Carbon::parse($med->start_date)->format('d M') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-700 max-w-xs">
                                    @if($med->instructions)
                                        <div class="p-1.5 bg-amber-50/70 border border-amber-200/60 rounded text-amber-900 flex items-start">
                                            <i class="fas fa-info-circle text-amber-500 mr-1.5 mt-0.5 text-xs flex-shrink-0"></i>
                                            <span>{{ $med->instructions }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">None</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                                    @php
                                        $rawMedDoc = $med->doctor->name ?? '';
                                        $cleanMedDoc = ($rawMedDoc && $rawMedDoc !== 'Attending Doctor') ? preg_replace('/^(dr\.?\s*)+/i', '', trim($rawMedDoc)) : '';
                                    @endphp
                                    <div>{{ $cleanMedDoc ? 'Dr. ' . $cleanMedDoc : ($med->doctor->name ?? 'Attending Doctor') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $med->created_at ? $med->created_at->format('d M, h:i A') : '' }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($med->status === 'active')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> Active
                                        </span>
                                    @elseif($med->status === 'discontinued')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800" title="{{ $med->discontinue_reason }}">
                                            Discontinued
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ ucfirst($med->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center text-xs">
                                    @if($med->status === 'active')
                                        <div class="flex items-center justify-center space-x-1.5">
                                            @if(in_array($med->frequency, ['SOS', 'PRN']))
                                                <button type="button" 
                                                        onclick="openSosModal({{ $med->id }}, '{{ addslashes($med->medicine_name) }}', '{{ addslashes($med->dose) }}', '{{ addslashes($med->route) }}')"
                                                        class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded font-semibold text-[11px] transition-colors"
                                                        title="Give SOS / PRN Dose Now">
                                                    <i class="fas fa-bolt mr-1"></i> Give SOS
                                                </button>
                                            @endif
                                            <button type="button"
                                                    onclick="openDiscontinueModal({{ $med->id }}, '{{ addslashes($med->medicine_name) }}')"
                                                    class="px-2 py-1 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-red-600 border border-gray-200 rounded font-medium text-[11px] transition-colors"
                                                    title="Discontinue Medication">
                                                <i class="fas fa-ban mr-1"></i> Stop
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Stopped</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-prescription-bottle text-4xl text-gray-300 mb-3"></i>
                <p class="font-medium">No active medications prescribed for this patient.</p>
                <p class="text-xs text-gray-400 mt-1">Click "Prescribe / Add Medication" above or create an IPD prescription.</p>
            </div>
        @endif
    </div>

    <!-- Daily Medication Administration Record (MAR Timeline Grid) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-check text-green-600 mr-2"></i> Daily MAR Schedule: {{ \Carbon\Carbon::parse($currentDate)->format('l, d F Y') }}
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Nursing shift schedule: click any scheduled dose to record administration</p>
            </div>
            
            <div class="flex items-center space-x-2 text-xs">
                <span class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded">
                    <i class="fas fa-clock mr-1 text-amber-500"></i> Scheduled
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-green-50 text-green-800 border border-green-200 rounded">
                    <i class="fas fa-check-circle mr-1 text-green-500"></i> Given
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-rose-50 text-rose-800 border border-rose-200 rounded">
                    <i class="fas fa-times-circle mr-1 text-rose-500"></i> Not Given
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-800 border border-orange-200 rounded">
                    <i class="fas fa-pause-circle mr-1 text-orange-500"></i> Held
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-purple-50 text-purple-800 border border-purple-200 rounded">
                    <i class="fas fa-user-slash mr-1 text-purple-500"></i> Refused
                </span>
            </div>
        </div>

        @if($todayDoses->count() > 0)
            <div class="p-6 space-y-6">

                <!-- 1. Morning Shift (08:00 - 14:00) -->
                <div class="border border-amber-200/70 rounded-xl overflow-hidden bg-amber-50/20">
                    <div class="bg-amber-100/60 px-4 py-2.5 border-b border-amber-200/70 flex items-center justify-between">
                        <span class="font-bold text-sm text-amber-900 flex items-center">
                            <i class="fas fa-sun text-amber-500 mr-2 text-base"></i> Morning Shift (08:00 AM – 02:00 PM)
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-amber-200/60 text-amber-900 rounded-full">
                            {{ $morningDoses->count() }} Doses
                        </span>
                    </div>

                    <div class="p-4">
                        @if($morningDoses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($morningDoses as $dose)
                                    @include('ipd.partials.dose-card', ['dose' => $dose])
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-gray-400 italic py-2 text-center">No doses scheduled for Morning Shift.</div>
                        @endif
                    </div>
                </div>

                <!-- 2. Evening Shift (14:00 - 20:00) -->
                <div class="border border-blue-200/70 rounded-xl overflow-hidden bg-blue-50/20">
                    <div class="bg-blue-100/60 px-4 py-2.5 border-b border-blue-200/70 flex items-center justify-between">
                        <span class="font-bold text-sm text-blue-900 flex items-center">
                            <i class="fas fa-cloud-sun text-blue-500 mr-2 text-base"></i> Evening Shift (02:00 PM – 08:00 PM)
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-blue-200/60 text-blue-900 rounded-full">
                            {{ $eveningDoses->count() }} Doses
                        </span>
                    </div>

                    <div class="p-4">
                        @if($eveningDoses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($eveningDoses as $dose)
                                    @include('ipd.partials.dose-card', ['dose' => $dose])
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-gray-400 italic py-2 text-center">No doses scheduled for Evening Shift.</div>
                        @endif
                    </div>
                </div>

                <!-- 3. Night Shift (20:00 - 08:00) -->
                <div class="border border-indigo-200/70 rounded-xl overflow-hidden bg-indigo-50/20">
                    <div class="bg-indigo-100/60 px-4 py-2.5 border-b border-indigo-200/70 flex items-center justify-between">
                        <span class="font-bold text-sm text-indigo-900 flex items-center">
                            <i class="fas fa-moon text-indigo-500 mr-2 text-base"></i> Night Shift (08:00 PM – 08:00 AM)
                        </span>
                        <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-200/60 text-indigo-900 rounded-full">
                            {{ $nightDoses->count() }} Doses
                        </span>
                    </div>

                    <div class="p-4">
                        @if($nightDoses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($nightDoses as $dose)
                                    @include('ipd.partials.dose-card', ['dose' => $dose])
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-gray-400 italic py-2 text-center">No doses scheduled for Night Shift.</div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <div class="p-10 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                <p class="font-medium text-gray-700">No doses scheduled for {{ \Carbon\Carbon::parse($currentDate)->format('d F Y') }}.</p>
                <p class="text-xs text-gray-400 mt-1">Prescribe a medication or change the date above to view schedules.</p>
            </div>
        @endif
    </div>

    <!-- Complete Medication Administration History & Audit Log -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-bold text-gray-800 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2"></i> Complete Medication Administration History
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Chronological audit trail of all administered, omitted, and refused doses</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <input type="text" id="marSearchInput" onkeyup="filterMarTable()" placeholder="Search medicine, staff..."
                       class="text-xs px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>

        @if(isset($mar_history) && $mar_history->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm" id="marHistoryTable">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Scheduled Time</th>
                            <th class="px-4 py-3 text-left">Medicine & Dose</th>
                            <th class="px-4 py-3 text-left">Route & Instructions</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actual Admin Time</th>
                            <th class="px-4 py-3 text-left">Quantity Given</th>
                            <th class="px-4 py-3 text-left">Administering Staff</th>
                            <th class="px-4 py-3 text-left">Audit Log Timestamp</th>
                            <th class="px-4 py-3 text-left">Remarks / Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="marHistoryBody">
                        @foreach($mar_history as $hist)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-800">
                                    {{ $hist->scheduled_at ? $hist->scheduled_at->format('d M Y, h:i A') : '--' }}
                                    <span class="block text-[10px] text-gray-400">Shift: {{ $hist->shift }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 block">{{ $hist->medication->medicine_name ?? 'N/A' }}</span>
                                    <span class="text-xs text-gray-500">{{ $hist->medication->dose ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-700 max-w-xs">
                                    <span class="font-medium text-blue-600">{{ $hist->route_administered ?: ($hist->medication->route ?? 'Oral') }}</span>
                                    @if(!empty($hist->medication->instructions))
                                        <div class="text-[11px] text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/50 mt-1">
                                            {{ $hist->medication->instructions }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($hist->status === 'given')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1 text-green-600"></i> Given
                                        </span>
                                    @elseif($hist->status === 'not_given')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <i class="fas fa-times-circle mr-1 text-rose-600"></i> Not Given
                                        </span>
                                    @elseif($hist->status === 'held')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                            <i class="fas fa-pause-circle mr-1 text-orange-600"></i> Skipped/Held
                                        </span>
                                    @elseif($hist->status === 'refused')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="fas fa-user-slash mr-1 text-purple-600"></i> Refused
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            Scheduled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-800 font-medium">
                                    {{ $hist->actual_administered_at ? $hist->actual_administered_at->format('d M, h:i A') : '--' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700">
                                    {{ $hist->given_quantity ?: ($hist->medication->dose ?: '--') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">
                                    @if($hist->staff_name)
                                        <span class="font-semibold text-gray-900 block">{{ $hist->staff_name }}</span>
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $hist->staff_id }}</span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs font-mono text-gray-500">
                                    {{ $hist->recorded_at ? $hist->recorded_at->format('d M Y, H:i:s') : ($hist->updated_at ? $hist->updated_at->format('d M Y, H:i:s') : '--') }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-700 max-w-xs">
                                    @if($hist->remarks)
                                        <span class="italic text-gray-800">{{ $hist->remarks }}</span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-500 text-xs italic">
                No medication administration history recorded yet.
            </div>
        @endif
    </div>

</div>

<!-- ================= MODALS ================= -->

<!-- 1. DOSE ADMINISTRATION MODAL (Mark as Given / Not Given / Held / Refused) -->
<div id="doseAdminModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDoseAdminModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-white/10 rounded-lg text-white">
                        <i class="fas fa-notes-medical text-lg"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-white" id="modalMedTitle">Administer Medication Dose</h3>
                        <p class="text-xs text-blue-100" id="modalMedSubtitle">Record dose administration execution</p>
                    </div>
                </div>
                <button type="button" onclick="closeDoseAdminModal()" class="text-white hover:text-gray-200 focus:outline-none text-xl font-bold">
                    &times;
                </button>
            </div>

            <!-- Patient & Medication Summary Bar -->
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 text-xs grid grid-cols-2 sm:grid-cols-4 gap-2">
                <div>
                    <span class="text-gray-500 block">Patient</span>
                    <span class="font-bold text-gray-800">{{ $ipd_patient->patient->name }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">UHID / IPD</span>
                    <span class="font-semibold text-gray-800">{{ $uhid ?? 'N/A' }} / {{ $ipd_patient->id }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Scheduled Time</span>
                    <span class="font-bold text-blue-700" id="modalScheduledTime">--</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Prescribed Dose</span>
                    <span class="font-bold text-gray-800" id="modalPrescribedDose">--</span>
                </div>
            </div>

            <!-- Modal Form -->
            <form id="doseAdminForm" method="POST" action="" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="status" id="adminStatus" value="given">

                <!-- Instructions Banner if any -->
                <div id="modalInstructionsBox" class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900 hidden">
                    <div class="font-semibold flex items-center text-amber-800 mb-0.5">
                        <i class="fas fa-prescription mr-1.5"></i> Doctor's Instructions:
                    </div>
                    <span id="modalInstructionsText"></span>
                </div>

                <!-- Status Selector Tiles -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">
                        Select Administration Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <!-- Given Tile -->
                        <button type="button" onclick="selectStatus('given')" id="btnStatus_given"
                                class="status-btn p-3 rounded-xl border-2 text-center transition-all flex flex-col items-center justify-center border-green-500 bg-green-50 text-green-800 shadow-sm">
                            <i class="fas fa-check-circle text-lg text-green-600 mb-1"></i>
                            <span class="text-xs font-bold">Given</span>
                            <span class="text-[10px] text-green-600">Administered</span>
                        </button>

                        <!-- Not Given Tile -->
                        <button type="button" onclick="selectStatus('not_given')" id="btnStatus_not_given"
                                class="status-btn p-3 rounded-xl border-2 text-center transition-all flex flex-col items-center justify-center border-gray-200 hover:border-rose-400 text-gray-600">
                            <i class="fas fa-times-circle text-lg text-rose-500 mb-1"></i>
                            <span class="text-xs font-bold">Not Given</span>
                            <span class="text-[10px] text-gray-500">Missed / Omitted</span>
                        </button>

                        <!-- Skipped / Held Tile -->
                        <button type="button" onclick="selectStatus('held')" id="btnStatus_held"
                                class="status-btn p-3 rounded-xl border-2 text-center transition-all flex flex-col items-center justify-center border-gray-200 hover:border-orange-400 text-gray-600">
                            <i class="fas fa-pause-circle text-lg text-orange-500 mb-1"></i>
                            <span class="text-xs font-bold">Skipped/Held</span>
                            <span class="text-[10px] text-gray-500">Temporarily Withheld</span>
                        </button>

                        <!-- Refused Tile -->
                        <button type="button" onclick="selectStatus('refused')" id="btnStatus_refused"
                                class="status-btn p-3 rounded-xl border-2 text-center transition-all flex flex-col items-center justify-center border-gray-200 hover:border-purple-400 text-gray-600">
                            <i class="fas fa-user-slash text-lg text-purple-500 mb-1"></i>
                            <span class="text-xs font-bold">Refused</span>
                            <span class="text-[10px] text-gray-500">By Patient</span>
                        </button>
                    </div>
                </div>

                <!-- Fields for GIVEN status -->
                <div id="fieldsGiven" class="space-y-3 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Actual Administration Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="actual_administered_at" id="actualAdminTime" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Dose Quantity Administered
                            </label>
                            <input type="text" name="given_quantity" id="givenQuantity" 
                                   placeholder="e.g. 1 Tablet, 5 ml, 1 Injection"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Route Administered
                        </label>
                        <select name="route_administered" id="routeAdministered" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="Oral">Oral (PO)</option>
                            <option value="Intravenous (IV)">Intravenous (IV)</option>
                            <option value="Intramuscular (IM)">Intramuscular (IM)</option>
                            <option value="Subcutaneous (SC)">Subcutaneous (SC)</option>
                            <option value="Inhalation">Inhalation</option>
                            <option value="Topical">Topical</option>
                            <option value="Sublingual">Sublingual</option>
                            <option value="Per Rectum (PR)">Per Rectum (PR)</option>
                            <option value="Ophthalmic">Ophthalmic (Eye Drops)</option>
                            <option value="Nasal">Nasal Spray</option>
                        </select>
                    </div>
                </div>

                <!-- Remarks / Reason Section -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        <span id="remarksLabel">Remarks / Clinical Notes</span> 
                        <span id="remarksRequired" class="text-red-500 hidden">* (Mandatory for Not Given, Held, or Refused)</span>
                    </label>
                    <textarea name="remarks" id="doseRemarks" rows="2"
                              placeholder="Add reason or clinical observation..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>

                    <!-- Quick Reason Chips (shown when not given, held, or refused) -->
                    <div id="quickReasonChips" class="flex flex-wrap gap-1.5 mt-2 hidden">
                        <span class="text-[11px] text-gray-500 self-center mr-1">Quick reasons:</span>
                        <button type="button" onclick="setRemark('Patient was sleeping')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">Patient Sleeping</button>
                        <button type="button" onclick="setRemark('Patient vomited prior to dose')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">Patient Vomiting</button>
                        <button type="button" onclick="setRemark('NPO / Fasting for investigation')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">NPO / Fasting</button>
                        <button type="button" onclick="setRemark('Patient refused to take medication')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">Patient Refused</button>
                        <button type="button" onclick="setRemark('Doctor advised to hold dose temporarily')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">Doctor Advised Hold</button>
                        <button type="button" onclick="setRemark('Medicine out of stock in pharmacy')" class="text-[11px] px-2 py-0.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full border border-gray-300">Out of Stock</button>
                    </div>
                </div>

                <!-- Automatic Staff & Server Audit Trail Notice -->
                <div class="p-3 bg-gray-100/80 rounded-lg border border-gray-200 text-[11px] text-gray-600 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-id-badge text-blue-600 text-base"></i>
                        <div>
                            <div>Administering Staff: <strong class="text-gray-900">{{ auth()->user()->name }}</strong> (Staff ID: <span class="font-mono text-blue-700">EMP-{{ auth()->user()->id }}</span>)</div>
                            <div class="text-[10px] text-gray-400">Timestamp will be permanently registered into hospital legal audit log.</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-semibold border border-blue-200">
                        Audit Active
                    </span>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
                    <button type="button" onclick="closeDoseAdminModal()" 
                            class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitDose"
                            class="px-5 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow transition-colors flex items-center">
                        <i class="fas fa-save mr-1.5"></i> Save Administration Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. PRESCRIBE / ADD MEDICATION ORDER MODAL -->
<div id="addMedicationModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAddMedicationModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-white/10 rounded-lg">
                        <i class="fas fa-prescription-bottle-alt text-lg"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-white">Prescribe Ongoing Medication</h3>
                        <p class="text-xs text-blue-200">Orders automatically generate scheduled doses according to frequency</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddMedicationModal()" class="text-white hover:text-gray-200 text-xl font-bold">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.ipd.medication.store_order', $ipd_patient->id) }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Medicine Name with Autocomplete -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">
                        Medicine Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="medicine_name" list="masterMedicinesList" required
                           placeholder="Type or select medicine (e.g. Paracetamol 650mg, Inj. Ceftriaxone 1g)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <datalist id="masterMedicinesList">
                        @if(isset($medicines))
                            @foreach($medicines as $m)
                                <option value="{{ $m->medicine }}">{{ $m->medicine }}</option>
                            @endforeach
                        @endif
                    </datalist>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dose / Strength</label>
                        <input type="text" name="dose" placeholder="e.g. 500 mg, 1 g, 10 ml, 2 puffs"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dose Type</label>
                        <select name="dose_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="Tablet">Tablet</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Injection (IV)">Injection (IV)</option>
                            <option value="Injection (IM)">Injection (IM)</option>
                            <option value="IV Infusion">IV Infusion</option>
                            <option value="Drops">Drops</option>
                            <option value="Inhaler">Inhaler</option>
                            <option value="Ointment">Ointment / Gel</option>
                            <option value="Suppository">Suppository</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Route</label>
                        <select name="route" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="Oral">Oral (PO)</option>
                            <option value="Intravenous (IV)">Intravenous (IV)</option>
                            <option value="Intramuscular (IM)">Intramuscular (IM)</option>
                            <option value="Subcutaneous (SC)">Subcutaneous (SC)</option>
                            <option value="Inhalation">Inhalation</option>
                            <option value="Topical">Topical</option>
                            <option value="Sublingual">Sublingual</option>
                            <option value="Per Rectum (PR)">Per Rectum (PR)</option>
                            <option value="Ophthalmic">Ophthalmic</option>
                            <option value="Nasal">Nasal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            Frequency <span class="text-red-500">*</span>
                        </label>
                        <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="OD">OD - Once Daily (09:00 AM)</option>
                            <option value="BD">BD - Twice Daily (09:00 AM, 09:00 PM)</option>
                            <option value="TDS">TDS - Thrice Daily (08:00 AM, 02:00 PM, 08:00 PM)</option>
                            <option value="QID">QID - 4 Times Daily (06:00, 12:00, 18:00, 22:00)</option>
                            <option value="Q4H">Q4H - Every 4 Hours (6 times/day)</option>
                            <option value="Q6H">Q6H - Every 6 Hours (4 times/day)</option>
                            <option value="Q8H">Q8H - Every 8 Hours (3 times/day)</option>
                            <option value="STAT">STAT - Single Immediate Dose</option>
                            <option value="SOS">SOS / PRN - As Needed (On Demand)</option>
                            <option value="HS">HS - At Bedtime (09:00 PM)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Duration</label>
                        <select name="duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="1 Day">1 Day</option>
                            <option value="2 Days">2 Days</option>
                            <option value="3 Days">3 Days</option>
                            <option value="5 Days" selected>5 Days</option>
                            <option value="7 Days">7 Days</option>
                            <option value="10 Days">10 Days</option>
                            <option value="14 Days">14 Days</option>
                            <option value="30 Days">30 Days</option>
                            <option value="Ongoing">Ongoing (Continuous)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date & Time</label>
                        <input type="datetime-local" name="start_date" value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Special Doctor Instructions</label>
                    <textarea name="instructions" rows="2" 
                              placeholder="e.g. Take after meals with warm water. Monitor BP before administration."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
                    <button type="button" onclick="closeAddMedicationModal()" 
                            class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition-colors">
                        <i class="fas fa-plus-circle mr-1.5"></i> Order & Generate Scheduled Doses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. GIVE SOS / PRN DOSE MODAL -->
<div id="sosModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeSosModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
            <div class="bg-gradient-to-r from-red-600 to-rose-700 px-6 py-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-bolt text-lg"></i>
                    <div>
                        <h3 class="text-base font-bold text-white">Administer SOS / PRN Dose</h3>
                        <p class="text-xs text-red-100" id="sosMedName"></p>
                    </div>
                </div>
                <button type="button" onclick="closeSosModal()" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>

            <form id="sosForm" method="POST" action="" class="p-6 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Administration Date & Time</label>
                    <input type="datetime-local" name="actual_administered_at" value="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Quantity Given</label>
                        <input type="text" name="given_quantity" id="sosQuantity" placeholder="e.g. 1 Tab"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Route</label>
                        <input type="text" name="route_administered" id="sosRoute" placeholder="Oral, IV, etc."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Reason / Indication for SOS</label>
                    <textarea name="remarks" rows="2" placeholder="e.g. High fever spike 102 F, Severe abdominal pain"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2 border-t border-gray-200">
                    <button type="button" onclick="closeSosModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow">Confirm & Record Dose</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. DISCONTINUE MEDICATION MODAL -->
<div id="discontinueModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDiscontinueModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
            <div class="bg-gray-800 px-6 py-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-ban text-red-400 text-lg"></i>
                    <div>
                        <h3 class="text-base font-bold text-white">Discontinue Medication</h3>
                        <p class="text-xs text-gray-300" id="discontinueMedName"></p>
                    </div>
                </div>
                <button type="button" onclick="closeDiscontinueModal()" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>

            <form id="discontinueForm" method="POST" action="" class="p-6 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">
                        Reason for Discontinuation <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" required rows="3" placeholder="e.g. Course completed, Changed to oral therapy, Adverse reaction observed"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:outline-none"></textarea>
                </div>

                <div class="p-3 bg-amber-50 text-amber-900 border border-amber-200 rounded-lg text-[11px]">
                    <i class="fas fa-exclamation-triangle mr-1 text-amber-500"></i>
                    Stopping this medication will remove all future pending scheduled doses from the MAR chart. Past given records will remain intact.
                </div>

                <div class="flex justify-end space-x-2 pt-2 border-t border-gray-200">
                    <button type="button" onclick="closeDiscontinueModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow">Confirm Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts for MAR interaction -->
<script>
    function openDoseAdminModal(doseId, medName, doseAmount, scheduledTime, route, instructions, currentStatus, quantityGiven, remarks) {
        document.getElementById('modalMedTitle').innerText = medName;
        document.getElementById('modalMedSubtitle').innerText = (route || 'Oral') + ' • ' + (doseAmount || '1 Unit');
        document.getElementById('modalScheduledTime').innerText = scheduledTime;
        document.getElementById('modalPrescribedDose').innerText = doseAmount || '--';
        document.getElementById('givenQuantity').value = quantityGiven || doseAmount || '1 Unit';
        
        if (route) {
            var routeSelect = document.getElementById('routeAdministered');
            for (var i = 0; i < routeSelect.options.length; i++) {
                if (routeSelect.options[i].value.toLowerCase().includes(route.toLowerCase())) {
                    routeSelect.selectedIndex = i;
                    break;
                }
            }
        }

        // Instructions
        var instBox = document.getElementById('modalInstructionsBox');
        if (instructions && instructions.trim() !== '') {
            document.getElementById('modalInstructionsText').innerText = instructions;
            instBox.classList.remove('hidden');
        } else {
            instBox.classList.add('hidden');
        }

        // Remarks
        document.getElementById('doseRemarks').value = remarks || '';

        // Form Action
        document.getElementById('doseAdminForm').action = "{{ url('/admin/ipd-patient/medication-chart/administer') }}/" + doseId;

        // Select initial status
        selectStatus(currentStatus && currentStatus !== 'scheduled' ? currentStatus : 'given');

        document.getElementById('doseAdminModal').classList.remove('hidden');
    }

    function closeDoseAdminModal() {
        document.getElementById('doseAdminModal').classList.add('hidden');
    }

    function selectStatus(status) {
        document.getElementById('adminStatus').value = status;

        // Reset all buttons
        var buttons = document.querySelectorAll('.status-btn');
        buttons.forEach(function(b) {
            b.classList.remove('border-green-500', 'bg-green-50', 'text-green-800',
                               'border-rose-500', 'bg-rose-50', 'text-rose-800',
                               'border-orange-500', 'bg-orange-50', 'text-orange-800',
                               'border-purple-500', 'bg-purple-50', 'text-purple-800',
                               'shadow-sm');
            b.classList.add('border-gray-200', 'text-gray-600');
        });

        var activeBtn = document.getElementById('btnStatus_' + status);
        var fieldsGiven = document.getElementById('fieldsGiven');
        var quickChips = document.getElementById('quickReasonChips');
        var reqSpan = document.getElementById('remarksRequired');
        var remarksArea = document.getElementById('doseRemarks');
        var submitBtn = document.getElementById('btnSubmitDose');

        if (status === 'given') {
            activeBtn.classList.remove('border-gray-200', 'text-gray-600');
            activeBtn.classList.add('border-green-500', 'bg-green-50', 'text-green-800', 'shadow-sm');
            fieldsGiven.classList.remove('hidden');
            quickChips.classList.add('hidden');
            reqSpan.classList.add('hidden');
            remarksArea.removeAttribute('required');
            submitBtn.className = "px-5 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow transition-colors flex items-center";
            submitBtn.innerHTML = '<i class="fas fa-check-circle mr-1.5"></i> Record Dose as Given';
        } else {
            fieldsGiven.classList.add('hidden');
            quickChips.classList.remove('hidden');
            reqSpan.classList.remove('hidden');
            remarksArea.setAttribute('required', 'required');

            if (status === 'not_given') {
                activeBtn.classList.remove('border-gray-200', 'text-gray-600');
                activeBtn.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-800', 'shadow-sm');
                submitBtn.className = "px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow transition-colors flex items-center";
                submitBtn.innerHTML = '<i class="fas fa-times-circle mr-1.5"></i> Record as Not Given';
            } else if (status === 'held') {
                activeBtn.classList.remove('border-gray-200', 'text-gray-600');
                activeBtn.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-800', 'shadow-sm');
                submitBtn.className = "px-5 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-lg shadow transition-colors flex items-center";
                submitBtn.innerHTML = '<i class="fas fa-pause-circle mr-1.5"></i> Record as Skipped / Held';
            } else if (status === 'refused') {
                activeBtn.classList.remove('border-gray-200', 'text-gray-600');
                activeBtn.classList.add('border-purple-500', 'bg-purple-50', 'text-purple-800', 'shadow-sm');
                submitBtn.className = "px-5 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow transition-colors flex items-center";
                submitBtn.innerHTML = '<i class="fas fa-user-slash mr-1.5"></i> Record as Refused by Patient';
            }
        }
    }

    function setRemark(text) {
        document.getElementById('doseRemarks').value = text;
    }

    function openAddMedicationModal() {
        document.getElementById('addMedicationModal').classList.remove('hidden');
    }

    function closeAddMedicationModal() {
        document.getElementById('addMedicationModal').classList.add('hidden');
    }

    function openSosModal(medId, medName, dose, route) {
        document.getElementById('sosMedName').innerText = medName + ' (' + (dose || '') + ')';
        document.getElementById('sosQuantity').value = dose || '1 Unit';
        document.getElementById('sosRoute').value = route || 'Oral';
        document.getElementById('sosForm').action = "{{ url('/admin/ipd-patient/medication-chart/sos') }}/" + medId;
        document.getElementById('sosModal').classList.remove('hidden');
    }

    function closeSosModal() {
        document.getElementById('sosModal').classList.add('hidden');
    }

    function openDiscontinueModal(medId, medName) {
        document.getElementById('discontinueMedName').innerText = medName;
        document.getElementById('discontinueForm').action = "{{ url('/admin/ipd-patient/medication-chart/discontinue') }}/" + medId;
        document.getElementById('discontinueModal').classList.remove('hidden');
    }

    function closeDiscontinueModal() {
        document.getElementById('discontinueModal').classList.add('hidden');
    }

    function filterMarTable() {
        var input = document.getElementById('marSearchInput');
        var filter = input.value.toLowerCase();
        var table = document.getElementById('marHistoryTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var text = tr[i].textContent || tr[i].innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
