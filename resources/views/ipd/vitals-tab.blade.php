<div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200">
        <div>
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-heartbeat text-blue-600 mr-2"></i> In-Patient Vitals & Ward Rounds Chart
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Track vitals across daily ward rounds (Morning, Evening, Night shifts)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ipd.vitals.chart', $ipd_patient->id) }}" target="_blank"
                class="inline-flex items-center px-3.5 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                <i class="fas fa-print mr-1.5 text-gray-500"></i> Print Vitals Chart
            </a>
            <button type="button" onclick="openVitalsModal()"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors">
                <i class="fas fa-plus mr-1.5"></i> Record Vitals (Ward Round)
            </button>
        </div>
    </div>

    @if(isset($vitals_history) && $vitals_history->count() > 0)
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-xs">
            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Date & Time</th>
                    <th class="px-3 py-3 text-center">Shift</th>
                    <th class="px-3 py-3 text-center">BP (mmHg)</th>
                    <th class="px-3 py-3 text-center">Pulse</th>
                    <th class="px-3 py-3 text-center">Temp (°F)</th>
                    <th class="px-3 py-3 text-center">SpO2</th>
                    <th class="px-3 py-3 text-center">RR</th>
                    <th class="px-3 py-3 text-center">RBS</th>
                    <th class="px-3 py-3 text-center">Wt / BMI</th>
                    <th class="px-4 py-3 text-left">Systemic / Notes</th>
                    <th class="px-3 py-3 text-left">Recorded By</th>
                    <th class="px-3 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach($vitals_history as $v)
                <tr class="hover:bg-blue-50/40 transition-colors">
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($v->recorded_at)->format('d-m-Y') }}
                        <div class="text-[11px] text-gray-500 font-normal">{{ \Carbon\Carbon::parse($v->recorded_at)->format('h:i A') }}</div>
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        @php
                            $shiftClass = match(strtolower($v->shift ?? '')) {
                                'morning' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'evening' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'night' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $shiftClass }}">
                            {{ $v->shift ?: 'Round' }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-center font-bold text-gray-800 whitespace-nowrap">
                        {{ $v->blood_pressure ?: '-' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        {{ $v->pulse ? $v->pulse . ' bpm' : '-' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        {{ $v->temperature ? $v->temperature . ' °F' : '-' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        @if($v->spo2)
                            <span class="font-semibold {{ intval($v->spo2) < 95 ? 'text-red-600' : 'text-green-700' }}">
                                {{ $v->spo2 }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        {{ $v->respiratory_rate ? $v->respiratory_rate . '/min' : '-' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap font-medium">
                        {{ $v->rbs ? $v->rbs . ' mg/dL' : '-' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        {{ $v->weight ? $v->weight . ' kg' : '-' }}
                        @if($v->bmi)
                            <div class="text-[10px] text-gray-500 font-medium">BMI: {{ $v->bmi }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-700 max-w-xs">
                        @if(!empty($v->general_appearance))
                            <span class="font-semibold text-gray-600">GA:</span> {{ $v->general_appearance }}<br>
                        @endif
                        @if(!empty($v->cvs))
                            <span class="font-semibold text-gray-600">CVS:</span> {{ $v->cvs }}<br>
                        @endif
                        @if(!empty($v->rs))
                            <span class="font-semibold text-gray-600">RS:</span> {{ $v->rs }}<br>
                        @endif
                        @if(!empty($v->notes))
                            <span class="text-gray-500 italic">{{ Str::limit($v->notes, 60) }}</span>
                        @endif
                        @if(empty($v->general_appearance) && empty($v->cvs) && empty($v->rs) && empty($v->notes))
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-gray-600 whitespace-nowrap">
                        {{ $v->recorded_by ?: 'Staff' }}
                    </td>
                    <td class="px-3 py-3 text-center whitespace-nowrap">
                        <form action="{{ route('admin.ipd.vitals.destroy', $v->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this vitals entry?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
        <i class="fas fa-heartbeat text-gray-300 text-4xl mb-3"></i>
        <h4 class="text-sm font-semibold text-gray-700 mb-1">No Vitals Recorded Yet</h4>
        <p class="text-xs text-gray-500 mb-4">Record routine ward round vitals for Morning, Evening, and Night shifts.</p>
        <button type="button" onclick="openVitalsModal()"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-xs font-medium text-white bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus mr-1.5"></i> Record First Vitals Entry
        </button>
    </div>
    @endif
</div>
