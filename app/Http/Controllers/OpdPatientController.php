<?php

namespace App\Http\Controllers;

use App\Events\PaymentCollectionEvent;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use App\Models\Diagnosis;
use App\Models\OpdPatient;
use App\Models\PatientVital;
use Illuminate\Http\Request;
use App\Models\OpdDiagnosisTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class OpdPatientController extends Controller
{


    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $search = $request->input('search');
        $doctor_id = $request->input('doctor_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = OpdPatient::with('doctor', 'patient');
        // dd($query);

        // Search by patient name OR OPD No
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })
                    ->orWhere('opd_no', 'like', "%$search%")
                    ->orWhere('patient_unique_id', 'like', "%$search%"); // <-- Add this line
            });
        }

        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        if ($from_date) {
            $query->whereDate('appointment_date', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('appointment_date', '<=', $to_date);
        }

        // Default sorting by latest
        $query->orderBy('id', 'desc');

        $opd_patients = $query->paginate($perPage);
        // dd($opd_patients);

        $doctors = User::role('Doctor')->select('id', 'name')->get();

        return view('opd.opdpatient', compact('opd_patients', 'doctors', 'search', 'doctor_id', 'from_date', 'to_date'));
    }
    public function add_opd_patient()
    {
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        //   dd($doctors);
        // Sare patients lao
         $patients = User::role('Patient')->select('id', 'name', 'email')->orderBy('id', 'desc')->get();


        // dd($patients);
        return view('opd.new-opdpatient', compact('patients', 'doctors'));
    }
    public function edit_ipd_patient($id)
    {
        $patient = OpdPatient::where('id', $id)->first();
        if ($patient) {
            $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
            $patients = User::role('Patient')->select('id', 'name', 'email')->get();
            return view('opd.edit-opdpatient', compact('patient', 'id', 'patients', 'doctors'));
        } else {
            return redirect()->back();
        }
    }



    public function opd_patient_save(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'patient' => 'required',
                'case' => 'nullable|string|max:255',
                'opd_no' => 'required|string|max:255|unique:opd_patients,opd_no',
                'height' => 'nullable|string|max:50',
                'weight' => 'nullable|string|max:50',
                'casuality' => 'nullable|string|max:255',
                'reference' => 'nullable|string|max:255',
                'blood_pressure' => 'nullable|string|max:50',
                'symptoms' => 'nullable|string|max:1000',
                'notes' => 'nullable|string|max:1000',
                'appointment_date' => 'required|date',
                'doctor' => 'required|exists:users,id',
                'standard_charge' => 'required|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'final_amount' => 'required|numeric|min:0',
                'payment_mode' => 'required|string|max:100',
                'is_old_patient' => 'required|boolean',
                'bp' => 'nullable|string|max:100',
                'SpO2' => 'nullable|string|max:100',
                'pulse' => 'nullable|string|max:50',
                'temperature' => 'nullable|string|max:50',
                'respiratory_rate' => 'nullable|string|max:50',
                'rbs' => 'nullable|string|max:50',
                'general_appearance' => 'nullable|string|max:255',
                'cvs' => 'nullable|string|max:255',
                'rs' => 'nullable|string|max:255',
                'pa' => 'nullable|string|max:255',
                'cns' => 'nullable|string|max:255',
                'other_systems' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $invoice_no = 'INV' . date('YmdHis') . mt_rand(100, 999);

            // Calculate tax/discount amount if needed (already calculated in JS, but double-check)
            $charge = floatval($request->standard_charge);
            $taxPercent = floatval($request->tax);
            $discountPercent = floatval($request->discount);
            $taxAmount = ($charge * $taxPercent) / 100;
            $discountAmount = ($charge * $discountPercent) / 100;
            $finalAmount = $charge + $taxAmount - $discountAmount;


            $patient_unique_id = DB::table('patients')
                ->where('user_id', $request->patient)
                ->value('patient_unique_id');

            // Create a new OPD Patient record
            $opdPatient = OpdPatient::create([
                'patient_id' => $request->patient,
                'patient_unique_id' => $patient_unique_id, // Store the patient's unique ID
                'case' => $request->case,
                'opd_no' => $request->opd_no,
                'height' => $request->height,
                'weight' => $request->weight,
                'casuality' => $request->casuality,
                'reference' => $request->reference,
                'blood_pressure' => $request->blood_pressure,
                'symptoms' => $request->symptoms,
                'notes' => $request->notes,
                'appointment_date' => $request->appointment_date,
                'doctor_id' => $request->doctor,
                'standard_charge' => $charge,
                'tax' => $taxPercent,
                'discount' => $discountPercent,
                'final_amount' => $finalAmount,
                'payment_mode' => $request->payment_mode,
                'is_old_patient' => $request->is_old_patient,
                'bp' => $request->bp,
                'SpO2' => $request->SpO2,
                'pulse' => $request->pulse, // Store pulse
                'temperature' => $request->temperature, // Store temperature
                'respiratory_rate' => $request->respiratory_rate,
                'rbs' => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs' => $request->cvs,
                'rs' => $request->rs,
                'pa' => $request->pa,
                'cns' => $request->cns,
                'other_systems' => $request->other_systems,
                'invoice_no' => $invoice_no, // Store the generated invoice number
            ]);



            $data = new \stdClass();
            $data->amount = $finalAmount;
            $data->payment_mode_id = $request->payment_mode;
            $data->patient_id = $request->patient;

            // dd($data);
            DB::commit();


            event(new PaymentCollectionEvent($data, 'Opd New Payment', 1));

            // Decide redirect route based on action
            if ($request->input('action') === 'save_print') {
                $redirect_to = route('admin.opd.print', $opdPatient->id);
            } else {
                $redirect_to = route('admin.opd.index');
            }

            return redirect($redirect_to)->with('success', 'OPD Patient Record Saved Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while saving the OPD Patient record! ' . $e->getMessage())
                ->withInput();
        }
    }

    public function opd_patient_edit_save(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'patient' => 'required',
                'case' => 'nullable|string|max:255',
                'opd_no' => "required|string|max:255|unique:opd_patients,opd_no,$id,id", // Ensure opd_no is unique, excluding current record
                'height' => 'nullable|string|max:50',
                'weight' => 'nullable|string|max:50',
                'blood_pressure' => 'nullable|string|max:50',
                'symptoms' => 'nullable|string|max:1000',
                'notes' => 'nullable|string|max:1000',
                'appointment_date' => 'required|date',
                'doctor' => "required|exists:users,id", // Ensure doctor exists in the users table
                'standard_charge' => 'required|numeric|min:0',
                'payment_mode' => 'required|string|max:100',
                'is_old_patient' => 'required|boolean',
                'bp' => 'nullable|string|max:100',
                'SpO2' => 'nullable|string|max:100',
                'pulse' => 'nullable|string|max:50',
                'temperature' => 'nullable|string|max:50',
                'respiratory_rate' => 'nullable|string|max:50',
                'rbs' => 'nullable|string|max:50',
                'general_appearance' => 'nullable|string|max:255',
                'cvs' => 'nullable|string|max:255',
                'rs' => 'nullable|string|max:255',
                'pa' => 'nullable|string|max:255',
                'cns' => 'nullable|string|max:255',
                'other_systems' => 'nullable|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Start a database transaction
            DB::beginTransaction();

            // Find the existing OPD Patient record by ID
            $opdPatient = OpdPatient::findOrFail($id); // Throws a 404 error if the record doesn't exist

            // Fetch the patient's unique ID
            $patient_unique_id = DB::table('patients')
                ->where('user_id', $request->patient)
                ->value('patient_unique_id');
            // Update the OPD Patient record
            $opdPatient->update([
                'patient_unique_id' => $patient_unique_id,
                'patient_id' => $request->patient, // Foreign key for patient
                'case' => $request->case,
                'opd_no' => $request->opd_no, // Unique OPD number
                'height' => $request->height,
                'weight' => $request->weight,
                'blood_pressure' => $request->blood_pressure,
                'symptoms' => $request->symptoms,
                'notes' => $request->notes,
                'appointment_date' => $request->appointment_date,
                'doctor_id' => $request->doctor, // Foreign key for doctor
                'standard_charge' => $request->standard_charge,
                'payment_mode' => $request->payment_mode,
                'is_old_patient' => $request->is_old_patient,
                'bp' => $request->bp,
                'SpO2' => $request->SpO2,
                'pulse' => $request->pulse,
                'temperature' => $request->temperature,
                'respiratory_rate' => $request->respiratory_rate,
                'rbs' => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs' => $request->cvs,
                'rs' => $request->rs,
                'pa' => $request->pa,
                'cns' => $request->cns,
                'other_systems' => $request->other_systems,
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.opd.index')->with('success', 'OPD Patient updated successfully!');
        } catch (\Throwable $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Redirect back with error message
            return redirect()->back()
                ->with('error', 'An error occurred while updating the OPD Patient record: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateVitals(Request $request, $id)
    {
        try {
            $opdPatient = OpdPatient::findOrFail($id);

            $request->validate([
                'recorded_at'        => 'nullable',
                'recorded_by'        => 'nullable|string|max:255',
                'shift'              => 'nullable|string|max:50',
                'height'             => 'nullable|string|max:50',
                'weight'             => 'nullable|string|max:50',
                'blood_pressure'     => 'nullable|string|max:50',
                'pulse'              => 'nullable|string|max:50',
                'temperature'        => 'nullable|string|max:50',
                'SpO2'               => 'nullable|string|max:50',
                'respiratory_rate'   => 'nullable|string|max:50',
                'rbs'                => 'nullable|string|max:50',
                'general_appearance' => 'nullable|string|max:255',
                'cvs'                => 'nullable|string|max:255',
                'rs'                 => 'nullable|string|max:255',
                'pa'                 => 'nullable|string|max:255',
                'cns'                => 'nullable|string|max:255',
                'other_systems'      => 'nullable|string|max:255',
                'notes'              => 'nullable|string',
            ]);

            $recordedAt = $request->recorded_at ?: now();
            $staffName  = $request->recorded_by ?: (auth()->user() ? auth()->user()->name : 'Staff');

            // ---- Save to patient_vitals (per-visit snapshot) ----
            PatientVital::create([
                'patient_id'         => $opdPatient->patient_id,
                'opd_patient_id'     => $opdPatient->id,
                'recorded_at'        => $recordedAt,
                'recorded_by'        => $staffName,
                'shift'              => $request->shift,
                'blood_pressure'     => $request->blood_pressure,
                'pulse'              => $request->pulse,
                'temperature'        => $request->temperature,
                'spo2'               => $request->SpO2,
                'respiratory_rate'   => $request->respiratory_rate,
                'rbs'                => $request->rbs,
                'height'             => $request->height,
                'weight'             => $request->weight,
                'general_appearance' => $request->general_appearance,
                'cvs'                => $request->cvs,
                'rs'                 => $request->rs,
                'pa'                 => $request->pa,
                'cns'                => $request->cns,
                'other_systems'      => $request->other_systems,
                'notes'              => $request->notes,
            ]);

            // ---- Also update opd_patients row (backward compat / quick access) ----
            $opdPatient->update([
                'height'             => $request->height,
                'weight'             => $request->weight,
                'blood_pressure'     => $request->blood_pressure,
                'pulse'              => $request->pulse,
                'temperature'        => $request->temperature,
                'SpO2'               => $request->SpO2,
                'respiratory_rate'   => $request->respiratory_rate,
                'rbs'                => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs'                => $request->cvs,
                'rs'                 => $request->rs,
                'pa'                 => $request->pa,
                'cns'                => $request->cns,
                'other_systems'      => $request->other_systems,
            ]);

            return redirect()->back()
                ->with('success', 'Vitals recorded successfully!')
                ->with('active_tab', 'vitals');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to record vitals: ' . $e->getMessage());
        }
    }

    public function destroyVital($id)
    {
        $vital = PatientVital::findOrFail($id);
        $vital->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Vitals record deleted.']);
        }

        return redirect()->back()
            ->with('success', 'Vitals record deleted successfully!')
            ->with('active_tab', 'vitals');
    }

    public function getPatientCases($patientId)
    {
        $cases = DB::table('patient_cases')
            ->where('patient_id', $patientId)
            ->select('id', 'case_id')
            ->get();
        return response()->json($cases);
    }



    public function generateOpdNo()
    {
        $year = date('y'); // e.g. 25 for 2025

        // Get last OPD number for this year
        $lastOpd = OpdPatient::whereYear('created_at', now()->year)
            ->orderByDesc('id')
            ->first();

        if ($lastOpd && preg_match('/\/OPD\/(\d{5})\/' . $year . '/', $lastOpd->opd_no, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        // Ensure unique OPD number
        do {
            $opd_no = 'CB/OPD/' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . '/' . $year;
            $exists = OpdPatient::where('opd_no', $opd_no)->exists();
            $nextNumber++;
        } while ($exists);

        return response()->json(['opd_no' => $opd_no]);
    }



    public function opd_patient_detail($id)
    {
        $opd_patient = OpdPatient::with(['patient', 'doctor'])->findOrFail($id);

        // Fetch appointments for this patient (latest first)
        $appointments = OpdPatient::with('doctor')->where('patient_id', $opd_patient->patient_id)->latest()->get();
        // dd($appointments);

        $appointments_count = OpdPatient::where('patient_id', $opd_patient->patient_id)->count();

        // Fetch diagnoses for this OPD patient
        $diagnoses = Diagnosis::where('opd_patient_id', $opd_patient->id)
            ->orderBy('report_date', 'desc')
            ->get();

        // Fetch medicine categories
        $medicine_categories = \App\Models\MedicineCategory::all();
        $medicines = \App\Models\Medicine::select('id', 'medicine', 'category')->orderBy('medicine')->get();

        // Fetch prescriptions for this patient (with medicines and doctor)
        $prescriptions = \App\Models\Prescription::with(['medicines.medicine'])
            ->where('patient_id', $opd_patient->patient_id)
            ->orderByDesc('id')
            ->get();

        // **Fetch diagnosis tests for this OPD patient**
        $diagnosis_tests = OpdDiagnosisTest::where('opd_patient_id', $opd_patient->id)->where('opd_no', $opd_patient->opd_no)->orderBy('test_date', 'desc')->get();
        // dd($diagnosis_tests);
        $diagnosis_tests_groups = OpdDiagnosisTest::where('opd_patient_id', $opd_patient->id)
            ->where('opd_no', $opd_patient->opd_no)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('group_test_no');
        // dd($diagnosis_tests_groups);
        $chargeCategories = \App\Models\ChargeCategory::all();
        // dd($chargeCategories); 
        $chargeTypes = \App\Models\ChargeType::all();

        $pathology_bills = \App\Models\PathologyBill::where('patient_id', $opd_patient->patient_id)->orderBy('id', 'desc')->get();
        $pharmacy_bills = \App\Models\MedicineBill::where('patient_id', $opd_patient->patient_id)->orderBy('id', 'desc')->get();

        $progress_notes = \App\Models\OpdProgressNote::where('opd_patient_id', $opd_patient->id)
            ->with(['medicines.category', 'addendums'])
            ->orderBy('date_time', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Load per-visit vitals history for this OPD visit
        $vitals_history = \App\Models\PatientVital::where('opd_patient_id', $opd_patient->id)
            ->orderByDesc('recorded_at')
            ->get();

        return view('opd.opdpatient-details', compact(
            'opd_patient',
            'appointments',
            'appointments_count',
            'diagnoses',
            'medicine_categories',
            'medicines',
            'prescriptions',
            'chargeCategories',
            'chargeTypes',
            'diagnosis_tests',
            'diagnosis_tests_groups',
            'pathology_bills',
            'pharmacy_bills',
            'progress_notes',
            'vitals_history'
        ));
    }

    public function opdcharge($id)
    {
        $opdPatient = \App\Models\AddCharge::with(['chargeCategory', 'chargeType'])->where('ipd_no', $id)->get();
        return response()->json(['data' => $opdPatient]);
    }

    public function print($id)
    {
        $opdPatient = OpdPatient::with(['doctor', 'patient'])->findOrFail($id);
        // dd($opdPatient);
        $patient_uhid = DB::table('patients')
            ->where('user_id', $opdPatient->patient_id)
            ->value('patient_unique_id');

        // dd($patient_uhid);

        return view('opd.print-opdpatient', compact('opdPatient', 'patient_uhid'));
    }

    public function groupPdf(Request $request)
    {
        $opd_patient_id = $request->opd_patient_id;
        $group_test_no = $request->group_test_no;
        // dd($opd_patient_id, $group_test_no);

        $tests = OpdDiagnosisTest::where('opd_patient_id', $opd_patient_id)
            ->where('group_test_no', $group_test_no)
            ->get();

        $opd_patient = OpdPatient::with(['patient', 'doctor'])->find($opd_patient_id);
        // dd($tests, $opd_patient);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('opd.diagnosis-tests-pdf', [
            'tests' => $tests,
            'opdPatient' => $opd_patient,
        ]);
        return $pdf->stream('diagnosis-tests.pdf'); // open in new tab
    }


    public function searchPatients(Request $request)
    {
        $term = $request->input('term');

        // Join users & patients table for UHID
        $results = \DB::table('users')
            ->leftJoin('patients', 'users.id', '=', 'patients.user_id')
            ->where('users.role', 'Patient') // ya aapke role system ke hisab se
            ->where(function ($query) use ($term) {
                $query->where('users.name', 'like', "%{$term}%")
                    ->orWhere('patients.patient_unique_id', 'like', "%{$term}%");
            })
            ->select('users.id', 'users.name', 'patients.patient_unique_id')
            ->orderBy('id', 'desc')
            ->get();

        // Format for Select2
        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'id' => $row->id,
                'name' => $row->name . ' (' . ($row->patient_unique_id ?? 'N/A') . ')'
            ];
        }

        return response()->json($data);
    }


    public function moveToIpd($id)
    {
        $opd = OpdPatient::with(['patient', 'doctor'])->findOrFail($id);
        // dd($opd);

        // Data array for prefill
        $prefill = [
            'uhid' => $opd->patient_unique_id,
            'patient_id' => $opd->patient_id,
            'doctor_id' => $opd->doctor_id,
            'symptoms' => $opd->symptoms,
            'height' => $opd->height,
            'weight' => $opd->weight,
            'blood_pressure' => $opd->blood_pressure,
            // aur bhi fields jo aapko chahiye
        ];

        // Redirect to IPD add page with prefill data as query string
        return redirect()->route('admin.ipd.add', $prefill);
    }

    public function blankReceipt($id)
    {
        $opdPatient = OpdPatient::with(['doctor', 'patient'])->findOrFail($id);
        // dd($opdPatient);
        $patient_uhid = DB::table('patients')
            ->where('user_id', $opdPatient->patient_id)
            ->value('patient_unique_id');
        return view('opd.blank-receipt', compact('opdPatient', 'patient_uhid'));
    }

    public function vitalsHistory($id)
    {
        $vitals = \App\Models\PatientVital::where('opd_patient_id', $id)
            ->orderByDesc('recorded_at')
            ->get();
        return response()->json($vitals);
    }
}

