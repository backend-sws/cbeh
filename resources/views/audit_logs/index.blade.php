@extends('layouts.admin')

@section('title', 'Audit Logs')

@push('css')
<style>
    .diff-old {
        background-color: #fee2e2;
        color: #991b1b;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: monospace;
        font-size: 0.85rem;
    }
    .diff-new {
        background-color: #dcfce7;
        color: #166534;
        border-radius: 4px;
        padding: 2px 6px;
        font-family: monospace;
        font-size: 0.85rem;
    }
    .badge-action-created {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .badge-action-updated {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .badge-action-deleted {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    .badge-action-default {
        background-color: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-lg shadow-sm">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Audit Logs & Activity Trail</h1>
                        <p class="text-gray-500 text-sm">Comprehensive tracking of all system modifications, additions, and deletions</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.audit_logs.export', request()->query()) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition shadow-sm hover:shadow">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            {{-- Total Logs --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Logs</p>
                        <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-database text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Today's Logs --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Today</p>
                        <h3 class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($stats['today']) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-calendar-day text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Creates --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Created</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($stats['created']) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-plus-circle text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Updates --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Updated</p>
                        <h3 class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($stats['updated']) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fas fa-edit text-lg"></i>
                    </div>
                </div>
            </div>

            {{-- Deletes --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 col-span-2 md:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Deleted</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ number_format($stats['deleted']) }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fas fa-trash-alt text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.audit_logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search title, user, desc, IP..."
                               class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    </div>
                </div>

                {{-- Module Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                    <select name="module" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">All Modules</option>
                        @foreach($modules as $m)
                            <option value="{{ $m }}" {{ $module === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                    <select name="action" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">All Actions</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- User Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User / Staff</label>
                    <select name="user_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ (string)$userId === (string)$u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role ?? 'User' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- From Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                {{-- To Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ $toDate }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                {{-- Per Page / Entries --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Entries Per Page</label>
                    <select name="perPage" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="20" {{ (string)$perPage === '20' ? 'selected' : '' }}>20 Entries</option>
                        <option value="50" {{ (string)$perPage === '50' ? 'selected' : '' }}>50 Entries</option>
                        <option value="100" {{ (string)$perPage === '100' ? 'selected' : '' }}>100 Entries</option>
                    </select>
                </div>

                {{-- Filter & Reset Buttons --}}
                <div class="flex space-x-2">
                    <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-md hover:bg-purple-700 transition shadow-sm h-[38px]">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.audit_logs.index') }}"
                       class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-md hover:bg-gray-300 transition h-[38px]">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Audit Logs Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left">Timestamp</th>
                            <th scope="col" class="px-5 py-3 text-left">User</th>
                            <th scope="col" class="px-5 py-3 text-left">Module</th>
                            <th scope="col" class="px-5 py-3 text-center">Action</th>
                            <th scope="col" class="px-5 py-3 text-left">Record</th>
                            <th scope="col" class="px-5 py-3 text-left">Description</th>
                            <th scope="col" class="px-5 py-3 text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white text-sm">
                        @forelse($auditLogs as $log)
                            @php
                                $actionClass = match(strtolower($log->action)) {
                                    'created' => 'badge-action-created',
                                    'updated' => 'badge-action-updated',
                                    'deleted' => 'badge-action-deleted',
                                    default => 'badge-action-default'
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                {{-- Timestamp --}}
                                <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-600">
                                    <div class="font-medium text-gray-800">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $log->created_at->format('h:i:s A') }} ({{ $log->created_at->diffForHumans() }})</div>
                                </td>

                                {{-- User --}}
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($log->user_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800 text-xs">{{ $log->user_name ?? 'System' }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">{{ $log->user_role ?? 'System' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Module --}}
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $log->module }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $actionClass }}">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                {{-- Record --}}
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900 text-xs">
                                        {{ $log->record_title ?? ('#' . $log->auditable_id) }}
                                    </div>
                                    @if($log->auditable_type)
                                        <div class="text-[10px] text-gray-400">
                                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="px-5 py-3 text-xs text-gray-600 max-w-md">
                                    <p class="truncate" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </p>
                                    @if(!empty($log->changed_fields))
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach(array_slice($log->changed_fields, 0, 3) as $field)
                                                <span class="px-1.5 py-0.2 bg-gray-100 text-gray-600 rounded text-[10px]">
                                                    {{ $field }}
                                                </span>
                                            @endforeach
                                            @if(count($log->changed_fields) > 3)
                                                <span class="text-[10px] text-gray-400">+{{ count($log->changed_fields) - 3 }} more</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                {{-- Diff Button --}}
                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <button type="button"
                                            onclick="openAuditModal({{ $log->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg text-xs font-semibold transition border border-purple-200">
                                        <i class="fas fa-eye mr-1.5"></i> Inspect
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3 text-2xl">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <h4 class="text-base font-semibold text-gray-700">No Audit Logs Found</h4>
                                        <p class="text-xs text-gray-400 mt-1 max-w-sm">No actions recorded matching the selected filter criteria. Perform actions in the application to see audit entries here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($auditLogs->hasPages())
                <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} audit entries
                    </div>
                    <div>
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Detail / Diff Modal --}}
<div id="auditModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden transform transition-all">
        
        {{-- Modal Header --}}
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div id="modalActionBadge" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"></div>
                <div>
                    <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Audit Event Details</h3>
                    <p id="modalSubtitle" class="text-xs text-gray-500 mt-0.5"></p>
                </div>
            </div>
            <button type="button" onclick="closeAuditModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Modal Content --}}
        <div class="p-6 overflow-y-auto space-y-6">
            {{-- Summary Banner --}}
            <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl">
                <div class="text-xs font-semibold text-purple-900 uppercase tracking-wider mb-1">Action Summary</div>
                <div id="modalDescription" class="text-sm font-medium text-purple-950"></div>
            </div>

            {{-- Changes Table (Old vs New Diff) --}}
            <div id="diffSection">
                <h4 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-3">Field-Level Changes (Old vs New)</h4>
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-[11px] font-bold text-gray-600 uppercase">
                            <tr>
                                <th class="px-4 py-2.5 text-left w-1/4">Field</th>
                                <th class="px-4 py-2.5 text-left w-3/8 text-rose-700 bg-rose-50/50">Previous Value</th>
                                <th class="px-4 py-2.5 text-left w-3/8 text-emerald-700 bg-emerald-50/50">New Value</th>
                            </tr>
                        </thead>
                        <tbody id="diffTableBody" class="divide-y divide-gray-100 text-xs bg-white">
                            {{-- Rows dynamically populated --}}
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Technical Metadata --}}
            <div>
                <h4 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-3">Request & Security Metadata</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="block text-[10px] text-gray-400 font-semibold uppercase">IP Address</span>
                        <span id="modalIp" class="text-xs font-bold text-gray-800 font-mono"></span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="block text-[10px] text-gray-400 font-semibold uppercase">HTTP Method</span>
                        <span id="modalMethod" class="text-xs font-bold text-gray-800"></span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="block text-[10px] text-gray-400 font-semibold uppercase">User Role</span>
                        <span id="modalRole" class="text-xs font-bold text-purple-700"></span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <span class="block text-[10px] text-gray-400 font-semibold uppercase">Audit Log ID</span>
                        <span id="modalLogId" class="text-xs font-bold text-gray-800"></span>
                    </div>
                </div>
                <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-100 text-xs text-gray-600">
                    <span class="block text-[10px] text-gray-400 font-semibold uppercase mb-0.5">Request URL</span>
                    <span id="modalUrl" class="font-mono text-[11px] break-all"></span>
                </div>
                <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-100 text-xs text-gray-600">
                    <span class="block text-[10px] text-gray-400 font-semibold uppercase mb-0.5">User Agent</span>
                    <span id="modalUserAgent" class="text-[11px] text-gray-500 break-all"></span>
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button type="button" onclick="closeAuditModal()"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function openAuditModal(id) {
        const modal = document.getElementById('auditModal');
        modal.classList.remove('hidden');

        // Fetch detail
        fetch(`{{ url('admin/audit-logs') }}/${id}`)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    const data = response.data;
                    document.getElementById('modalTitle').innerText = `${data.module} - ${data.record_title}`;
                    document.getElementById('modalSubtitle').innerText = `By ${data.user_name} (${data.user_role}) on ${data.created_at}`;
                    document.getElementById('modalDescription').innerText = data.description;
                    document.getElementById('modalIp').innerText = data.ip_address;
                    document.getElementById('modalMethod').innerText = data.method;
                    document.getElementById('modalRole').innerText = data.user_role;
                    document.getElementById('modalLogId').innerText = `#${data.id}`;
                    document.getElementById('modalUrl').innerText = data.url;
                    document.getElementById('modalUserAgent').innerText = data.user_agent;

                    // Action badge
                    const badge = document.getElementById('modalActionBadge');
                    badge.innerText = data.action;
                    badge.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider ';
                    if (data.action === 'CREATED') {
                        badge.className += 'badge-action-created';
                    } else if (data.action === 'UPDATED') {
                        badge.className += 'badge-action-updated';
                    } else if (data.action === 'DELETED') {
                        badge.className += 'badge-action-deleted';
                    } else {
                        badge.className += 'badge-action-default';
                    }

                    // Diff Table
                    const tbody = document.getElementById('diffTableBody');
                    tbody.innerHTML = '';

                    const oldVals = data.old_values || {};
                    const newVals = data.new_values || {};
                    const allKeys = Array.from(new Set([...Object.keys(oldVals), ...Object.keys(newVals)]));

                    if (allKeys.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-400">No attribute snapshots captured.</td></tr>';
                    } else {
                        allKeys.forEach(key => {
                            const oldVal = oldVals[key] !== undefined ? JSON.stringify(oldVals[key]) : '<span class="text-gray-300 font-normal">None</span>';
                            const newVal = newVals[key] !== undefined ? JSON.stringify(newVals[key]) : '<span class="text-gray-300 font-normal">None</span>';
                            const isChanged = oldVals[key] !== undefined && newVals[key] !== undefined && oldVals[key] !== newVals[key];

                            const tr = document.createElement('tr');
                            tr.className = isChanged ? 'bg-amber-50/40 font-medium' : '';
                            tr.innerHTML = `
                                <td class="px-4 py-2 font-mono text-[11px] text-gray-800">
                                    ${key}
                                    ${isChanged ? '<span class="ml-1 text-[9px] text-amber-700 bg-amber-100 px-1 py-0.5 rounded font-sans">Changed</span>' : ''}
                                </td>
                                <td class="px-4 py-2 text-rose-800 break-all font-mono text-[11px] bg-rose-50/20">${oldVal}</td>
                                <td class="px-4 py-2 text-emerald-800 break-all font-mono text-[11px] bg-emerald-50/20">${newVal}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }
                }
            })
            .catch(err => {
                console.error(err);
                alert("Failed to load audit log details.");
            });
    }

    function closeAuditModal() {
        document.getElementById('auditModal').classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAuditModal();
    });
</script>
@endpush
