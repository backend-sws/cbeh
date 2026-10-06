<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bed;
use App\Models\User;
use App\Models\Charge;
use App\Models\BedType;
use App\Models\Patient;
use App\Models\AddCharge;
use App\Models\BedAssign;
use App\Models\IpdPatient;
use App\Models\IpdPayment;
use Illuminate\Support\Str;
use App\Models\IpdDischarge;
use App\Models\IpdVital;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\DiagnosisTest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IpdPatientController extends Controller
{

    // ...existing code...
    public function index()
{
    $perPage = request()->input('perPage', 10);

    // Get filter inputs
    $search = request()->input('search');
    $doctor_id = request()->input('doctor_id');
    $from_date = request()->input('from_date');
    $to_date = request()->input('to_date');
    $due_status = request()->input('due_status');

    // --- UPDATED LOGIC FOR DISCHARGE STATUS ---
    // Agar URL me 'discharge_status' nahi hai (First Load), to default 'admitted' set karein.
    // Agar user ne 'All' select kiya hai (value=""), to request me key hogi par value null/empty hogi.
    if (request()->has('discharge_status')) {
        $discharge_status = request()->input('discharge_status');
    } else {
        $discharge_status = 'admitted'; // Default Active/Admitted Patients
    }
    // ------------------------------------------

    // Query builder
    $query = IpdPatient::with('doctor', 'patient', 'beds', 'charges', 'payments');

    // 1. Search Logic
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('ipd_no', 'like', "%$search%")
                ->orWhereHas('patient', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->orWhereHas('patient', function ($q3) use ($search) {
                    $q3->whereHas('patientProfile', function ($q4) use ($search) {
                        $q4->where('patient_unique_id', 'like', "%$search%");
                    });
                });
        });
    }

    // 2. Filter by doctor
    if ($doctor_id) {
        $query->where('doctor_id', $doctor_id);
    }

    // 3. Filter by Discharge Status (UPDATED)
    if ($discharge_status == 'admitted') {
        $query->whereNull('discharge_date');
    } elseif ($discharge_status == 'discharged') {
        $query->whereNotNull('discharge_date');
    }
    // Note: Agar $discharge_status empty/null hai (Matlab "All Status"), to koi filter nahi lagega.

    // 4. Filter by Payment Due Status
    if ($due_status) {
        $chargesTable = (new \App\Models\AddCharge)->getTable();
        $paymentsTable = (new \App\Models\IpdPayment)->getTable();

        $chargesQuery = "(SELECT COALESCE(SUM(total), 0) FROM $chargesTable WHERE $chargesTable.ipd_no = ipd_patients.ipd_no)";
        $paymentsQuery = "(SELECT COALESCE(SUM(amount), 0) FROM $paymentsTable WHERE $paymentsTable.ipd_no = ipd_patients.ipd_no)";

        if ($due_status == 'pending') {
            $query->whereRaw("$chargesQuery > $paymentsQuery");
        } elseif ($due_status == 'clear') {
            $query->whereRaw("$chargesQuery <= $paymentsQuery");
        }
    }

    // 5. Filter by admission date range
    if ($from_date) {
        $query->whereDate('admission_date', '>=', $from_date);
    }
    if ($to_date) {
        $query->whereDate('admission_date', '<=', $to_date);
    }

    $ipd_patients = $query->orderByDesc('id')->paginate($perPage);

    // Doctors list for filter dropdown
    $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();

    return view('ipd.ipdpatient', compact(
        'ipd_patients',
        'search',
        'doctor_id',
        'from_date',
        'to_date',
        'doctors',
        'discharge_status', // View me pass karein taaki dropdown me selected dikhe
        'due_status'
    ));
}
    // ...existing code...
    // public function add_ipd_patient()
    // {
    //     $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
    //     $patients = User::role('Patient')->select('id', 'name', 'email')->get();
    //     $uhid = DB::table('patients')
    //         ->select('patient_unique_id')
    //         ->where('user_id', $patients->first()->id)
    //         ->value('patient_unique_id');   
    //     $ipdno = $bedNumber = Str::upper(Str::random(8)); // e.g., A8K3Z1F2
    //     $bedtype = $bedTypes = BedType::select('id', 'name')->get();
    //     return view('ipd.new-ipdpatient', compact('patients', 'doctors', 'ipdno', 'bedtype'));
    // }


    public function add_ipd_patient(Request $request)
    {
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        // Patient ke saath UHID bhi join karen
        $patients = User::role('Patient')
            ->leftJoin('patients', 'users.id', '=', 'patients.user_id')
            ->select('users.id', 'users.name', 'users.email', 'patients.patient_unique_id as uhid')->orderBy('id', 'desc')
            ->get();
        $ipdno = $this->generateIpdNo(); // <-- yahan par function call karein
        $bedtype = BedType::select('id', 'name')->get();

        $prefill = [
            'uhid' => $request->get('uhid'),
            'patient_id' => $request->get('patient_id'),
            'doctor_id' => $request->get('doctor_id'),
            'symptoms' => $request->get('symptoms'),
            'height' => $request->get('height'),
            'weight' => $request->get('weight'),
            'blood_pressure' => $request->get('blood_pressure'),
            'bp' => $request->get('bp'),
            'pulse' => $request->get('pulse'),
            'temperature' => $request->get('temperature'),
            'SpO2' => $request->get('SpO2') ?? $request->get('spo2'),
            'respiratory_rate' => $request->get('respiratory_rate'),
            'rbs' => $request->get('rbs'),
            'general_appearance' => $request->get('general_appearance'),
            'cvs' => $request->get('cvs'),
            'rs' => $request->get('rs'),
            'pa' => $request->get('pa'),
            'cns' => $request->get('cns'),
            'other_systems' => $request->get('other_systems'),
        ];
        return view('ipd.new-ipdpatient', compact('patients', 'doctors', 'ipdno', 'bedtype', 'prefill'));
    }



    public function generateIpdNo()
    {
        $year = date('y'); // e.g., 25 for 2025

        // Get the last IPD number for the current year
        $lastIpd = IpdPatient::whereYear('created_at', now()->year)
            ->where('ipd_no', 'like', '%-' . $year)
            ->orderByDesc('id')
            ->first();

        if ($lastIpd && preg_match('/(?:TH|CB)-IPD-(\d{4})-' . $year . '/', $lastIpd->ipd_no, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $ipd_no = 'CB-IPD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '-' . $year;

        return $ipd_no;
    }

    public function edit_ipd_patient($id)
    {
        $patient = IpdPatient::where('id', $id)->first();
        if ($patient) {
            $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
            $patients = User::role('Patient')->select('id', 'name', 'email')->get();
            $bedtype = BedType::select('id', 'name')->get();
            $beds = Bed::all();
            $cases = \App\Models\PatientCase::find($patient->case);


            return view('ipd.edit-ipdpatient', compact('patient', 'id', 'patients', 'doctors', 'bedtype', 'beds', 'cases'));
        } else {
            return redirect()->back();
        }
    }



    public function ipd_patient_save(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'patient' => 'required',
            'case' => 'nullable|string|max:255',
            'ipd_no' => 'required|string|max:255|unique:ipd_patients,ipd_no',
            'height' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'admission_date' => 'nullable|date',
            'blood_pressure' => 'nullable|string|max:50',
            'bp' => 'nullable|string|max:50',
            'pulse' => 'nullable|string|max:50',
            'temperature' => 'nullable|string|max:50',
            'SpO2' => 'nullable|string|max:50',
            'respiratory_rate' => 'nullable|string|max:50',
            'rbs' => 'nullable|string|max:50',
            'general_appearance' => 'nullable|string|max:255',
            'cvs' => 'nullable|string|max:255',
            'rs' => 'nullable|string|max:255',
            'pa' => 'nullable|string|max:255',
            'cns' => 'nullable|string|max:255',
            'other_systems' => 'nullable|string|max:255',
            'doctor' => 'required|exists:users,id',
            'bed_type' => 'nullable|string|max:100',
            'bed' => 'required|string|max:50',
            // 'status' => 'required|boolean',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ❌ Check if the patient is already admitted (discharge_date is null)
            $existingIpd = IpdPatient::where('patient_id', $request->patient)
                ->whereNull('discharge_date')
                ->first();

            if ($existingIpd) {
                return redirect()->back()
                    ->with('error', 'This patient is already admitted and has not been discharged yet.')
                    ->withInput();
            }

            DB::beginTransaction();

            // ✅ Create the IPD Patient record
            IpdPatient::create([
                'ipd_no' => $request->ipd_no,
                'patient_id' => $request->patient,
                'case' => $request->case,
                'height' => $request->height,
                'weight' => $request->weight,
                'admission_date' => $request->admission_date,
                'blood_pressure' => $request->blood_pressure ?? $request->bp,
                'bp' => $request->bp ?? $request->blood_pressure,
                'pulse' => $request->pulse,
                'temperature' => $request->temperature,
                'SpO2' => $request->SpO2 ?? $request->spo2,
                'respiratory_rate' => $request->respiratory_rate,
                'rbs' => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs' => $request->cvs,
                'rs' => $request->rs,
                'pa' => $request->pa,
                'cns' => $request->cns,
                'other_systems' => $request->other_systems,
                'doctor_id' => $request->doctor,
                'bed_type' => $request->bed_type,
                'bed' => $request->bed,
                'status' => $request->status ?? 1,
                'symptoms' => $request->symptoms,
                'notes' => $request->notes,
            ]);

            // ✅ Assign bed
            DB::table('bed_assigns')->insert([
                'patient_id' => $request->patient,
                'bed_id' => $request->bed,
                'case' => $request->case,
                'ipd_no' => $request->ipd_no,
                'assigned_date' => $request->admission_date ?? now(),
            ]);

            DB::commit();

            return redirect()->route('admin.ipd.index')
                ->with('success', 'IPD Patient Record Added Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while saving the IPD Patient record! ' . $e->getMessage())
                ->withInput();
        }
    }


    public function ipd_patient_edit_save(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'patient' => "required", // Ensure the patient exists in the users table
            'case' => 'nullable|string|max:255',
            'ipd_no' => "required|string|max:255|unique:ipd_patients,ipd_no,$id,id", // Exclude the current record from uniqueness validation
            'height' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'admission_date' => 'nullable|date',
            'blood_pressure' => 'nullable|string|max:50',
            'bp' => 'nullable|string|max:50',
            'pulse' => 'nullable|string|max:50',
            'temperature' => 'nullable|string|max:50',
            'SpO2' => 'nullable|string|max:50',
            'respiratory_rate' => 'nullable|string|max:50',
            'rbs' => 'nullable|string|max:50',
            'general_appearance' => 'nullable|string|max:255',
            'cvs' => 'nullable|string|max:255',
            'rs' => 'nullable|string|max:255',
            'pa' => 'nullable|string|max:255',
            'cns' => 'nullable|string|max:255',
            'other_systems' => 'nullable|string|max:255',
            'doctor' => "required|exists:users,id",
            'bed_type' => 'required|string|max:100',
            'bed' => 'required|string|max:50',
            // 'status' => 'required|boolean',
            'symptoms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Find the existing IPD Patient record by ID and update it
            $ipdPatient = IpdPatient::findOrFail($id);

            $ipdPatient->update([
                'patient_id' => $request->patient,
                'case' => $request->case,
                'ipd_no' => $request->ipd_no,
                'height' => $request->height,
                'weight' => $request->weight,
                'admission_date' => $request->admission_date,
                'blood_pressure' => $request->blood_pressure ?? $request->bp,
                'bp' => $request->bp ?? $request->blood_pressure,
                'pulse' => $request->pulse,
                'temperature' => $request->temperature,
                'SpO2' => $request->SpO2 ?? $request->spo2,
                'respiratory_rate' => $request->respiratory_rate,
                'rbs' => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs' => $request->cvs,
                'rs' => $request->rs,
                'pa' => $request->pa,
                'cns' => $request->cns,
                'other_systems' => $request->other_systems,
                'doctor_id' => $request->doctor,
                'bed_type' => $request->bed_type,
                'bed' => $request->bed,
                'status' => $request->status ?? 1,
                'symptoms' => $request->symptoms,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.ipd.index')
                ->with('success', 'IPD Patient Record Updated Successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while updating the IPD Patient record!'
                    . ' ' . $e->getMessage())
                ->withInput();
        }
    }

    public function ipd_patient_detail($id)
    {
        $ipd_patient = IpdPatient::with('doctor', 'patient', 'beds')->where('id', $id)->first();
        // dd($ipd_patient->patient_id);
        $patient = Patient::where('user_id', $ipd_patient->patient_id)->first();

        if ($ipd_patient) {
            $pathology_bills = \App\Models\PathologyBill::where(function($q) use ($ipd_patient) {
                $q->where('patient_id', $ipd_patient->patient_id)->orWhere('ipd_patient_id', $ipd_patient->id);
            })->orderBy('id', 'desc')->get();
            $pharmacy_bills = \App\Models\MedicineBill::where(function($q) use ($ipd_patient) {
                $q->where('patient_id', $ipd_patient->patient_id)->orWhere('ipd_patient_id', $ipd_patient->id);
            })->orderBy('id', 'desc')->get();

            $prescriptions = \App\Models\Prescription::with('medicines.medicine')
                ->where('patient_id', $ipd_patient->patient_id)
                ->orderByDesc('id')
                ->get();
            $medicine_categories = \App\Models\MedicineCategory::all();
            $medicines = \App\Models\Medicine::orderBy('medicine', 'asc')->get();

            $progress_notes = \App\Models\IpdProgressNote::where('ipd_patient_id', $ipd_patient->id)
                ->with(['medicines.category', 'addendums'])
                ->orderBy('date_time', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            $vitals_history = IpdVital::where('ipd_patient_id', $ipd_patient->id)
                ->orderByDesc('recorded_at')
                ->get();

            $active_medications = \App\Models\PatientMedication::where('ipd_patient_id', $ipd_patient->id)
                ->with(['doctor', 'administrations.administeredBy'])
                ->orderBy('status', 'asc')
                ->orderBy('id', 'desc')
                ->get();

            $mar_date = request('mar_date', now()->toDateString());

            $mar_doses_today = \App\Models\MedicationAdministration::where('ipd_patient_id', $ipd_patient->id)
                ->whereDate('scheduled_at', $mar_date)
                ->with(['medication', 'administeredBy'])
                ->orderBy('scheduled_at', 'asc')
                ->get();

            $mar_history = \App\Models\MedicationAdministration::where('ipd_patient_id', $ipd_patient->id)
                ->with(['medication', 'administeredBy'])
                ->orderBy('scheduled_at', 'desc')
                ->take(100)
                ->get();

            $investigations = \App\Models\IpdInvestigation::where('ipd_patient_id', $ipd_patient->id)
                ->with(['doctor', 'orderedBy'])
                ->orderBy('id', 'desc')
                ->get();

            $doctors = \App\Models\User::role('Doctor')->select('id', 'name', 'email')->get();

            return view('ipd.ipdpatient-detail', compact(
                'ipd_patient',
                'patient',
                'pathology_bills',
                'pharmacy_bills',
                'prescriptions',
                'medicine_categories',
                'medicines',
                'progress_notes',
                'vitals_history',
                'active_medications',
                'mar_date',
                'mar_doses_today',
                'mar_history',
                'investigations',
                'doctors'
            ));
        } else {
            return redirect()->back();
        }
    }

    public function updateVitals(Request $request, $id)
    {
        try {
            $ipdPatient = IpdPatient::findOrFail($id);

            $request->validate([
                'recorded_at'        => 'nullable',
                'recorded_by'        => 'nullable|string|max:255',
                'shift'              => 'nullable|string|max:50',
                'height'             => 'nullable|string|max:50',
                'weight'             => 'nullable|string|max:50',
                'blood_pressure'     => 'nullable|string|max:50',
                'bp'                 => 'nullable|string|max:50',
                'pulse'              => 'nullable|string|max:50',
                'temperature'        => 'nullable|string|max:50',
                'SpO2'               => 'nullable|string|max:50',
                'spo2'               => 'nullable|string|max:50',
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

            $bpVal = $request->blood_pressure ?? $request->bp;
            $spo2Val = $request->SpO2 ?? $request->spo2;
            $recordedAt = $request->recorded_at ?: now();
            $staffName = $request->recorded_by ?: (auth()->user() ? auth()->user()->name : 'Staff');

            // Save new snapshot in ipd_vitals table
            IpdVital::create([
                'patient_id'         => $ipdPatient->patient_id,
                'ipd_patient_id'     => $ipdPatient->id,
                'recorded_at'        => $recordedAt,
                'recorded_by'        => $staffName,
                'shift'              => $request->shift,
                'blood_pressure'     => $bpVal,
                'pulse'              => $request->pulse,
                'temperature'        => $request->temperature,
                'spo2'               => $spo2Val,
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

            // Also update ipd_patients row for quick access / backward compatibility
            $ipdPatient->update([
                'height'             => $request->height,
                'weight'             => $request->weight,
                'blood_pressure'     => $bpVal,
                'bp'                 => $bpVal,
                'pulse'              => $request->pulse,
                'temperature'        => $request->temperature,
                'SpO2'               => $spo2Val,
                'respiratory_rate'   => $request->respiratory_rate,
                'rbs'                => $request->rbs,
                'general_appearance' => $request->general_appearance,
                'cvs'                => $request->cvs,
                'rs'                 => $request->rs,
                'pa'                 => $request->pa,
                'cns'                => $request->cns,
                'other_systems'      => $request->other_systems,
            ]);

            return redirect()->back()->with('success', 'Vitals and examination findings recorded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating vitals: ' . $e->getMessage());
        }
    }

    public function ipd_patient_add_prescription($id)
    {
        $ipd = IpdPatient::with('doctor', 'patient', 'beds')->where('id', $id)->first();
        // dd($ipd);
        $uhid = DB::table('patients')
            ->select('patient_unique_id')
            ->where('user_id', $ipd->patient_id)
            ->value('patient_unique_id');

        $medicineCategories = DB::table('medicine_categories')->select('id', 'medicine_category')->get();
        $medicineBrands = DB::table('medicine_brands')->select('id', 'brand')->get();
        $medicines = DB::table('medicines')
            ->select('id', 'medicine', 'brand', 'category', 'salt_composition', 'buying_price', 'selling_price', 'side_effects', 'description', 'quantity', 'created_at', 'updated_at')
            ->get();
        if ($ipd) {
            return view('ipd.add-prescription', compact('ipd', 'medicineCategories', 'medicineBrands', 'medicines', 'uhid'));
        } else {
            return redirect()->back();
        }
    }


    public function ipdcharge($id)
    {
        $ipdPatient = AddCharge::with(['chargeCategory', 'chargeType'])->where('ipd_no', $id)->get();
        return response()->json(['data' => $ipdPatient]);
    }

    // public function dischargeslip($id)
    // {



    //     $ipd_patient = IpdPatient::with('doctor', 'patient')->where('patient_id', $id)->first();
    //     $diagnosis = DiagnosisTest::with('diagnosisCategory')->where('patient_id', $id)->get();
    //     $prescriptions = Prescription::with([
    //         'prescriptionMedicines.medicine.categoryRelation'
    //     ])->where('patient_id', $id)->get();
    //     $beds = BedAssign::with('bed')->where('patient_id', $id)->first();




    //     $pdf = Pdf::loadView('ipd.discharge-slip-pdf', compact(
    //         'ipd_patient',
    //         'diagnosis',
    //         'prescriptions',
    //         'beds'


    //     ));


    //     return $pdf->stream('billing.pdf');


    // }


    public function discharge(Request $request, $id)
    {
        try {
            $ipd = IpdPatient::findOrFail($id); // Throws 404 if not found

            // --- STEP 1: CALCULATE DUE AMOUNT ---

            // 1. Calculate Bed Charges (Days * Bed Charge)
            $bed_assign = BedAssign::where('ipd_no', $ipd->ipd_no)->first();
            $bed_amount = 0;

            if ($bed_assign && $bed_assign->bed) {
                $admission_date = Carbon::parse($ipd->admission_date);
                $discharge_date = Carbon::now();

                // Calculate total days (minimum 1 day)
                $days = $admission_date->diffInDays($discharge_date);
                $days = ($days == 0) ? 1 : $days;

                $bed_amount = $days * $bed_assign->bed->charge;
            }

            // 2. Calculate Other Charges (from Charge Table)
            $other_charges = AddCharge::where('ipd_no', $ipd->ipd_no)->sum('total');

            // 3. Calculate Total Bill
            // Note: Agar aap Tax/Discount bhi store kar rahe hain toh unhe yahan adjust karein
            $gross_total = $other_charges;

            // 4. Calculate Total Paid Amount
            $total_paid = IpdPayment::where('ipd_no', $ipd->ipd_no)->sum('amount');

            // 5. Calculate Net Payable (Due)
            // Use round() to avoid decimal floating point issues (e.g. 0.0000001)
            $due_amount = round($gross_total - $total_paid, 2);

            // --- STEP 2: CHECK DUE CONDITION ---

            if ($due_amount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Discharge! Patient has pending dues of Rs ' . number_format($due_amount, 2) . '. Please clear the bill first.',
                    'status_code' => 500 // 0 means Warning/Error for your JS

                ]);
            }

            // --- STEP 3: PROCEED TO DISCHARGE ---
            $now = Carbon::now();

            // Update Bed Assign
            if ($bed_assign) {
                $bed_assign->discharged_date = $now;
                $bed_assign->status = 'discharged';
                $bed_assign->save();
            }

            // Save or Update in SEPARATE Table (ipd_discharges)
            IpdDischarge::updateOrCreate(
                ['ipd_patient_id' => $id],
                [
                    'discharge_date' => $now,
                    'discharge_status' => 'Normal',
                ]
            );

            // Update IPD Patient
            $ipd->discharge_date = $now;
            $ipd->status = 0; // ✅ Status set to 0 as requested
            $ipd->save();

            return response()->json([
                'success' => true,
                'message' => 'IPD Patient discharged successfully!',
                'redirect_to' => url()->previous(),
                'status_code' => 1 // Success
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'msg' => 'An error occurred while discharging the patient!',
                'error' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }


    public function blankReceipt($id)
    {
        $ipdPatient = IpdPatient::with(['patient', 'doctor', 'beds'])->findOrFail($id);
        // dd($ipdPatient);
        $patient_uhid = Patient::where('user_id', $ipdPatient->patient->id)->value('patient_unique_id');
        return view('ipd.blank-receipt', compact('ipdPatient', 'patient_uhid'));
    }

    // public function dischargeSlip($id)
    // {
    //     // Patient ka data fetch karein relations ke saath
    //     $ipdPatient = IpdPatient::with(['patient', 'doctor', 'beds', 'charges', 'payments'])->findOrFail($id);

    //     // View return karein (aapko ye view file banani padegi)
    //     return view('ipd.discharge_slip', compact('ipdPatient'));
    // }

    public function getPaymentHistory($id)
    {
        // Patient dhoondhein aur payments load karein
        $ipdPatient = IpdPatient::with([
            'payments' => function ($q) {
                $q->orderBy('created_at', 'desc'); // Check column name: 'date' or 'payment_date' in ipd_payments table
            }
        ])->findOrFail($id);

        // Data format karein
        $history = $ipdPatient->payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                // Date format check karein apne DB ke hisab se
                'date' => Carbon::parse($payment->payment_date)->format('d-M-Y h:i A'),
                'amount' => number_format($payment->amount, 2),
                // Mode column check karein (payment_mode ya mode)
                'mode' => $payment->payment_mode ?? 'Cash',
                // Print URL generate karein
                'print_url' => route('admin.ipd.payment.print', $payment->id)
            ];
        });

        return response()->json([
            'status' => 1,
            'ipd_no' => $ipdPatient->ipd_no,
            'data' => $history
        ]);
    }

    public function printPaymentReceipt($id)
    {

        $payment = IpdPayment::with(['ipdPatient.patient', 'ipdPatient.charges', 'ipdPatient.payments'])->findOrFail($id);

        $uhid = DB::table('patients')->where('user_id', $payment->ipdPatient->patient_id)->value('patient_unique_id');

        $balanceDue = 0;

        if ($payment->ipdPatient) {


            $totalCharges = $payment->ipdPatient->charges->sum('total');

            // Total Paid (Sum of all payments)
            $totalPaid = $payment->ipdPatient->payments->sum('amount');

            // Final Balance
            $balanceDue = $totalCharges - $totalPaid;
        }

        // 3. View return karein variable ke saath
        return view('ipd.print_payment', compact('payment', 'balanceDue', 'uhid'));
    }


    public function dischargeForm($id)
    {
        // Load IPD Patient with related models
        $ipdPatient = IpdPatient::with([
            'patient.patientProfile',
            'doctor',
            'beds.bedType',
            'discharge'
        ])->findOrFail($id);


        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        if (empty($uhid)) {
            $uhid = $ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null);
        }

        // Fetch admission vitals if recorded
        $admissionVitals = DB::table('ipd_vitals')
            ->where('ipd_patient_id', $id)
            ->orderBy('recorded_at', 'asc')
            ->first();

        if (!$admissionVitals) {
            $admissionVitals = DB::table('patient_vitals')
                ->where('patient_id', $ipdPatient->patient_id)
                ->orderBy('recorded_at', 'asc')
                ->first();
        }

        return view('ipd.discharge_form', compact('ipdPatient', 'uhid', 'admissionVitals'));
    }

    // 2. Save Data (Separate Table Logic)
    public function dischargeSave(Request $request, $id)
    {
        $submitAction = $request->input('submit_action', 'draft');
        $isDraft = $submitAction === 'draft';
        $andPrint = $submitAction === 'save_and_print';

        $rules = [
            'discharge_date' => $isDraft ? 'nullable' : 'required',
            'discharge_status' => $isDraft ? 'nullable' : 'required',
        ];
        $request->validate($rules);

        $patient = IpdPatient::findOrFail($id);

        if (!$isDraft && $patient->status == 1) {
            // Calculate due amount
            $other_charges = AddCharge::where('ipd_no', $patient->ipd_no)->sum('total');
            $total_paid = IpdPayment::where('ipd_no', $patient->ipd_no)->sum('amount');
            $due_amount = round($other_charges - $total_paid, 2);

            if ($due_amount > 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Cannot Discharge! Patient has pending dues of Rs ' . number_format($due_amount, 2) . '. Please clear the bill first.');
            }
        }

        // Format investigations: if array of structured rows or raw text
        $investigations = $request->investigations;
        if ($request->has('inv_name') && is_array($request->inv_name)) {
            $invItems = [];
            foreach ($request->inv_name as $idx => $name) {
                if (!empty(trim($name)) || !empty(trim($request->inv_result[$idx] ?? ''))) {
                    $invItems[] = [
                        'name' => trim($name),
                        'result' => trim($request->inv_result[$idx] ?? '')
                    ];
                }
            }
            if (!empty($invItems)) {
                $investigations = json_encode($invItems);
            }
        }

        // Format procedures: if array of structured rows or raw text
        $procedures = $request->procedures;
        if ($request->has('proc_name') && is_array($request->proc_name)) {
            $procItems = [];
            foreach ($request->proc_name as $idx => $name) {
                if (!empty(trim($name)) || !empty(trim($request->proc_findings[$idx] ?? '')) || !empty(trim($request->proc_date[$idx] ?? ''))) {
                    $procItems[] = [
                        'date' => trim($request->proc_date[$idx] ?? ''),
                        'name' => trim($name),
                        'findings' => trim($request->proc_findings[$idx] ?? '')
                    ];
                }
            }
            if (!empty($procItems)) {
                $procedures = json_encode($procItems);
            }
        }

        // Format medications: if array of structured rows or raw text
        $medications = $request->discharge_medications;
        if ($request->has('med_name') && is_array($request->med_name)) {
            $medItems = [];
            foreach ($request->med_name as $idx => $name) {
                if (!empty(trim($name))) {
                    $medItems[] = [
                        'medicine' => trim($name),
                        'dose' => trim($request->med_dose[$idx] ?? ''),
                        'route' => trim($request->med_route[$idx] ?? ''),
                        'frequency' => trim($request->med_frequency[$idx] ?? ''),
                        'duration' => trim($request->med_duration[$idx] ?? ''),
                    ];
                }
            }
            if (!empty($medItems)) {
                $medications = json_encode($medItems);
            }
        }

        // Standard advice checkboxes
        $standardAdvice = $request->has('standard_advice') ? json_encode($request->standard_advice) : null;

        // Parse discharge date
        $dischargeDate = $request->discharge_date ? Carbon::parse($request->discharge_date) : now();

        // Save or Update in ipd_discharges table
        IpdDischarge::updateOrCreate(
            ['ipd_patient_id' => $id],
            [
                'discharge_date' => $dischargeDate,
                'discharge_status' => $request->discharge_status ?? ($isDraft ? 'Draft' : 'Normal'),
                'father_spouse' => $request->father_spouse,
                'diagnosis' => $request->diagnosis,
                'history' => $request->history,
                'condition_at_admission' => $request->condition_at_admission,
                'bp' => $request->bp,
                'pulse' => $request->pulse,
                'rr' => $request->rr,
                'spo2' => $request->spo2,
                'temperature' => $request->temperature,
                'gcs' => $request->gcs,
                'systemic_exam' => $request->systemic_exam,
                'investigations' => $investigations,
                'treatment_given' => $request->treatment_given,
                'procedures' => $procedures,
                'condition_at_discharge' => $request->condition_at_discharge,
                'general_condition' => $request->general_condition,
                'consciousness' => $request->consciousness,
                'mobility' => $request->mobility,
                'diet' => $request->diet,
                'discharge_medications' => $medications,
                'advice' => $request->advice,
                'precautions_followup' => $request->precautions_followup,
                'followup_date' => $request->followup_date,
                'followup_doctor' => $request->followup_doctor,
                'special_instructions' => $request->special_instructions,
                'treating_physician' => $request->treating_physician,
                'standard_advice_flags' => $standardAdvice,
            ]
        );

        if (!$isDraft) {
            // Sync discharge_date to the main ipd_patients table
            $patient->update([
                'status' => 0,
                'discharge_date' => $dischargeDate
            ]);

            // Mark bed as discharged in bed_assigns
            DB::table('bed_assigns')
                ->where('ipd_no', $patient->ipd_no)
                ->update([
                    'discharged_date' => $dischargeDate,
                    'status' => 'discharged'
                ]);
        }

        if ($andPrint) {
            return redirect()->route('admin.ipd.discharge.slip', $id);
        }

        if ($isDraft) {
            return redirect()->back()->with('success', 'Discharge Summary Draft Saved Successfully!');
        }

        return redirect()->route('admin.ipd.index')->with('success', 'Patient Discharged Successfully!');
    }

    // 3. Print Slip
    public function dischargeSlip($id)
    {
        $ipdPatient = IpdPatient::with([
            'patient.patientProfile',
            'doctor',
            'beds.bedType',
            'discharge'
        ])->findOrFail($id);


        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        if (empty($uhid)) {
            $uhid = $ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null);
        }

        return view('ipd.discharge_slip', compact('ipdPatient', 'uhid'));
    }



    public function print_admission_slip($id)
    {
        $ipdPatient = IpdPatient::with(['patient.patientProfile', 'doctor', 'beds.bedType'])->findOrFail($id);

        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        if (empty($uhid)) {
            $uhid = $ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null);
        }

        return view('ipd.print.admission_slip', compact('ipdPatient', 'uhid'));
    }

    public function print_discharge_slip($id)
    {
        $ipdPatient = IpdPatient::with(['patient.patientProfile', 'doctor', 'beds.bedType', 'discharge'])->findOrFail($id);

        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        if (empty($uhid)) {
            $uhid = $ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null);
        }

        return view('ipd.print.discharge_slip', compact('ipdPatient', 'uhid'));
    }

    public function print_no_dues($id)
    {
        $ipdPatient = IpdPatient::with(['patient.patientProfile', 'doctor', 'beds.bedType', 'discharge'])->findOrFail($id);
        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        if (empty($uhid)) {
            $uhid = $ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null);
        }
        return view('ipd.print.no_dues', compact('ipdPatient', 'uhid'));
    }

    public function print_pos_label($id)
    {
        $ipdPatient = IpdPatient::with(['patient', 'doctor', 'beds'])->findOrFail($id);
        $uhid = DB::table('patients')->where('user_id', $ipdPatient->patient_id)->value('patient_unique_id');
        $user = User::findOrFail($ipdPatient->patient_id);
        
        return view('ipd.print.pos_label', compact('ipdPatient', 'uhid', 'user'));
    }

    public function print_service_chart($id)
    {
        $ipdPatient = IpdPatient::with(['doctor', 'patient', 'beds'])->findOrFail($id);
        $patient_uhid = DB::table('patients')
            ->where('user_id', $ipdPatient->patient_id)
            ->value('patient_unique_id');

        return view('ipd.print-service-chart', compact('ipdPatient', 'patient_uhid'));
    }
}
