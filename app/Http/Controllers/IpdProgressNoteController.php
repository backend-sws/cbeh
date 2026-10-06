<?php

namespace App\Http\Controllers;

use App\Models\IpdPatient;
use App\Models\IpdProgressNote;
use App\Models\ProgressNoteMedicine;
use App\Models\ProgressNoteAddendum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpdProgressNoteController extends Controller
{
    /**
     * Get all progress notes for an IPD patient.
     */
    public function index($ipdPatientId)
    {
        $notes = IpdProgressNote::where('ipd_patient_id', $ipdPatientId)
            ->with(['medicines.category', 'addendums'])
            ->orderBy('date_time', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($notes);
    }

    /**
     * Store a newly created progress note.
     */
    public function store(Request $request, $ipdPatientId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date_time' => 'required',
            'note' => 'required|string',
            'staff_name' => 'nullable|string|max:255',
        ]);

        $ipdPatient = IpdPatient::findOrFail($ipdPatientId);

        DB::beginTransaction();
        try {
            $staffName = auth()->user() ? auth()->user()->name : ($request->staff_name ?: 'Staff');

            $progressNote = IpdProgressNote::create([
                'ipd_patient_id' => $ipdPatient->id,
                'patient_id'     => $ipdPatient->patient_id,
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
                        ProgressNoteMedicine::create([
                            'ipd_progress_note_id' => $progressNote->id,
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
                    'message' => 'Progress note saved successfully!',
                    'data'    => $progressNote->load(['medicines.category', 'addendums']),
                ]);
            }

            return redirect()->back()->with('success', 'Progress note saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error saving progress note: ' . $e->getMessage());
        }
    }

    /**
     * Add an addendum to an existing progress note.
     */
    public function addAddendum(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
            'date_time' => 'nullable',
            'staff_name' => 'nullable|string|max:255',
        ]);

        $progressNote = IpdProgressNote::findOrFail($id);

        try {
            $staffName = auth()->user() ? auth()->user()->name : ($request->staff_name ?: 'Staff');
            $dateTime = $request->date_time ?: now();

            $addendum = ProgressNoteAddendum::create([
                'ipd_progress_note_id' => $progressNote->id,
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

            return redirect()->back()->with('success', 'Addendum added successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error adding addendum: ' . $e->getMessage());
        }
    }

    /**
     * Print a single progress note with all medicines and addendums.
     */
    public function print($id)
    {
        $note = IpdProgressNote::with([
            'ipdPatient.patient',
            'ipdPatient.doctor',
            'ipdPatient.beds',
            'medicines.category',
            'addendums'
        ])->findOrFail($id);

        return view('ipd.print-progress-note', compact('note'));
    }

    /**
     * Delete a progress note.
     */
    public function destroy($id)
    {
        $note = IpdProgressNote::findOrFail($id);
        $note->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Progress note deleted successfully!']);
        }

        return redirect()->back()->with('success', 'Progress note deleted successfully!');
    }
}
