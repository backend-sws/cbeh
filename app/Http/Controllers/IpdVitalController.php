<?php

namespace App\Http\Controllers;

use App\Models\IpdPatient;
use App\Models\IpdVital;
use Illuminate\Http\Request;

class IpdVitalController extends Controller
{
    /**
     * Return JSON list of all vitals for an IPD admission.
     */
    public function index($ipdPatientId)
    {
        $vitals = IpdVital::where('ipd_patient_id', $ipdPatientId)
            ->orderByDesc('recorded_at')
            ->get();

        return response()->json($vitals);
    }

    /**
     * Store a new vitals record for an IPD admission.
     */
    public function store(Request $request, $ipdPatientId)
    {
        $request->validate([
            'recorded_at'      => 'required',
            'recorded_by'      => 'nullable|string|max:255',
            'shift'            => 'nullable|string|max:50',
            'blood_pressure'   => 'nullable|string|max:50',
            'pulse'            => 'nullable|string|max:50',
            'temperature'      => 'nullable|string|max:50',
            'spo2'             => 'nullable|string|max:50',
            'respiratory_rate' => 'nullable|string|max:50',
            'rbs'              => 'nullable|string|max:50',
            'height'           => 'nullable|string|max:50',
            'weight'           => 'nullable|string|max:50',
            'general_appearance' => 'nullable|string|max:255',
            'cvs'              => 'nullable|string|max:255',
            'rs'               => 'nullable|string|max:255',
            'pa'               => 'nullable|string|max:255',
            'cns'              => 'nullable|string|max:255',
            'other_systems'    => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
        ]);

        $ipdPatient = IpdPatient::findOrFail($ipdPatientId);

        $staffName = $request->recorded_by ?: (auth()->user() ? auth()->user()->name : 'Staff');

        $vital = IpdVital::create([
            'patient_id'       => $ipdPatient->patient_id,
            'ipd_patient_id'   => $ipdPatient->id,
            'recorded_at'      => $request->recorded_at,
            'recorded_by'      => $staffName,
            'shift'            => $request->shift,
            'blood_pressure'   => $request->blood_pressure,
            'pulse'            => $request->pulse,
            'temperature'      => $request->temperature,
            'spo2'             => $request->spo2,
            'respiratory_rate' => $request->respiratory_rate,
            'rbs'              => $request->rbs,
            'height'           => $request->height,
            'weight'           => $request->weight,
            'general_appearance' => $request->general_appearance,
            'cvs'              => $request->cvs,
            'rs'               => $request->rs,
            'pa'               => $request->pa,
            'cns'              => $request->cns,
            'other_systems'    => $request->other_systems,
            'notes'            => $request->notes,
        ]);

        // ALSO update the ipd_patient row for backward compatibility (latest vitals)
        $ipdPatient->update([
            'blood_pressure'   => $request->blood_pressure ?? $ipdPatient->blood_pressure,
            'pulse'            => $request->pulse ?? $ipdPatient->pulse,
            'temperature'      => $request->temperature ?? $ipdPatient->temperature,
            'SpO2'             => $request->spo2 ?? $ipdPatient->SpO2,
            'respiratory_rate' => $request->respiratory_rate ?? $ipdPatient->respiratory_rate,
            'rbs'              => $request->rbs ?? $ipdPatient->rbs,
            'height'           => $request->height ?? $ipdPatient->height,
            'weight'           => $request->weight ?? $ipdPatient->weight,
            'general_appearance' => $request->general_appearance ?? $ipdPatient->general_appearance,
            'cvs'              => $request->cvs ?? $ipdPatient->cvs,
            'rs'               => $request->rs ?? $ipdPatient->rs,
            'pa'               => $request->pa ?? $ipdPatient->pa,
            'cns'              => $request->cns ?? $ipdPatient->cns,
            'other_systems'    => $request->other_systems ?? $ipdPatient->other_systems,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vitals recorded successfully!',
                'data'    => $vital,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Vitals recorded successfully!')
            ->with('active_tab', 'vitals');
    }

    /**
     * Delete a vitals record.
     */
    public function destroy($id)
    {
        $vital = IpdVital::findOrFail($id);
        $vital->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Vitals record deleted.']);
        }

        return redirect()->back()
            ->with('success', 'Vitals record deleted.')
            ->with('active_tab', 'vitals');
    }

    /**
     * Print Vitals Chart for an IPD admission.
     */
    public function chart($ipdPatientId)
    {
        $ipdPatient = IpdPatient::with(['patient', 'doctor'])->findOrFail($ipdPatientId);

        $vitals = IpdVital::where('ipd_patient_id', $ipdPatientId)
            ->orderBy('recorded_at', 'asc')
            ->get();

        $uhid = \Illuminate\Support\Facades\DB::table('patients')
            ->where('user_id', $ipdPatient->patient_id)
            ->value('patient_unique_id');

        return view('ipd.vitals-chart', compact('ipdPatient', 'vitals', 'uhid'));
    }
}
