<?php

namespace App\Http\Controllers;

use App\Models\IpdPatient;
use App\Models\PatientMedication;
use App\Models\MedicationAdministration;
use App\Models\Medicine;
use App\Services\MedicationSchedulerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OngoingMedicationController extends Controller
{
    /**
     * Store a new medication order directly from the Ongoing Medication Chart.
     */
    public function storeOrder(Request $request, $ipdPatientId)
    {
        $validator = Validator::make($request->all(), [
            'medicine_name' => 'required|string|max:255',
            'dose'          => 'nullable|string|max:100',
            'dose_type'     => 'nullable|string|max:100',
            'route'         => 'nullable|string|max:100',
            'frequency'     => 'required|string|max:50',
            'duration'      => 'nullable|string|max:100',
            'start_date'    => 'nullable|date',
            'instructions'  => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ipdPatient = IpdPatient::findOrFail($ipdPatientId);

        // Find medicine_id if exists in master medicines
        $medId = null;
        $medFound = Medicine::where('medicine', trim($request->medicine_name))->first();
        if ($medFound) {
            $medId = $medFound->id;
        }

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now();

        $medication = PatientMedication::create([
            'ipd_patient_id' => $ipdPatient->id,
            'patient_id'     => $ipdPatient->patient_id,
            'doctor_id'      => auth()->id(),
            'medicine_id'    => $medId,
            'medicine_name'  => trim($request->medicine_name),
            'dose'           => $request->dose,
            'dose_type'      => $request->dose_type,
            'route'          => $request->route,
            'frequency'      => $request->frequency,
            'duration'       => $request->duration,
            'start_date'     => $startDate,
            'instructions'   => $request->instructions,
            'status'         => 'active',
        ]);

        // Automatically generate scheduled doses
        $count = MedicationSchedulerService::generateDoses($medication);

        $message = "Medication '{$medication->medicine_name}' ordered successfully. {$count} scheduled doses generated.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'medication' => $medication]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Administer a scheduled dose (Given, Not Given, Held, Refused).
     */
    public function administerDose(Request $request, $doseId)
    {
        $status = $request->input('status');

        $rules = [
            'status' => 'required|in:given,not_given,held,refused',
        ];

        // Remarks are mandatory for Not Given, Held, or Refused
        if (in_array($status, ['not_given', 'held', 'refused'])) {
            $rules['remarks'] = 'required|string|min:3|max:1000';
        } else {
            $rules['remarks'] = 'nullable|string|max:1000';
            $rules['actual_administered_at'] = 'nullable|date';
            $rules['given_quantity'] = 'nullable|string|max:100';
            $rules['route_administered'] = 'nullable|string|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $dose = MedicationSchedulerService::administerDose($doseId, $request->all(), auth()->user());

            $statusLabels = [
                'given'     => 'Given ✅',
                'not_given' => 'Not Given ❌',
                'held'      => 'Skipped/Held ⏸️',
                'refused'   => 'Refused by Patient ⚠️',
            ];

            $statusText = $statusLabels[$status] ?? ucfirst($status);
            $message = "Dose for '{$dose->medication->medicine_name}' marked as {$statusText} by " . auth()->user()->name . ".";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message, 'dose' => $dose]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error updating dose: ' . $e->getMessage());
        }
    }

    /**
     * Record an on-demand SOS dose.
     */
    public function administerSos(Request $request, $medicationId)
    {
        $validator = Validator::make($request->all(), [
            'actual_administered_at' => 'nullable|date',
            'given_quantity'         => 'nullable|string|max:100',
            'route_administered'     => 'nullable|string|max:100',
            'remarks'                => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $medication = PatientMedication::findOrFail($medicationId);

        try {
            $dose = MedicationSchedulerService::recordSosDose($medication, $request->all(), auth()->user());

            $message = "SOS Dose for '{$medication->medicine_name}' administered and recorded by " . auth()->user()->name . ".";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message, 'dose' => $dose]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error recording SOS dose: ' . $e->getMessage());
        }
    }

    /**
     * Discontinue an active medication.
     */
    public function discontinue(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:3|max:1000',
        ]);

        $medication = PatientMedication::findOrFail($id);

        try {
            MedicationSchedulerService::discontinue($medication, $request->reason, auth()->user());

            $message = "Medication '{$medication->medicine_name}' discontinued. Future scheduled doses removed.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error discontinuing medication: ' . $e->getMessage());
        }
    }

    /**
     * Bedside printable Medication Administration Record (MAR).
     */
    public function printMar($ipdPatientId)
    {
        $ipd_patient = IpdPatient::with([
            'patient.patientProfile',
            'doctor.doctorProfile',
            'beds.bedType'
        ])->findOrFail($ipdPatientId);

        $uhid = DB::table('patients')->where('user_id', $ipd_patient->patient_id)->value('patient_unique_id')
            ?? ($ipd_patient->patient->patient_unique_id ?? $ipd_patient->patient->uhid ?? null);

        $active_medications = PatientMedication::where('ipd_patient_id', $ipd_patient->id)
            ->with(['doctor', 'administrations.administeredBy'])
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $administrations = MedicationAdministration::where('ipd_patient_id', $ipd_patient->id)
            ->with(['medication', 'administeredBy'])
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('ipd.medication-chart-print', compact('ipd_patient', 'uhid', 'active_medications', 'administrations'));
    }

    /**
     * Centralized Ward Nursing MAR Station dashboard.
     */
    public function wardStation(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $shift = $request->input('shift', 'All');
        $status = $request->input('status', 'All');

        $query = MedicationAdministration::with(['medication', 'ipdPatient.patient', 'ipdPatient.beds', 'administeredBy'])
            ->whereDate('scheduled_at', $date);

        if ($shift !== 'All') {
            $query->where('shift', $shift);
        }

        if ($status !== 'All') {
            $query->where('status', $status);
        }

        $doses = $query->orderBy('scheduled_at', 'asc')->paginate(25);

        // Summary counts for the selected date
        $summary = [
            'total'     => MedicationAdministration::whereDate('scheduled_at', $date)->count(),
            'due'       => MedicationAdministration::whereDate('scheduled_at', $date)->where('status', 'scheduled')->count(),
            'given'     => MedicationAdministration::whereDate('scheduled_at', $date)->where('status', 'given')->count(),
            'not_given' => MedicationAdministration::whereDate('scheduled_at', $date)->where('status', 'not_given')->count(),
            'held'      => MedicationAdministration::whereDate('scheduled_at', $date)->where('status', 'held')->count(),
            'refused'   => MedicationAdministration::whereDate('scheduled_at', $date)->where('status', 'refused')->count(),
        ];

        return view('ipd.medication-chart-station', compact('doses', 'date', 'shift', 'status', 'summary'));
    }
}
