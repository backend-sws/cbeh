<?php
namespace App\Http\Controllers;

use App\Events\PaymentCollectionEvent;
use Psy\Util\Str;
use App\Models\ChargeType;
use App\Models\OpdPatient;
use Illuminate\Http\Request;
use App\Models\ChargeCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OpdDiagnosisTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OpdDiagnosisTestController extends Controller
{



    public function index(Request $request)
    {
        $opd_patient_id = $request->opd_patient_id;
        $group_test_no = $request->group_test_no;

        $query = OpdDiagnosisTest::with(['chargeCategory', 'chargeType'])
            ->where('opd_patient_id', $opd_patient_id);

        if ($group_test_no) {
            $query->where('group_test_no', $group_test_no);
        }

        $tests = $query->get();
        // dd($tests);
        $opd_patient = OpdPatient::with(['patient', 'doctor'])->find($opd_patient_id);

        return response()->json([
            'patient' => [
                'name' => $opd_patient->patient->name ?? '',
            ],
            'doctor' => [
                'name' => $opd_patient->doctor->name ?? '',
            ],
            'opd_no' => $opd_patient->opd_no ?? '',
            'uhid' => $opd_patient->patient_unique_id ?? '',
            'appointment_date' => $opd_patient->appointment_date ? \Carbon\Carbon::parse($opd_patient->appointment_date)->format('jS M, Y h:i A') : '',
            'tests' => $tests->map(function ($test) {
                return [
                    'test_name' => $test->test_name,
                    'test_code' => $test->test_code,
                    'test_type' => $test->test_type,
                    'urgency' => $test->urgency,
                    'test_date' => $test->test_date,
                    'standard_charge' => $test->price,
                    'charge_category_name' => $test->chargeCategory->name ?? '',
                    'charge_type_name' => $test->chargeType->name ?? '',
                    'created_at_formatted' => $test->created_at ? $test->created_at->format('d M Y, h:i A') : '',
                ];
            }),
        ]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'opd_patient_id' => 'required|exists:opd_patients,id',
    //         'patient_id' => 'required|exists:users,id',
    //         'doctor_id' => 'nullable|exists:users,id',
    //         'opd_no' => 'nullable|string|max:255',
    //         'test_name' => 'required|array',
    //         'test_name.*' => 'required|string|max:255',
    //         'test_type' => 'array',
    //         'urgency' => 'array',
    //         'test_date' => 'array',
    //         'clinical_indication' => 'array',
    //         'special_instructions' => 'array',
    //     ]);

    //     $count = count($request->test_name);

    //     // Generate a unique group_test_no for this submission
    //     $group_test_no = 'GT' . date('YmdHis') . strtoupper(substr(uniqid(), -4));

    //     for ($i = 0; $i < $count; $i++) {
    //         // Auto-generate test code: e.g. TEST-YYYYMMDD-UNIQUE
    //         $testCode = 'TEST-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    //         OpdDiagnosisTest::create([
    //             'opd_patient_id' => $request->opd_patient_id,
    //             'patient_id' => $request->patient_id,
    //             'doctor_id' => $request->doctor_id,
    //             'opd_no' => $request->opd_no,
    //             'group_test_no' => $group_test_no,
    //             'test_count' => $count,
    //             'test_name' => $request->test_name[$i],
    //             'test_code' => $testCode,
    //             'test_type' => $request->test_type[$i] ?? null,
    //             'urgency' => $request->urgency[$i] ?? null,
    //             'test_date' => $request->test_date[$i] ?? null,
    //             'clinical_indication' => $request->clinical_indication[$i] ?? null,
    //             'special_instructions' => $request->special_instructions[$i] ?? null,
    //         ]);
    //     }
    //     return response()->json(['success' => true]);
    // }

    public function show($id)
    {
        $test = OpdDiagnosisTest::findOrFail($id);
        return response()->json($test);
    }



    public function generateDiagnosisPdf($groupTestNo)
    {
        try {
            // dd('Generating PDF for Group Test No: ' . $groupTestNo);
            // Step 1: Get test data with relationships
            $tests = OpdDiagnosisTest::with(['patient', 'doctor', 'chargeCategory', 'chargeType'])
                ->where('group_test_no', $groupTestNo)
                ->get();
            // dd($tests);

            $invoice_no = DB::table('opd_diagnosis_bill')
                ->where('group_test_no', $groupTestNo)
                ->value('invoice_no');


            if ($tests->isEmpty()) {
                return back()->with('error', 'No tests found for this group');
            }

            $firstTest = $tests->first();

            // Step 2: Get OPD Patient details
            $opdPatient = OpdPatient::where('id', $firstTest->opd_patient_id)->first();
            if (!$opdPatient) {
                return back()->with('error', 'OPD Patient not found');
            }

            // Step 3: Get UHID
            $patient_uhid = DB::table('patients')
                ->where('user_id', $opdPatient->patient_id)
                ->value('patient_unique_id');
            // dd($patient_uhid);

            // Step 4: Prepare view data
            $data = [
                'tests' => $tests,
                'patient' => $firstTest->patient,
                'patient_uhid' => $patient_uhid,
                'doctor' => $firstTest->doctor,
                'opd_no' => $opdPatient->opd_no,
                'opdPatient' => $opdPatient,
                'group_test_no' => $groupTestNo,
                'generated_date' => now()->format('d-m-Y h:i A'),
                'total_amount' => $tests->sum('price'),
                'invoice_no' => $invoice_no,

            ];
            // dd($data);

            // dd($data);

            // Step 5: Return view instead of PDF for direct printing
            return view('opd.diagnosis-report-pdf', $data);

        } catch (\Exception $e) {
            Log::error('Report Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $test = OpdDiagnosisTest::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'test_name' => 'required|string|max:255',
            'test_code' => 'nullable|string|max:100',
            'test_type' => 'nullable|string|max:50',
            'urgency' => 'nullable|string|max:20',
            'clinical_indication' => 'nullable|string|max:1000',
            'special_instructions' => 'nullable|string|max:1000',
            'test_date' => 'nullable|date',
            'result' => 'nullable|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $test->update($validator->validated());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $test = OpdDiagnosisTest::findOrFail($id);
        $test->delete();
        return response()->json(['success' => true]);
    }



    // In your controller (e.g., DiagnosisTestController.php)

    // Get charge types for a category
    // public function getChargeTypes(Request $request)
    // {
    //     $chargeCategoryId = $request->input('charge_category_id');

    //     $chargeTypes = DB::table('charge_types')
    //         ->join('hospital_charges', 'charge_types.id', '=', 'hospital_charges.charge_type')
    //         ->where('hospital_charges.charge_category', $chargeCategoryId)
    //         ->select('charge_types.id', 'charge_types.name')
    //         ->distinct()
    //         ->get();

    //     return response()->json($chargeTypes);
    // }

    // // Get standard charge for a category and type
    // public function getStandardCharge(Request $request)
    // {
    //     $chargeCategoryId = $request->input('charge_category_id');
    //     $chargeTypeId = $request->input('charge_type_id');

    //     $charge = DB::table('hospital_charges')
    //         ->where('charge_category', $chargeCategoryId)
    //         ->where('charge_type', $chargeTypeId)
    //         ->first();

    //     return response()->json([
    //         'standard_charge' => $charge->standard_charge ?? 0
    //     ]);
    // }



    // In your OpdDiagnosisTestController.php

    // Get charge categories for a test type
    public function getChargeCategories(Request $request)
    {
        $chargeTypeId = $request->input('charge_type_id');

        $chargeCategories = DB::table('charge_categories')
            ->join('hospital_charges', 'charge_categories.id', '=', 'hospital_charges.charge_category')
            ->where('hospital_charges.charge_type', $chargeTypeId)
            ->select('charge_categories.id', 'charge_categories.charge_category')
            ->distinct()
            ->get();

        return response()->json($chargeCategories);
    }

    // Get standard charge for a category and type
    public function getStandardCharge(Request $request)
    {
        $chargeCategoryId = $request->input('charge_category_id');
        $chargeTypeId = $request->input('charge_type_id');

        $charge = DB::table('hospital_charges')
            ->where('charge_category', $chargeCategoryId)
            ->where('charge_type', $chargeTypeId)
            ->first();

        return response()->json([
            'standard_charge' => $charge->standard_charge ?? 0
        ]);
    }
    // Store multiple diagnosis tests
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'charge_category_id' => 'required|array|min:1',
            'charge_type_id' => 'required|array|min:1',
            'charge_type_id.*' => 'required|exists:charge_types,id',
            'standard_charge' => 'required|array|min:1',
            'standard_charge.*' => 'required|numeric|min:0',
            'opd_patient_id' => 'required|exists:opd_patients,id',
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'opd_no' => 'nullable|string|max:255',
            'urgency' => 'required|array|min:1',
            'urgency.*' => 'required|string|max:20',
            'test_date' => 'required|array|min:1',

            'test_date.*' => 'required|date',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();

        // dd($validated);

        // Generate group test number in the same format as before
        $group_test_no = 'GT' . date('YmdHis') . strtoupper(substr(uniqid(), -4));
        // dd($group_test_no);

        $invoice_no = 'INV' . date('YmdHis') . mt_rand(100, 999);
        $count = count($validated['charge_category_id']);

        DB::table('opd_diagnosis_bill')->insert([
            'invoice_no' => $invoice_no,
            'group_test_no' => $group_test_no,
        ]);

        // dd($count);

        try {
            DB::beginTransaction();
            $total_amount = 0;
            foreach ($validated['charge_category_id'] as $index => $chargeCategoryId) {
                // Get category and type names for test name
                $categoryName = ChargeCategory::find($chargeCategoryId)->charge_category;
                $typeName = ChargeType::find($validated['charge_type_id'][$index])->name;

                // Auto-generate test code: e.g. TEST-YYYYMMDD-UNIQUE
                $testCode = 'TEST-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
                // dd($testCode);

                OpdDiagnosisTest::create([
                    'opd_patient_id' => $validated['opd_patient_id'],
                    'patient_id' => $validated['patient_id'],
                    'doctor_id' => $validated['doctor_id'],
                    'opd_no' => $validated['opd_no'],
                    'group_test_no' => $group_test_no,
                    'test_count' => $count,
                    'charge_category' => $chargeCategoryId,
                    'charge_type' => $validated['charge_type_id'][$index],
                    'price' => $validated['standard_charge'][$index],
                    'test_name' => " $typeName",
                    'test_code' => $testCode,
                    'test_type' => $categoryName, // Or determine based on category
                    'urgency' => $validated['urgency'][$index],
                    'test_date' => $validated['test_date'][$index],
                    'clinical_indication' => $validated['clinical_indication'][$index] ?? null,
                    'special_instructions' => $validated['special_instructions'][$index] ?? null,
                ]);
                $total_amount += $validated['standard_charge'][$index];
                // Log::info("Test saved: " . $validated['test_name'][$index] . " with amount: " . $total_amount);
            }


            DB::commit();
            // $data = [
            //     'amount' => $total_amount,
            //     'payment_mode_id' => "Cash", // Assuming default payment mode is Cash
            //     'patient_id' => $request->patient_id,
            //     // 'department_type' => 2, // Assuming 1 is the department type for OPD
            // ];



            $data = new \stdClass();
            $data->amount = $total_amount;
            $data->payment_mode_id = "Cash"; // Assuming default payment mode is Cash
            $data->patient_id = $request->patient_id;
            // event(new PaymentCollectionEvent($data, 'Opd Diagnosis Payment', $department_type = 2));


            return response()->json([
                'success' => true,
                'message' => 'Diagnosis tests saved successfully!',
                'group_test_no' => $group_test_no
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save diagnosis tests: ' . $e->getMessage()
            ], 500);
        }
    }
}