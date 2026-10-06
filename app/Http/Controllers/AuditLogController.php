<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 20);
        $search = $request->input('search', '');
        $userId = $request->input('user_id');
        $action = $request->input('action');
        $module = $request->input('module');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = AuditLog::with('user')->latest('id');

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('record_title', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('auditable_id', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filters
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        if (!empty($action)) {
            $query->where('action', $action);
        }

        if (!empty($module)) {
            $query->where('module', $module);
        }

        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereDate('created_at', '>=', $fromDate)
                  ->whereDate('created_at', '<=', $toDate);
        } elseif (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        } elseif (!empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $auditLogs = $query->paginate($perPage)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', Carbon::today())->count(),
            'created' => AuditLog::where('action', 'created')->count(),
            'updated' => AuditLog::where('action', 'updated')->count(),
            'deleted' => AuditLog::where('action', 'deleted')->count(),
        ];

        // Dropdown options
        $users = User::orderBy('name')->get(['id', 'name', 'role']);
        $modules = AuditLog::select('module')->distinct()->whereNotNull('module')->orderBy('module')->pluck('module');
        $actions = AuditLog::select('action')->distinct()->whereNotNull('action')->orderBy('action')->pluck('action');

        return view('audit_logs.index', compact(
            'auditLogs',
            'stats',
            'users',
            'modules',
            'actions',
            'search',
            'userId',
            'action',
            'module',
            'fromDate',
            'toDate',
            'perPage'
        ));
    }

    /**
     * Display the specified audit log for modal inspection.
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $log->id,
                'created_at' => $log->created_at ? $log->created_at->format('M d, Y h:i:s A') : 'N/A',
                'user_name' => $log->user_name ?? ($log->user->name ?? 'System'),
                'user_role' => $log->user_role ?? 'N/A',
                'action' => strtoupper($log->action),
                'module' => $log->module,
                'record_title' => $log->record_title ?? 'N/A',
                'auditable_type' => $log->auditable_type ? class_basename($log->auditable_type) : 'N/A',
                'auditable_id' => $log->auditable_id ?? 'N/A',
                'description' => $log->description,
                'ip_address' => $log->ip_address ?? 'N/A',
                'user_agent' => $log->user_agent ?? 'N/A',
                'url' => $log->url ?? 'N/A',
                'method' => $log->method ?? 'N/A',
                'old_values' => $log->old_values ?? [],
                'new_values' => $log->new_values ?? [],
                'changed_fields' => $log->changed_fields ?? [],
            ]
        ]);
    }

    /**
     * Stream CSV export of filtered audit logs.
     */
    public function export(Request $request): StreamedResponse
    {
        $search = $request->input('search');
        $userId = $request->input('user_id');
        $action = $request->input('action');
        $module = $request->input('module');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = AuditLog::with('user')->latest('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('record_title', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }
        if (!empty($userId)) $query->where('user_id', $userId);
        if (!empty($action)) $query->where('action', $action);
        if (!empty($module)) $query->where('module', $module);
        if (!empty($fromDate)) $query->whereDate('created_at', '>=', $fromDate);
        if (!empty($toDate)) $query->whereDate('created_at', '<=', $toDate);

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'Timestamp',
                'User',
                'Role',
                'Action',
                'Module',
                'Record Title',
                'Changed Fields',
                'Description',
                'IP Address'
            ]);

            $query->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '',
                        $log->user_name,
                        $log->user_role,
                        strtoupper($log->action),
                        $log->module,
                        $log->record_title,
                        is_array($log->changed_fields) ? implode(', ', $log->changed_fields) : '',
                        $log->description,
                        $log->ip_address,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
