@php
    $status = $dose->status;
    $timeStr = $dose->scheduled_at ? $dose->scheduled_at->format('h:i A') : '--';
    $med = $dose->medication;
    $medName = $med->medicine_name ?? 'Medication';
    $doseAmt = $med->dose ?? '';
    $route = $dose->route_administered ?: ($med->route ?? 'Oral');
    $instructions = $med->instructions ?? '';
    $quantity = $dose->given_quantity ?: ($med->dose ?? '1 Unit');
    $remarks = $dose->remarks ?? '';

    $borderClass = 'border-gray-200 bg-white hover:border-blue-400';
    $badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
    $badgeText = 'Due / Scheduled';
    $badgeIcon = 'fa-clock text-amber-500';

    if ($status === 'given') {
        $borderClass = 'border-green-300 bg-green-50/40 hover:border-green-500';
        $badgeClass = 'bg-green-100 text-green-800 border-green-200';
        $badgeText = 'Given';
        $badgeIcon = 'fa-check-circle text-green-600';
    } elseif ($status === 'not_given') {
        $borderClass = 'border-rose-300 bg-rose-50/40 hover:border-rose-500';
        $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
        $badgeText = 'Not Given';
        $badgeIcon = 'fa-times-circle text-rose-600';
    } elseif ($status === 'held') {
        $borderClass = 'border-orange-300 bg-orange-50/40 hover:border-orange-500';
        $badgeClass = 'bg-orange-100 text-orange-800 border-orange-200';
        $badgeText = 'Skipped / Held';
        $badgeIcon = 'fa-pause-circle text-orange-600';
    } elseif ($status === 'refused') {
        $borderClass = 'border-purple-300 bg-purple-50/40 hover:border-purple-500';
        $badgeClass = 'bg-purple-100 text-purple-800 border-purple-200';
        $badgeText = 'Refused';
        $badgeIcon = 'fa-user-slash text-purple-600';
    }
@endphp

<div class="p-3.5 rounded-xl border {{ $borderClass }} shadow-sm transition-all flex flex-col justify-between space-y-2.5">
    <div>
        <!-- Top Bar: Scheduled Time & Status Badge -->
        <div class="flex items-center justify-between">
            <span class="font-extrabold text-sm text-gray-900 flex items-center">
                <i class="far fa-clock mr-1.5 text-blue-600 text-xs"></i> {{ $timeStr }}
            </span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $badgeClass }}">
                <i class="fas {{ $badgeIcon }} mr-1 text-[10px]"></i> {{ $badgeText }}
            </span>
        </div>

        <!-- Medicine Name & Strength -->
        <div class="mt-2">
            <h4 class="font-bold text-gray-900 text-sm leading-tight flex items-baseline">
                {{ $medName }}
            </h4>
            <div class="flex items-center space-x-2 text-xs text-gray-600 mt-1">
                @if($doseAmt)
                    <span class="font-semibold text-gray-800">{{ $doseAmt }}</span>
                    <span>•</span>
                @endif
                <span class="text-blue-700 font-medium">{{ $route }}</span>
                <span>•</span>
                <span class="text-gray-500">{{ $med->dose_type ?: 'Tablet' }}</span>
            </div>
        </div>

        <!-- Special Doctor Instructions if available -->
        @if(!empty($instructions))
            <div class="mt-2 p-1.5 bg-amber-50 rounded border border-amber-200/70 text-[11px] text-amber-900 flex items-start">
                <i class="fas fa-info-circle text-amber-500 mr-1.5 mt-0.5 flex-shrink-0"></i>
                <span class="line-clamp-2" title="{{ $instructions }}">{{ $instructions }}</span>
            </div>
        @endif

        <!-- Execution Details if administered -->
        @if($status !== 'scheduled')
            <div class="mt-2.5 pt-2 border-t border-gray-200/60 text-xs space-y-1">
                @if($status === 'given')
                    <div class="text-green-900 font-medium flex items-center justify-between text-[11px]">
                        <span>Actual Time: <strong>{{ $dose->actual_administered_at ? $dose->actual_administered_at->format('h:i A') : '--' }}</strong></span>
                        <span class="text-green-700 font-semibold">{{ $quantity }}</span>
                    </div>
                @endif

                @if(!empty($dose->staff_name))
                    <div class="text-[11px] text-gray-600 flex items-center justify-between">
                        <span>Staff: <strong class="text-gray-800">{{ $dose->staff_name }}</strong></span>
                        <span class="font-mono text-[10px] text-gray-400">{{ $dose->staff_id }}</span>
                    </div>
                @endif

                @if(!empty($remarks))
                    <div class="text-[11px] text-gray-700 italic bg-white/80 p-1.5 rounded border border-gray-200 mt-1">
                        "{{ $remarks }}"
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Action Button -->
    <div class="pt-2">
        <button type="button" 
                onclick="openDoseAdminModal(
                    {{ $dose->id }},
                    '{{ addslashes($medName) }}',
                    '{{ addslashes($doseAmt) }}',
                    '{{ $timeStr }}',
                    '{{ addslashes($route) }}',
                    '{{ addslashes($instructions) }}',
                    '{{ $status }}',
                    '{{ addslashes($quantity) }}',
                    '{{ addslashes($remarks) }}'
                )"
                class="w-full py-1.5 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center 
                {{ $status === 'scheduled' 
                    ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm' 
                    : 'bg-white hover:bg-gray-100 text-gray-700 border border-gray-300' }}">
            @if($status === 'scheduled')
                <i class="fas fa-hand-holding-medical mr-1.5"></i> Administer / Mark Dose
            @else
                <i class="fas fa-edit mr-1.5 text-gray-500"></i> Update Administration
            @endif
        </button>
    </div>
</div>
