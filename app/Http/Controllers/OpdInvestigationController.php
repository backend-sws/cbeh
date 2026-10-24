<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OpdInvestigation;
use App\Models\OpdPatient;
use App\Models\User;
use Illuminate\Http\Request;

class OpdInvestigationController extends Controller
{
    /**
     * List all investigations for an OPD patient.
     */
    public function index($opdPatientId)
    {
        $investigations = OpdInvestigation::where('opd_patient_id', $opdPatientId)
            ->with(['doctor', 'orderedBy'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $investigations
        ]);
    }

    /**
     * Store new diagnosis & investigation orders (Doctor / Admin).
     */
    public function store(Request $request, $opdPatientId)
    {
        $opdPatient = OpdPatient::findOrFail($opdPatientId);

        $request->validate([
            'diagnosis'           => 'nullable|string',
            'investigation_name'  => 'nullable|string',
            'category'            => 'nullable|string|max:100',
            'urgency'             => 'nullable|string|max:50',
            'instruction'         => 'nullable|string',
            'sample_type'         => 'nullable|string|max:100',
            'doctor_id'           => 'nullable|integer',
            'tests'               => 'nullable|array',
            'tests.*.name'        => 'nullable|string',
            'tests.*.category'    => 'nullable|string',
            'tests.*.sample_type' => 'nullable|string',
        ]);

        $currentUserId = auth()->id();
        $currentUserName = auth()->user() ? auth()->user()->name : 'Staff';
        $doctorId = $request->doctor_id ?: ($opdPatient->doctor_id ?: null);

        $orderedByName = $currentUserName;
        if ($doctorId && $doc = User::find($doctorId)) {
            $orderedByName = $doc->name;
        }

        $created = [];

        // Check if multiple tests were submitted
        if ($request->has('tests') && is_array($request->tests) && count($request->tests) > 0) {
            foreach ($request->tests as $testItem) {
                $testName = trim($testItem['name'] ?? '');
                if (!$testName) continue;

                $created[] = OpdInvestigation::create([
                    'opd_patient_id'     => $opdPatient->id,
                    'patient_id'         => $opdPatient->patient_id,
                    'doctor_id'          => $doctorId,
                    'ordered_by_id'      => $currentUserId,
                    'ordered_by_name'    => $orderedByName,
                    'diagnosis'          => $request->diagnosis,
                    'investigation_name' => $testName,
                    'category'           => $testItem['category'] ?? ($request->category ?: 'Pathology'),
                    'urgency'            => $request->urgency ?: 'Routine',
                    'instruction'        => $request->instruction,
                    'status'             => 'Pending',
                    'sample_type'        => $testItem['sample_type'] ?? $request->sample_type,
                ]);
            }
        } elseif ($request->filled('investigation_name')) {
            $names = preg_split('/[\r\n,]+/', $request->investigation_name);
            foreach ($names as $name) {
                $trimmed = trim($name);
                if (!$trimmed) continue;

                $created[] = OpdInvestigation::create([
                    'opd_patient_id'     => $opdPatient->id,
                    'patient_id'         => $opdPatient->patient_id,
                    'doctor_id'          => $doctorId,
                    'ordered_by_id'      => $currentUserId,
                    'ordered_by_name'    => $orderedByName,
                    'diagnosis'          => $request->diagnosis,
                    'investigation_name' => $trimmed,
                    'category'           => $request->category ?: 'Pathology',
                    'urgency'            => $request->urgency ?: 'Routine',
                    'instruction'        => $request->instruction,
                    'status'             => 'Pending',
                    'sample_type'        => $request->sample_type,
                ]);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please enter at least one investigation / test name.'
            ], 422);
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($created) . ' investigation(s) ordered successfully.',
            'data'    => $created
        ]);
    }

    /**
     * Update the workflow status of an investigation (Nurses / Staff / Doctor / Admin).
     */
    public function updateStatus(Request $request, $id)
    {
        $investigation = OpdInvestigation::findOrFail($id);

        $request->validate([
            'status'         => 'required|string|in:Pending,Sample Collected,Sample Accepted,In Process,Completed,Cancelled',
            'staff_name'     => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
            'sample_type'    => 'nullable|string|max:100',
            'result_summary' => 'nullable|string',
        ]);

        $status = $request->status;

        // Strictly use currently logged-in user name
        $staffName = (auth()->check() && auth()->user()->name)
            ? auth()->user()->name
            : ($request->staff_name ?: 'Staff');

        $investigation->status = $status;

        if ($request->filled('sample_type')) {
            $investigation->sample_type = $request->sample_type;
        }

        if ($status === 'Sample Collected') {
            $investigation->sample_collected_by = $staffName;
            $investigation->sample_collected_at = now();
        } elseif ($status === 'Sample Accepted') {
            if (!$investigation->sample_collected_by) {
                $investigation->sample_collected_by = $staffName;
                $investigation->sample_collected_at = now();
            }
            $investigation->sample_accepted_by = $staffName;
            $investigation->sample_accepted_at = now();
        } elseif ($status === 'In Process') {
            if (!$investigation->sample_accepted_by) {
                $investigation->sample_accepted_by = $staffName;
                $investigation->sample_accepted_at = now();
            }
        } elseif ($status === 'Completed') {
            if ($request->filled('result_summary')) {
                $investigation->result_summary = $request->result_summary;
            }
        }

        if ($request->filled('notes')) {
            $timestamp = now()->format('d M, h:i A');
            $newNote = "[{$timestamp} by {$staffName} - {$status}]: " . trim($request->notes);
            $investigation->process_notes = $investigation->process_notes 
                ? ($investigation->process_notes . "\n" . $newNote) 
                : $newNote;
        }

        $investigation->save();

        return response()->json([
            'status'  => 'success',
            'message' => "Investigation status updated to {$status}.",
            'data'    => $investigation->load(['doctor', 'orderedBy'])
        ]);
    }

    /**
     * Show single investigation.
     */
    public function show($id)
    {
        $investigation = OpdInvestigation::with(['doctor', 'orderedBy', 'opdPatient.patient'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $investigation
        ]);
    }

    /**
     * Delete an investigation order.
     */
    public function destroy($id)
    {
        $investigation = OpdInvestigation::findOrFail($id);
        $investigation->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Investigation order deleted successfully.'
        ]);
    }
}
