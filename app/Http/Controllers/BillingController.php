<?php

namespace App\Http\Controllers;

use App\Models\OpdPatient;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Billing\Billing;
use App\Models\Billing\EmployeePayroll;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    // public function index(Request $request)
    // {
    //     $search = $request->input('search', '');

    //     // Patient search by name or patient_unique_id (users.name, patients.patient_unique_id)
    //     $patient = \DB::table('patients')
    //         ->join('users', 'patients.user_id', '=', 'users.id')
    //         ->where(function ($q) use ($search) {
    //             $q->where('users.name', 'like', "%$search%")
    //                 ->orWhere('patients.patient_unique_id', 'like', "%$search%");
    //         })
    //         ->select('patients.*', 'users.name as patient_name', 'users.email as user_email', 'users.id as user_id')
    //         ->first();

    //     $opdPatients = collect();
    //     $ipdPatients = collect();
    //     $diagnosisTests = collect();

    //     if ($patient) {
    //         // OPD Patients (all)
    //         $opdPatients = \DB::table('opd_patients')
    //             ->leftJoin('users as doctors', 'opd_patients.doctor_id', '=', 'doctors.id')
    //             ->where('opd_patients.patient_id', $patient->user_id)
    //             ->select('opd_patients.*', 'doctors.name as doctor_name')
    //             ->get();

    //             // dd($opdPatients);

    //         // IPD Patients (all)
    //         $ipdPatients = \DB::table('ipd_patients')
    //             ->leftJoin('users as doctors', 'ipd_patients.doctor_id', '=', 'doctors.id')
    //             ->leftJoin('beds', 'ipd_patients.bed', '=', 'beds.id')
    //             ->where('ipd_patients.patient_id', $patient->user_id)
    //             ->select('ipd_patients.*', 'doctors.name as doctor_name', 'beds.name as bed_name')
    //             ->get();

    //         // Diagnosis Tests (all)
    //         $diagnosisTests = \DB::table('opd_diagnosis_tests')
    //             ->leftJoin('users as doctors', 'opd_diagnosis_tests.doctor_id', '=', 'doctors.id')
    //             ->where('opd_diagnosis_tests.patient_id', $patient->user_id)
    //             ->select('opd_diagnosis_tests.*', 'doctors.name as doctor_name')
    //             ->get();

    //             // dd($diagnosisTests);
    //     }

    //     return view('billing.index', compact('patient', 'opdPatients', 'ipdPatients', 'diagnosisTests', 'search'));
    // }



    // // billing 
    // public function list_bill(Request $request)
    // {

    //     $perPage = request()->input('perPage', 10);
    //     $search = request()->input('search', '');
    //     $query = Billing::query();
    //     $query =  $query->where('package_name', 'like', '%' . $search . '%')
    //               ->orWhere('patient_email', 'like', '%' . $search . '%')->paginate($perPage);
    //     $billings = $query ?? collect();
    //     // Fetch and return OPD patient data
    //     return view('billing.list_bill', compact('billings'));
    // }

    // public function add_bill()
    // {

    //     return view('billing.add_bill');
    // }
    // public function edit_bill(Request $request,$id)
    // {
    //     $bill=Billing::findOrFail($id);
    //     return view('billing.edit_bill',compact('id','bill'));
    // }

    // public function add_bill_save(Request $request)
    // {
    //     try {
    //         // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'admission_id' =>'required|string|max:255',
    //             'bill_date'=>'required|date',
    //             'patient_id' =>'required|string|max:255',
    //             'patient_email' =>'required|string|max:255|unique:billings,patient_email',
    //             'patient_dob' =>'required|date',
    //             'doctor_id' =>'required|string|max:255',
    //             'admission_date' =>'required|date',
    //             'discharge_date' =>'required|date',
    //             'package_name' =>'required|string|max:255',
    //             'insurance_id' =>'required|string|max:255',
    //             'total_days' =>'required|string|max:255',
    //             'policy_no' =>'required|string|max:255',
    //         ]);

    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'msg' => 'Some validation errors occurred!',
    //                 'errors' => $validator->errors(),
    //                 'status' => 422
    //             ]);
    //         }
    //         // Start a database transaction
    //         DB::beginTransaction();

    //         // Create  Appointment record
    //         Billing::create(
    //             [
    //             'admission_id' => $request->admission_id ,
    //             'bill_date'=> $request->bill_date ,
    //             'patient_id' => $request->patient_id ,
    //             'patient_email' => $request->patient_email ,
    //             'patient_dob' => $request->patient_dob ,
    //             'doctor_id' => $request->doctor_id ,
    //             'admission_date' => $request->admission_date ,
    //             'discharge_date' => $request->discharge_date ,
    //             'package_name' => $request->package_name ,
    //             'insurance_id' => $request->insurance_id ,
    //             'total_days' => $request->total_days ,
    //             'policy_no' => $request->policy_no ,
    //             ]
    //         );
    //         DB::commit();
    //         $redirect_to=route('admin.billing.list_bill');
    //         return response()->json([
    //             'msg' => 'New Bill Created Successfully!',
    //             'redirect_to'=>$redirect_to,
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'msg' => 'An error occurred while saving the bill record!',
    //             'errors' => $e->getMessage(),
    //             'status' => 500
    //         ]);
    //     }

    // }

    // public function edit_bill_save(Request $request,$id)
    // {
    //     try {
    //         // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'admission_id' =>'required|string|max:255',
    //             'bill_date'=>'required|date',
    //             'patient_id' =>'required|string|max:255',
    //             'patient_email' =>'required|string|max:255|unique:billings,patient_email,'.$id.',id',
    //             'patient_dob' =>'required|date',
    //             'doctor_id' =>'required|string|max:255',
    //             'admission_date' =>'required|date',
    //             'discharge_date' =>'required|date',
    //             'package_name' =>'required|string|max:255',
    //             'insurance_id' =>'required|string|max:255',
    //             'total_days' =>'required|string|max:255',
    //             'policy_no' =>'required|string|max:255',
    //         ]);

    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'msg' => 'Some validation errors occurred!',
    //                 'errors' => $validator->errors(),
    //                 'status' => 422
    //             ]);
    //         }
    //         // Start a database transaction
    //         DB::beginTransaction();

    //         // Create  Appointment record
    //         Billing::where('id',$id)->update(
    //             [
    //             'admission_id' => $request->admission_id ,
    //             'bill_date'=> $request->bill_date ,
    //             'patient_id' => $request->patient_id ,
    //             'patient_email' => $request->patient_email ,
    //             'patient_dob' => $request->patient_dob ,
    //             'doctor_id' => $request->doctor_id ,
    //             'admission_date' => $request->admission_date ,
    //             'discharge_date' => $request->discharge_date ,
    //             'package_name' => $request->package_name ,
    //             'insurance_id' => $request->insurance_id ,
    //             'total_days' => $request->total_days ,
    //             'policy_no' => $request->policy_no ,
    //             ]
    //         );
    //         DB::commit();
    //         $redirect_to=route('admin.billing.list_bill');
    //         return response()->json([
    //             'msg' => 'Bill Updated Successfully!',
    //             'redirect_to'=>$redirect_to,
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'msg' => 'An error occurred while saving the bill record!',
    //             'errors' => $e->getMessage(),
    //             'status' => 500
    //         ]);
    //     }
    // }
    // public function delete_bill(Request $request,$id)
    // {
    //     dd('dfdf');
    // }

    // // employee payroll 

    // public function list_employee_payroll(Request $request)
    // {

    //     $perPage = request()->input('perPage', 10);
    //     $search = request()->input('search', '');
    //     $query = EmployeePayroll::query();
    //     $query =  $query->where('payroll_id', 'like', '%' . $search . '%')
    //               ->orWhere('employee_id', 'like', '%' . $search . '%')->paginate($perPage);
    //     $employee_payrolls = $query ?? collect();
    //     // Fetch and return OPD patient data
    //     return view('billing.list_employee_payroll', compact('employee_payrolls'));
    // }

    // public function add_employee_payroll()
    // {

    //     return view('billing.add_employee_payroll');
    // }
    // public function edit_employee_payroll(Request $request,$id)
    // {
    //     $employee_payroll=EmployeePayroll::findOrFail($id);
    //     return view('billing.edit_employee_payroll',compact('id','employee_payroll'));
    // }

    // public function add_employee_payroll_save(Request $request)
    // {
    //     try {
    //         // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'sr_no' => 'required|integer',
    //             'payroll_id'=>'required|string|max:255|unique:employee_payrolls,payroll_id',
    //             'role' => 'required|string|max:255',
    //             'employee_id' => 'required|string|max:255',
    //             'month' => 'required|integer',
    //             'year' => 'required|integer',
    //             'status'=> 'required|string|max:255|in:paid,unpaid',
    //             'basic_salary'=> 'required|string|max:255',
    //             'allowance'=> 'required|string|max:255',
    //             'deduction'=> 'required|string|max:255',
    //             'net_salary'=> 'required|string|max:255',
    //         ]);

    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'msg' => 'Some validation errors occurred!',
    //                 'errors' => $validator->errors(),
    //                 'status' => 422
    //             ]);
    //         }
    //         // Start a database transaction
    //         DB::beginTransaction();

    //         // Create  Appointment record
    //         EmployeePayroll::create(
    //             [
    //             'sr_no' => $request->sr_no , 
    //             'payroll_id'=> $request->payroll_id , 
    //             'role' => $request->role , 
    //             'employee_id' => $request->employee_id , 
    //             'month' => $request->month , 
    //             'year' => $request->year , 
    //             'status'=> $request->status , 
    //             'basic_salary'=> $request->basic_salary , 
    //             'allowance'=> $request->allowance , 
    //             'deduction'=> $request->deduction , 
    //             'net_salary'=> $request->net_salary , 
    //             ]
    //         );
    //         DB::commit();
    //         $redirect_to=route('admin.billing.list_employee_payroll');
    //         return response()->json([
    //             'msg' => 'New Employee Payroll Created Successfully!',
    //             'redirect_to'=>$redirect_to,
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'msg' => 'An error occurred while saving the employee payroll record!',
    //             'errors' => $e->getMessage(),
    //             'status' => 500
    //         ]);
    //     }

    // }

    // public function edit_employee_payroll_save(Request $request,$id)
    // {
    //     try {
    //         // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'sr_no' => 'required|integer',
    //             'payroll_id'=>'required|string|max:255|unique:employee_payrolls,payroll_id,'.$id.',id',
    //             'role' => 'required|string|max:255',
    //             'employee_id' => 'required|string|max:255',
    //             'month' => 'required|integer',
    //             'year' => 'required|integer',
    //             'status'=> 'required|string|max:255|in:paid,unpaid',
    //             'basic_salary'=> 'required|string|max:255',
    //             'allowance'=> 'required|string|max:255',
    //             'deduction'=> 'required|string|max:255',
    //             'net_salary'=> 'required|string|max:255',
    //         ]);

    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'msg' => 'Some validation errors occurred!',
    //                 'errors' => $validator->errors(),
    //                 'status' => 422
    //             ]);
    //         }
    //         // Start a database transaction
    //         DB::beginTransaction();

    //         // Create  Appointment record
    //         EmployeePayroll::where('id',$id)->update(
    //             [
    //             'sr_no' => $request->sr_no , 
    //             'payroll_id'=> $request->payroll_id , 
    //             'role' => $request->role , 
    //             'employee_id' => $request->employee_id , 
    //             'month' => $request->month , 
    //             'year' => $request->year , 
    //             'status'=> $request->status , 
    //             'basic_salary'=> $request->basic_salary , 
    //             'allowance'=> $request->allowance , 
    //             'deduction'=> $request->deduction , 
    //             'net_salary'=> $request->net_salary , 
    //             ]
    //         );
    //         DB::commit();
    //         $redirect_to=route('admin.billing.list_employee_payroll');
    //         return response()->json([
    //             'msg' => 'Employee Payroll Updated Successfully!',
    //             'redirect_to'=>$redirect_to,
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'msg' => 'An error occurred while saving the employee payroll record!',
    //             'errors' => $e->getMessage(),
    //             'status' => 500
    //         ]);
    //     }

    // }
    // public function delete_employee_payroll(Request $request,$id)
    // {
    //     dd('dfdf');
    // }



    // //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function Invoice()
    // {
    //     // Fetch and return new OPD patient data
    //     return view('billing.invoice');
    // }
    // public function NewInvoice()
    // {
    //     // Fetch and return new OPD patient data
    //     return view('billing.newinvoice');
    // }
    // public function Account()
    // {
    //     // Fetch and return new OPD patient data
    //     return view('billing.account');
    // }

    // public function advancePayment()
    // {
    //     return view('billing.advancepayment');
    // }

    // public function newAdvancePayment()
    // {
    //     return view('billing.newadvancepayment');
    // }

    // public function payment()
    // {
    //     return view('billing.payment');
    // }

    // public function newPayment()
    // {
    //     return view('billing.newpayment');
    // }

    // public function paymentReport()
    // {
    //     return view('billing.paymentreport');
    // }

    // // public function employeePayroll()
    // // {
    // //     return view('billing.employeepayroll');
    // // }

    // // public function newEmployeePayroll()
    // // {
    // //     return view('billing.newemployeepayroll');
    // // }

    // public function manualBillingPayment()
    // {
    //     return view('billing.manualbillingpayment');
    // }

    // public function get_billing_charge($id)
    // {
    //     // Fetch billing charge details for the given ID
    //     $billingCharge = Billing::where('patient_id', $id)->get();

    //     if ($billingCharge->isEmpty()) {
    //         return response()->json(['message' => 'Billing charge not found'], 404);
    //     }

    //     return response()->json($billingCharge);
    // }



    public function index(Request $request)
{
    $search = $request->input('search', '');

    // Patient search
    $patient = \DB::table('patients')
        ->join('users', 'patients.user_id', '=', 'users.id')
        ->where(function ($q) use ($search) {
            $q->where('users.name', 'like', "%$search%")
                ->orWhere('patients.patient_unique_id', 'like', "%$search%");
        })
        ->select('patients.*', 'users.name as patient_name', 'users.email as user_email', 'users.id as user_id')
        ->first();

    $opdPatients = collect();
    $ipdPatients = collect();
    $diagnosisTests = collect();
    $pathologyBills = collect(); // NEW
    $pharmacyBills = collect();  // NEW

    if ($patient) {
        // 1. OPD Patients
        $opdPatients = \DB::table('opd_patients')
            ->leftJoin('users as doctors', 'opd_patients.doctor_id', '=', 'doctors.id')
            ->where('opd_patients.patient_id', $patient->user_id)
            ->select('opd_patients.*', 'doctors.name as doctor_name')
            ->orderBy('id', 'desc')
            ->get();

        // 2. IPD Patients
        $ipdPatients = \DB::table('ipd_patients')
            ->leftJoin('users as doctors', 'ipd_patients.doctor_id', '=', 'doctors.id')
            ->leftJoin('beds', 'ipd_patients.bed', '=', 'beds.id')
            ->where('ipd_patients.patient_id', $patient->user_id)
            ->select('ipd_patients.*', 'doctors.name as doctor_name', 'beds.name as bed_name')
            ->orderBy('id', 'desc')
            ->get();

        // 3. Diagnosis Tests
        $diagnosisTests = \DB::table('opd_diagnosis_tests')
            ->leftJoin('users as doctors', 'opd_diagnosis_tests.doctor_id', '=', 'doctors.id')
            ->where('opd_diagnosis_tests.patient_id', $patient->user_id)
            ->select('opd_diagnosis_tests.*', 'doctors.name as doctor_name')
            ->orderBy('id', 'desc')
            ->get();

        // 4. Pathology Bills (NEW)
        $pathologyBills = \DB::table('pathology_bills')
            ->leftJoin('users as doctors', 'pathology_bills.doctor_id', '=', 'doctors.id')
            ->where('pathology_bills.patient_id', $patient->user_id)
            ->select('pathology_bills.*', 'doctors.name as doctor_name')
            ->orderBy('id', 'desc')
            ->get();

        // 5. Pharmacy/Medicine Bills (NEW)
        // Note: Change 'pharmacy_bills' to your actual medicine bill table name
        $pharmacyBills = \DB::table('medicine_bills') 
            ->leftJoin('users as doctors', 'medicine_bills.doctor_id', '=', 'doctors.id')
            ->where('medicine_bills.patient_id', $patient->user_id)
            ->select('medicine_bills.*', 'doctors.name as doctor_name')
            ->orderBy('id', 'desc')
            ->get();
    }

    return view('billing.index', compact(
        'patient', 
        'opdPatients', 
        'ipdPatients', 
        'diagnosisTests', 
        'pathologyBills', 
        'pharmacyBills', 
        'search'
    ));
}
}
