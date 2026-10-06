<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\OpdPatient;
use App\Models\Prescription;
use App\Models\PatientVital;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MedicineCategory;
use Illuminate\Support\Facades\DB;
use App\Models\PrescriptionMedicine;

class PrescriptionOpdController extends Controller
{
    // public function index($opd_patient)
    // {
    //      $opd_patient = \App\Models\OpdPatient::with(['patient', 'doctor'])->findOrFail($opd_patient);
    //     $prescriptions = Prescription::with('medicines.medicine')->where('patient_id', $opd_patient)->orderByDesc('id')->get();
    //     $medicine_categories = MedicineCategory::all();

    //     dd($prescriptions->toArray());
    //     return view('opd.opdpatient-details', compact('prescriptions', 'medicine_categories', 'opd_patient'));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'headernotes' => 'nullable|string',
            'footernotes' => 'nullable|string',
            'prescription_date' => 'required|date',
            'status' => 'required|string',
            'medicine_id' => 'nullable|array',
            'medicine_name' => 'nullable|array',
            'dosage' => 'nullable|array',
            'route' => 'nullable|array',
            'duration' => 'nullable|array',
            'dose_duration' => 'nullable|array',
            'doseInterval' => 'nullable|array',
            'dose_interval' => 'nullable|array',
            'frequency' => 'nullable|array',
            'time' => 'nullable|array',
            'instructions' => 'nullable|array',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'headernotes' => $request->headernotes,
            'footernotes' => $request->footernotes,
            'prescription_date' => $request->prescription_date,
            'status' => $request->status,
        ]);

        $medicinesList = $request->medicine_name ?? $request->medicine_id ?? [];
        if (!empty($medicinesList)) {
            foreach ($medicinesList as $i => $val) {
                if (empty($val) || trim($val) === '') continue;

                $medId = null;
                $medName = trim($val);

                if (is_numeric($val) && (int)$val > 0) {
                    $found = Medicine::find($val);
                    if ($found) {
                        $medId = $found->id;
                        $medName = $found->medicine;
                    }
                } else {
                    $found = Medicine::where('medicine', $medName)->first();
                    if ($found) {
                        $medId = $found->id;
                        $medName = $found->medicine;
                    }
                }

                $routeVal = $request->route[$i] ?? ($request->time[$i] ?? null);
                $freqVal = $request->dose_interval[$i] ?? ($request->frequency[$i] ?? ($request->doseInterval[$i] ?? null));
                $durationVal = $request->duration[$i] ?? ($request->dose_duration[$i] ?? null);

                PrescriptionMedicine::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id'     => $medId,
                    'medicine_name'   => $medName,
                    'dosage'          => $request->dosage[$i] ?? null,
                    'route'           => $routeVal,
                    'doseInterval'    => $freqVal,
                    'duration'        => $durationVal,
                    'time'            => $routeVal,
                    'instructions'    => $request->instructions[$i] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Prescription added successfully!');
    }

    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'headernotes' => 'nullable|string',
            'footernotes' => 'nullable|string',
            'prescription_date' => 'required|date',
            'status' => 'required|string',
            'medicine_id' => 'nullable|array',
            'medicine_name' => 'nullable|array',
            'dosage' => 'nullable|array',
            'route' => 'nullable|array',
            'duration' => 'nullable|array',
            'dose_duration' => 'nullable|array',
            'doseInterval' => 'nullable|array',
            'dose_interval' => 'nullable|array',
            'frequency' => 'nullable|array',
            'time' => 'nullable|array',
            'instructions' => 'nullable|array',
        ]);

        $prescription->update([
            'headernotes' => $request->headernotes,
            'footernotes' => $request->footernotes,
            'prescription_date' => $request->prescription_date,
            'status' => $request->status,
        ]);

        // Delete old medicines
        $prescription->medicines()->delete();

        // Add new medicines
        $medicinesList = $request->medicine_name ?? $request->medicine_id ?? [];
        if (!empty($medicinesList)) {
            foreach ($medicinesList as $i => $val) {
                if (empty($val) || trim($val) === '') continue;

                $medId = null;
                $medName = trim($val);

                if (is_numeric($val) && (int)$val > 0) {
                    $found = Medicine::find($val);
                    if ($found) {
                        $medId = $found->id;
                        $medName = $found->medicine;
                    }
                } else {
                    $found = Medicine::where('medicine', $medName)->first();
                    if ($found) {
                        $medId = $found->id;
                        $medName = $found->medicine;
                    }
                }

                $routeVal = $request->route[$i] ?? ($request->time[$i] ?? null);
                $freqVal = $request->dose_interval[$i] ?? ($request->frequency[$i] ?? ($request->doseInterval[$i] ?? null));
                $durationVal = $request->duration[$i] ?? ($request->dose_duration[$i] ?? null);

                PrescriptionMedicine::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id'     => $medId,
                    'medicine_name'   => $medName,
                    'dosage'          => $request->dosage[$i] ?? null,
                    'route'           => $routeVal,
                    'doseInterval'    => $freqVal,
                    'duration'        => $durationVal,
                    'time'            => $routeVal,
                    'instructions'    => $request->instructions[$i] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Prescription updated successfully!');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->medicines()->delete();
        $prescription->delete();
        return redirect()->back()->with('success', 'Prescription deleted successfully!');
    }

    public function getMedicinesByCategory($category_id)
    {
        if (empty($category_id) || $category_id === 'all') {
            $medicines = Medicine::all();
        } else {
            $medicines = Medicine::where('category', $category_id)->get();
        }
        return response()->json($medicines);
    }

    public function show(Prescription $prescription)
    {
        $prescription->load('medicines.medicine');
        return response()->json([
            'id' => $prescription->id,
            'prescription_date' => $prescription->prescription_date,
            'created_at' => $prescription->created_at,
            'headernotes' => $prescription->headernotes,
            'footernotes' => $prescription->footernotes,
            'status' => $prescription->status,
            'medicines' => $prescription->medicines->map(function ($item) {
                return [
                    'category_id' => $item->medicine->category ?? null,
                    'medicine_id' => $item->medicine_id,
                    'medicine_name' => $item->medicine_name ?: ($item->medicine->medicine ?? ''),
                    'dosage' => $item->dosage,
                    'route' => $item->route ?: ($item->time ?? ''),
                    'frequency' => $item->doseInterval,
                    'duration' => $item->duration,
                    'doseInterval' => $item->doseInterval,
                    'time' => $item->time,
                    'instructions' => $item->instructions,
                ];
            })->toArray(),
        ]);
    }

    public function pdf(Prescription $prescription)
    {
        $prescription->load('medicines.medicine');

        // Get the OPD visit for this prescription (closest appointment_date on or before prescription_date)
        $opd_patient = OpdPatient::with(['patient', 'doctor'])
            ->where('patient_id', $prescription->patient_id)
            ->where('appointment_date', '<=', $prescription->prescription_date . ' 23:59:59')
            ->orderByDesc('appointment_date')
            ->first();

        // Fallback: just get the latest OPD visit if no exact date match
        if (!$opd_patient) {
            $opd_patient = OpdPatient::with(['patient', 'doctor'])
                ->where('patient_id', $prescription->patient_id)
                ->orderByDesc('id')
                ->first();
        }

        // ---- Load closest vitals from patient_vitals table ----
        // Find vitals recorded on or before prescription_date for this OPD visit
        $visit_vitals = null;
        if ($opd_patient) {
            $visit_vitals = PatientVital::where('opd_patient_id', $opd_patient->id)
                ->where('recorded_at', '<=', $prescription->prescription_date . ' 23:59:59')
                ->orderByDesc('recorded_at')
                ->first();
        }
        // If no patient_vitals record exists, $visit_vitals = null
        // Blade view falls back to $opd_patient->blood_pressure etc.

        $uhid = DB::table("patients")->where('user_id', $prescription->patient_id)->value('patient_unique_id');
        $medicine_categories = MedicineCategory::all();
        $medicines = Medicine::all();

        return view('opd.prescription-pdf', compact(
            'prescription',
            'opd_patient',
            'visit_vitals',
            'medicine_categories',
            'medicines',
            'uhid'
        ));
    }
}