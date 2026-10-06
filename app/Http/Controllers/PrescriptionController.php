<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medicine;
use App\Models\IpdPatient;
use Illuminate\Support\Str;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\MedicineBrand;
use App\Models\MedicineCategory;
use App\Models\OpdPatient;
use App\Models\IpdVital;
use App\Models\PatientVital;
use Illuminate\Support\Facades\DB;
use App\Models\PrescriptionMedicine;
use Illuminate\Support\Facades\Validator;



class PrescriptionController extends Controller
{
    public function index()
    {
        $perPage = request()->input('perPage', 10);
        $prescriptions = Prescription::with('doctor', 'patient')->paginate($perPage);
        return view('prescription.prescription', compact('prescriptions'));
    }
    public function add()
    {
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        $patients = User::role('Patient')->select('id', 'name', 'email')->get();
        return view('prescription.newprescription', compact('doctors', 'patients'));
    }
    public function getMedicines(Request $request)
    {
        $query = $request->input('query');
        $medicines = Medicine::with(['category', 'brand', 'type'])
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('generic_name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($medicines);
    }
    public function getMedicineCategories()
    {
        $categories = MedicineCategory::all();
        return response()->json($categories);
    }
    public function getMedicineBrands()
    {
        $brands = MedicineBrand::all();
        return response()->json($brands);
    }

    public function getPrescriptionMedicines($prescriptionId)
    {
        $medicines = PrescriptionMedicine::where('prescription_id', $prescriptionId)
            ->with(['medicine', 'medicine.category', 'medicine.brand', 'medicine.type'])
            ->get();

        return response()->json($medicines);
    }
    public function getPrescriptionDetails($id)
    {
        $prescription = Prescription::with(['doctor', 'patient', 'patient.ipd', 'medicines.medicine', 'medicines.medicine.categoryRelation', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->findOrFail($id);

        return response()->json($prescription);
    }
    public function prescription_by_patient($patientId)
    {
        $prescriptions = Prescription::where('patient_id', $patientId)
            ->with(['patient', 'doctor'])
            ->get();

        return response()->json($prescriptions);
    }
    public function prescriptionDetail($id)
    {
        $prescription = Prescription::with(['doctor', 'patient', 'medicines.medicine', 'medicines.medicine', 'medicines.medicine', 'medicines.medicine.category'])
            ->findOrFail($id);

        return response()->json($prescription);
    }
    public function getPrescriptionByDoctor($doctorId)
    {
        $prescriptions = Prescription::where('doctor_id', $doctorId)
            ->with(['patient', 'medicines.medicine', 'medicines.medicine.category', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->get();

        return response()->json($prescriptions);
    }
    public function getPrescriptionByIpdNo($ipdNo)
    {
        $prescriptions = Prescription::where('ipd_no', $ipdNo)
            ->with(['doctor', 'patient', 'medicines.medicine', 'medicines.medicine.category', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->get();

        return response()->json($prescriptions);
    }
    public function getPrescriptionByCase($case)
    {
        $prescriptions = Prescription::where('case', 'LIKE', "%{$case}%")
            ->with(['doctor', 'patient', 'medicines.medicine', 'medicines.medicine.category', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->get();

        return response()->json($prescriptions);
    }
    public function getPrescriptionByStatus($status)
    {
        $prescriptions = Prescription::where('status', $status)
            ->with(['doctor', 'patient', 'medicines.medicine', 'medicines.medicine.category', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->get();

        return response()->json($prescriptions);
    }
    public function getPrescriptionByDateRange($startDate, $endDate)
    {
        $prescriptions = Prescription::whereBetween('created_at', [$startDate, $endDate])
            ->with(['doctor', 'patient', 'medicines.medicine', 'medicines.medicine.category', 'medicines.medicine.brand', 'medicines.medicine.type'])
            ->get();

        return response()->json($prescriptions);
    }
    public function edit($id)
    {
        return view('prescription.editprescription', compact('id'));
    }
    public function view($id)
    {
        return view('prescription.viewprescription', compact('id'));
    }
    public function delete($id)
    {
        $prescription = Prescription::findOrFail($id);
        // Delete associated prescription medicines
        $prescription->delete();
        return redirect()->back()->with('success', 'Prescription deleted successfully.');
    }
    public function save(Request $request)
    {
        // Validate general prescription data
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'status' => 'boolean',
            // Optional validation for medicine arrays
            'medicine_id' => 'array',
            'medicine_name' => 'array',
            'dosage' => 'array',
            'dose_duration' => 'array',
            'dose_interval' => 'array',
            'time' => 'array',
            'instruction' => 'array',
        ]);

        if ($validator->fails()) {
            return show_validation_error($validator, true);
        }

        // Create the main prescription
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'headernotes' => $request->headernotes,
            'footernotes' => $request->footernotes,
            'status' => $request->status ? 1 : 0,
            'prescription_date' => now(),
        ]);

        // Check if this prescription is for an admitted IPD patient
        $ipdPatient = null;
        if (!empty($request->ipd_no)) {
            $ipdPatient = IpdPatient::where('id', $request->ipd_no)->orWhere('ipd_no', $request->ipd_no)->first();
        }
        if (!$ipdPatient) {
            $ipdPatient = IpdPatient::where('patient_id', $request->patient_id)->whereNull('discharge_date')->latest()->first();
        }

        // Handle multiple medicines
        $medicinesList = $request->medicine_name ?? $request->medicine_id ?? [];
        if (!empty($medicinesList)) {
            foreach ($medicinesList as $index => $val) {
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

                $prescMed = PrescriptionMedicine::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medId,
                    'medicine_name' => $medName,
                    'dosage' => $request->dosage[$index] ?? '',
                    'duration' => $request->dose_duration[$index] ?? ($request->duration[$index] ?? ''),
                    'doseInterval' => $request->dose_interval[$index] ?? ($request->frequency[$index] ?? ''),
                    'time' => $request->route[$index] ?? ($request->time[$index] ?? ''),
                    'instructions' => $request->instruction[$index] ?? ($request->instructions[$index] ?? ''),
                ]);

                // Also activate in Ongoing Medication Chart and generate scheduled doses if IPD patient
                if ($ipdPatient) {
                    try {
                        $patientMed = \App\Models\PatientMedication::create([
                            'ipd_patient_id' => $ipdPatient->id,
                            'patient_id'     => $prescription->patient_id,
                            'prescription_id'=> $prescription->id,
                            'doctor_id'      => $prescription->doctor_id,
                            'medicine_id'    => $medId,
                            'medicine_name'  => $medName,
                            'dose'           => $request->dosage[$index] ?? '',
                            'dose_type'      => $request->dose_type[$index] ?? 'Tablet',
                            'route'          => $request->route[$index] ?? ($request->time[$index] ?? 'Oral'),
                            'frequency'      => $request->dose_interval[$index] ?? ($request->frequency[$index] ?? 'OD'),
                            'duration'       => $request->dose_duration[$index] ?? ($request->duration[$index] ?? '5 Days'),
                            'start_date'     => now(),
                            'instructions'   => $request->instruction[$index] ?? ($request->instructions[$index] ?? ''),
                            'status'         => 'active',
                        ]);

                        \App\Services\MedicationSchedulerService::generateDoses($patientMed);
                    } catch (\Exception $e) {
                        \Log::warning("Could not auto-generate doses for medication {$medName}: " . $e->getMessage());
                    }
                }
            }
        }


        return redirect()->route('admin.prescription.print', $prescription->id)->with('success', 'Prescription created successfully.');
    }

    public function prescriptionSave(Request $request)
    {
        // Logic to save prescription data
        return redirect()->back()->with('success', 'Prescription saved successfully.');
    }
    public function prescriptionUpdate(Request $request, $id)
    {
        // Logic to update prescription data
        return redirect()->back()->with('success', 'Prescription updated successfully.');
    }

    public function print($id)
    {
        $prescription = Prescription::where('id', $id)
            ->with(['patient', 'doctor.doctorProfile', 'medicines.medicine.categoryRelation', 'medicines.medicine.category'])
            ->firstOrFail();

        $uhid = DB::table('patients')->where('user_id', $prescription->patient_id)->value('patient_unique_id');

        $ipd_patient = null;
        if (request()->filled('ipd_no')) {
            $ipd_patient = IpdPatient::with(['beds.bedType', 'doctor.doctorProfile', 'patient'])->where('ipd_no', request('ipd_no'))->first();
        }
        if (!$ipd_patient && request()->filled('ipd_id')) {
            $ipd_patient = IpdPatient::with(['beds.bedType', 'doctor.doctorProfile', 'patient'])->where('id', request('ipd_id'))->first();
        }
        if (!$ipd_patient) {
            $ipd_patient = IpdPatient::with(['beds.bedType', 'doctor.doctorProfile', 'patient'])->where('patient_id', $prescription->patient_id)->latest()->first();
        }

        $opd_patient = OpdPatient::where('patient_id', $prescription->patient_id)->latest()->first();
        $beds = $ipd_patient;

        // Find closest vitals for prescription date
        $targetDate = $prescription->prescription_date ? \Carbon\Carbon::parse($prescription->prescription_date)->format('Y-m-d') : now()->toDateString();
        $visit_vitals = null;
        if ($ipd_patient) {
            $visit_vitals = IpdVital::where('ipd_patient_id', $ipd_patient->id)
                ->where('recorded_at', '<=', $targetDate . ' 23:59:59')
                ->orderByDesc('recorded_at')
                ->first();
            if (!$visit_vitals) {
                $visit_vitals = IpdVital::where('ipd_patient_id', $ipd_patient->id)
                    ->orderByDesc('recorded_at')
                    ->first();
            }
        } elseif ($opd_patient) {
            $visit_vitals = PatientVital::where('opd_patient_id', $opd_patient->id)
                ->where('recorded_at', '<=', $targetDate . ' 23:59:59')
                ->orderByDesc('recorded_at')
                ->first();
            if (!$visit_vitals) {
                $visit_vitals = PatientVital::where('opd_patient_id', $opd_patient->id)
                    ->orderByDesc('recorded_at')
                    ->first();
            }
        }

        return view('prescription.print-prescription', compact('prescription', 'uhid', 'ipd_patient', 'opd_patient', 'beds', 'visit_vitals'));
    }


}
