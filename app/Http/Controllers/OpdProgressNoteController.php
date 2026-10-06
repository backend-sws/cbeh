<?php

namespace App\Http\Controllers;

use App\Models\OpdPatient;
use App\Models\OpdProgressNote;
use App\Models\OpdProgressNoteMedicine;
use App\Models\OpdProgressNoteAddendum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpdProgressNoteController extends Controller
{
    /**
     * Get all progress notes for an OPD patient.
     */
    public function index($opdPatientId)
    {
        $notes = OpdProgressNote::where('opd_patient_id', $opdPatientId)
            ->with(['medicines.category', 'addendums'])
            ->orderBy('date_time', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($notes);
    }

    /**
     * Store a newly created progress note for OPD.
     */
    public function store(Request $request, $opdPatientId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date_time' => 'required',
            'note' => 'required|string',
            'staff_name' => 'nullable|string|max:255',
        ]);

        $opdPatient = OpdPatient::findOrFail($opdPatientId);

        DB::beginTransaction();
        try {
            $staffName = auth()->user() ? auth()->user()->name : ($request->staff_name ?: 'Staff');

            $progressNote = OpdProgressNote::create([
                'opd_patient_id' => $opdPatient->id,
                'patient_id'     => $opdPatient->patient_id,
                'user_id'        => auth()->id(),
                'staff_name'     => $staffName,
                'date_time'      => $request->date_time,
                'title'          => $request->title,
                'note'           => $request->note,
            ]);

            // Save medicines if provided
            if ($request->has('medicine_name') && is_array($request->medicine_name)) {
                $categoryIds = $request->category_id ?? [];
                $dosages = $request->dosage ?? [];
                $routes = $request->route ?? [];
                $frequencies = $request->dose_interval ?? ($request->frequency ?? []);
                $durations = $request->duration ?? [];
                $instructions = $request->instructions ?? [];

                foreach ($request->medicine_name as $index => $medName) {
                    $trimmedName = trim($medName);
                    if (!empty($trimmedName)) {
                        OpdProgressNoteMedicine::create([
                            'opd_progress_note_id' => $progressNote->id,
                            'category_id'          => !empty($categoryIds[$index]) ? $categoryIds[$index] : null,
                            'medicine_name'        => $trimmedName,
                            'dosage'               => $dosages[$index] ?? null,
                            'route'                => $routes[$index] ?? null,
                            'dose_interval'        => $frequencies[$index] ?? null,
                            'duration'             => $durations[$index] ?? null,
                            'instructions'         => $instructions[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'OPD Progress Note saved successfully!',
                    'data'    => $progressNote->load(['medicines.category', 'addendums']),
                ]);
            }

            return redirect()->back()->with('success', 'OPD Progress Note saved successfully!')->with('active_tab', 'progress-notes');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error saving progress note: ' . $e->getMessage())->with('active_tab', 'progress-notes');
        }
    }

    /**
     * Add an addendum to an existing OPD progress note.
     */
    public function addAddendum(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
            'date_time' => 'nullable',
            'staff_name' => 'nullable|string|max:255',
        ]);

        $progressNote = OpdProgressNote::findOrFail($id);

        try {
            $staffName = auth()->user() ? auth()->user()->name : ($request->staff_name ?: 'Staff');
            $dateTime = $request->date_time ?: now();

            $addendum = OpdProgressNoteAddendum::create([
                'opd_progress_note_id' => $progressNote->id,
                'user_id'              => auth()->id(),
                'staff_name'           => $staffName,
                'date_time'            => $dateTime,
                'note'                 => $request->note,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Addendum added successfully!',
                    'data'    => $addendum,
                ]);
            }

            return redirect()->back()->with('success', 'Addendum added successfully!')->with('active_tab', 'progress-notes');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error adding addendum: ' . $e->getMessage())->with('active_tab', 'progress-notes');
        }
    }

    /**
     * Print a single OPD progress note slip with all medicines and addendums.
     */
    public function print($id)
    {
        $note = OpdProgressNote::with([
            'opdPatient.patient',
            'opdPatient.doctor',
            'medicines.category',
            'addendums'
        ])->findOrFail($id);

        return view('opd.print-progress-note', compact('note'));
    }

    /**
     * Delete an OPD progress note.
     */
    public function destroy($id)
    {
        $note = OpdProgressNote::findOrFail($id);
        $note->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Progress note deleted successfully!']);
        }

        return redirect()->back()->with('success', 'Progress note deleted successfully!')->with('active_tab', 'progress-notes');
    }
}
