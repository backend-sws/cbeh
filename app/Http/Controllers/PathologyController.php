<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ChargeType;
use App\Models\IpdPatient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PathologyBill;
use App\Models\PathologyTest;
use App\Models\PathologyUnit;
use App\Models\ChargeCategory;
use App\Models\PathologyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PathologyPayment;
use App\Models\PathologyCategory;
use App\Models\PathologyParameter;
use Illuminate\Support\Facades\DB;
use App\Models\ReportParameterValue;
use App\Events\PaymentCollectionEvent;
use App\Models\CollectionOfPathologyTest;
use Illuminate\Support\Facades\Validator;



class PathologyController extends Controller
{

    // Show list
    public function pathology_category_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');

        $query = PathologyCategory::query();
        $query = $query->where('name', 'like', '%' . $search . '%')
            ->paginate($perPage);

        $pathologyCategories = $query ?? collect();
        return view('pathology.pathologycategory', compact('pathologyCategories'));
    }

    // Save new category
    public function pathology_category_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            PathologyCategory::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Pathology category saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving.')
                ->withInput();
        }
    }

    // Delete category
    public function pathology_category_delete($id)
    {
        try {
            $category = PathologyCategory::findOrFail($id);
            $category->delete();

            return redirect()->back()->with('success', 'Pathology category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete the category.')
                ->withInput();
        }
    }

    public function pathology_category_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            $category = PathologyCategory::findOrFail($id);
            $category->update([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Pathology category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating.')
                ->withInput();
        }
    }

    public function pathology_unit_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');

        $query = PathologyUnit::query();
        $query = $query->where('name', 'like', '%' . $search . '%')
            ->paginate($perPage);

        $pathologyUnits = $query ?? collect();
        return view('pathology.pathologyunit', compact('pathologyUnits'));
    }

    public function pathology_unit_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            PathologyUnit::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Pathology unit saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving.')
                ->withInput();
        }
    }

    public function pathology_unit_delete($id)
    {
        try {
            $unit = PathologyUnit::findOrFail($id);
            $unit->delete();

            return redirect()->back()->with('success', 'Pathology unit deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete the unit.')
                ->withInput();
        }
    }

    public function pathology_unit_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            $unit = PathologyUnit::findOrFail($id);
            $unit->update([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Pathology unit updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating.')
                ->withInput();
        }
    }

    public function pathology_parameter_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');

        $query = PathologyParameter::query();
        $query = $query->where('name', 'like', '%' . $search . '%')
            ->paginate($perPage);

        $pathologyParameters = $query ?? collect();
        $pathologyUnits = PathologyUnit::all();
        return view('pathology.pathologyparameter', compact('pathologyParameters', 'pathologyUnits'));
    }

    public function pathology_parameter_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'reference_range' => 'required|string|max:255',
            'unit_id' => 'required|exists:pathology_units,id',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            PathologyParameter::create($request->only(['name', 'reference_range', 'unit_id', 'description']));
            return redirect()->back()->with('success', 'Pathology parameter saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error while saving parameter.')->withInput();
        }
    }

    public function pathology_parameter_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'reference_range' => 'required|string|max:255',
            'unit_id' => 'required|exists:pathology_units,id',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $parameter = PathologyParameter::findOrFail($id);
            $parameter->update($request->only(['name', 'reference_range', 'unit_id', 'description']));

            // ✅ Update reference value in report_parameter_values table
            DB::table('report_parameter_values')
                ->where('parameter_id', $parameter->id)
                ->update(['reference_range' => $request->reference_range]);

            DB::commit();

            return redirect()->back()->with('success', 'Pathology parameter and reference value updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while updating parameter: ' . $e->getMessage())->withInput();
        }
    }


    public function pathology_parameter_delete($id)
    {
        try {
            $parameter = PathologyParameter::findOrFail($id);
            $parameter->delete();
            return redirect()->back()->with('success', 'Pathology parameter deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting parameter.')->withInput();
        }
    }
    // pathology_test
    public function pathology_test_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        $query = PathologyTest::with(['patient', 'category', 'unit']);

        // Date filter (applies to all queries)
        if (!empty($from_date) && !empty($to_date)) {
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('test_name', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%')
                    ->orWhere('test_type', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $pathologyTests = $query->orderByDesc('id')->paginate($perPage);

        return view('pathology.pathologytest', compact('pathologyTests'));
    }
    public function pathology_test_add()
    {
        $patients = User::role('Patient')->select('id', 'name')->get();
        $doctors = User::role('Doctor')->select('id', 'name')->get();

        $categories = PathologyCategory::all();
        $units = PathologyUnit::all();
        $parameters = PathologyParameter::with('unit')->get();

        // 1. Get Charge Type ID for 'Pathology'
        // Ensure 'ChargeType' model exists and name matches exactly 'Pathology' in DB
        $pathologyType = ChargeType::where('name', 'Pathology')->first();

        // 2. Filter Charge Categories based on that ID
        if ($pathologyType) {
            $charge_category = ChargeCategory::where('charge_type', $pathologyType->id)->get();
        } else {
            $charge_category = collect(); // Empty if Pathology type not found
        }

        return view('pathology.newpathologytest', compact(
            'patients',
            'categories',
            'units',
            'parameters',
            'charge_category',
            'doctors'
        ));
    }
    public function pathology_test_save(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [

            'test_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'test_type' => 'required|string|max:255',
            'category_id' => 'required|exists:pathology_categories,id',

            'sub_category' => 'nullable|string|max:255',
            'method' => 'nullable|string|max:255',
            'charge_category' => 'required|string|max:255',
            'standard_charge' => 'required|string|max:255',
            'report_days' => 'nullable|integer|min:0|max:365',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        // Save to DB
        $parameter = null;
        if ($request->has('parameter')) {
            $parameter = $request->parameter;
        }
        PathologyTest::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'test_name' => $request->test_name,
            'short_name' => $request->short_name,
            'test_type' => $request->test_type,
            'category_id' => $request->category_id,
            // 'unit_id'      => $request->unit_id,
            'sub_category' => $request->sub_category,
            'method' => $request->method,
            'report_days' => $request->report_days,
            'charge_category' => $request->charge_category,

            'standard_charge' => $request->standard_charge,
            'tax' => $request->tax ?? 0, // Optional tax field
            'parameter' => $parameter,

        ]);

        return redirect()->route('admin.pathology.test.index')
            ->with('success', 'Pathology test added successfully.');
    }



    // Show bill details

    public function pathology_bill_show($id)
    {
        $bill = PathologyBill::findOrFail($id);

        $tests = CollectionOfPathologyTest::where('bill_id', $bill->id)
            ->with('test')
            ->get();
        // dd($tests);

        $reports = PathologyReport::where('bill_id', $bill->id)->get()->keyBy('test_id');

        $staff = User::where('role', 'staff')->orWhere('role', 'admin')->get();

        return view('pathology.bill_detail', compact('bill', 'tests', 'staff', 'reports'));
    }


    public function pathology_test_edit($id)
    {
        $test = PathologyTest::findOrFail($id);
        $patients = User::role('Patient')->select('id', 'name', 'email')->get();
        $doctors = User::role('Doctor')->select('id', 'name')->get();

        $categories = PathologyCategory::all();
        $units = PathologyUnit::all();
        $parameters = PathologyParameter::with('unit')->get();

        // 1. Get Charge Type ID for 'Pathology'
        $pathologyType = ChargeType::where('name', 'Pathology')->first();

        // 2. Filter Charge Categories based on that ID
        if ($pathologyType) {
            $charge_category = ChargeCategory::where('charge_type', $pathologyType->id)->get();
        } else {
            $charge_category = collect();
        }

        // 3. Find the ID of the currently saved Charge Category Name (because DB stores Name, but Select needs ID)
        $currentCategoryId = null;
        if ($test->charge_category) {
            $foundCat = ChargeCategory::where('charge_category', $test->charge_category)->first();
            if ($foundCat) {
                $currentCategoryId = $foundCat->id;
            }
        }

        $existing_parameters = json_decode($test->parameter, true) ?? [];

        return view('pathology.editpathologytest', compact(
            'test',
            'patients',
            'doctors',
            'categories',
            'units',
            'parameters',
            'charge_category',
            'currentCategoryId', // Pass the calculated ID
            'existing_parameters'
        ));
    }

    public function pathology_test_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'test_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'test_type' => 'required|string|max:255',
            'category_id' => 'required|exists:pathology_categories,id',
            'sub_category' => 'nullable|string|max:255',
            'method' => 'nullable|string|max:255',
            'report_days' => 'nullable|integer|min:0|max:365',
            'charge_category' => 'required', // Validate ID is present
            'standard_charge' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        $test = PathologyTest::findOrFail($id);

        // 1. Fetch Charge Category Name from DB dynamically based on ID
        $chargeCatName = $test->charge_category; // Default to existing if not changed
        $chargeCatObj = ChargeCategory::find($request->charge_category);
        if ($chargeCatObj) {
            $chargeCatName = $chargeCatObj->charge_category;
        }

        $parameter = null;
        if ($request->has('parameter')) {
            $parameter = $request->parameter;
        }

        $test->update([
            'test_name' => $request->test_name,
            'short_name' => $request->short_name,
            'test_type' => $request->test_type,
            'category_id' => $request->category_id,
            'sub_category' => $request->sub_category,
            'method' => $request->method,
            'report_days' => $request->report_days,

            // Save the fetched name
            'charge_category' => $chargeCatName,

            'standard_charge' => $request->standard_charge,
            'tax' => $request->tax ?? 0,
            'parameter' => $parameter,
        ]);

        return redirect()->route('admin.pathology.test.index')
            ->with('success', 'Test updated successfully.');
    }
    public function pathology_report_detail(Request $request, $id, $bill_id)
    {
        $test = PathologyTest::with(['category', 'unit'])->findOrFail($id);
        // dd($test);
        $report = PathologyReport::where('bill_id', $bill_id)->where('test_id', $test->id)->first();

        $report_days = (int) $test->report_days;
        $expected_date = $test->created_at ? $test->created_at->addDays($report_days) : null;

        $parameters = json_decode($test->parameter, true) ?? [];
        $exit_parameters = [];

        if ($report) {
            $exit_parameters = ReportParameterValue::where('report_id', $report->id)->where('test_id', $test->id)
                ->with('parameter.unit')
                ->get()
                ->map(function ($param) {
                    return [
                        'id' => $param->parameter_id,
                        'name' => $param->parameter->name ?? 'N/A',
                        'unit' => $param->parameter->unit->name ?? '',
                        'report_value' => $param->value ?? '',
                        'reference_range' => $param->reference_range ?? '',
                    ];
                })
                ->toArray();
        } else {
            foreach ($parameters as $param) {
                $paramModel = PathologyParameter::with('unit')->find($param['parameter_id']);
                if ($paramModel) {
                    $exit_parameters[] = [
                        'id' => $paramModel->id,
                        'name' => $paramModel->name ?? 'N/A',
                        'unit' => $paramModel->unit->name ?? '',
                        'report_value' => '',
                        'reference_range' => $paramModel->reference_range ?? '',
                    ];
                }
            }
        }

        return response()->json([
            'test' => [
                'test_id' => $test->id,
                'test_name' => $test->test_name,
                'short_name' => $test->short_name,
                'test_type' => $test->test_type,
                'category' => $test->category->name ?? 'N/A',
                'unit' => $test->unit->name ?? 'N/A',
                'sub_category' => $test->sub_category,
                'method' => $test->method,
                'report_days' => $report_days,
                'standard_charge' => $test->standard_charge,
                'tax' => $test->tax ?? 0,
                'exit_parameters' => $exit_parameters,
                'created_at' => $test->created_at,
                'expected_date' => $expected_date ? $expected_date->format('m/d/Y') : 'N/A',
            ],
            'report' => $report ? [
                'approved_by' => $report->approved_by,
                'approve_date' => $report->approve_date ? Carbon::parse($report->approve_date)->format('Y-m-d') : null,
                'sample_collection_date' => $report->sample_collection_date ? Carbon::parse($report->sample_collection_date)->format('Y-m-d') : null,
                'collection_by' => $report->collection_by,
                'pathology_center' => $report->pathology_center,
                'result' => $report->result,
                'report_file' => $report->report_file,
            ] : null
        ]);
    }
    // public function pathology_test_update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [

    //         'test_name' => 'required|string|max:255',
    //         'short_name' => 'required|string|max:255',
    //         'test_type' => 'required|string|max:255',
    //         'category_id' => 'required|exists:pathology_categories,id',

    //         'sub_category' => 'nullable|string|max:255',
    //         'method' => 'nullable|string|max:255',
    //         'report_days' => 'nullable|integer|min:0|max:365',
    //         'charge_category' => 'required|string|max:255',
    //         'standard_charge' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->with('error', 'Validation errors occurred!')
    //             ->with('errorsop', $validator->errors())
    //             ->withInput();
    //     }

    //     $charge_category = ChargeCategory::all()->pluck('name', 'id');

    //     $test = PathologyTest::findOrFail($id);
    //     $parameter = null;
    //     if ($request->has('parameter')) {
    //         $parameter = $request->parameter;
    //     }
    //     $test->update(
    //         [

    //             'test_name' => $request->test_name,
    //             'short_name' => $request->short_name,
    //             'test_type' => $request->test_type,
    //             'category_id' => $request->category_id,
    //             // 'unit_id'      => $request->unit_id,
    //             'sub_category' => $request->sub_category,
    //             'method' => $request->method,
    //             'report_days' => $request->report_days,
    //             'charge_category' => $request->charge_category ? $charge_category[$request->charge_category] : null,
    //             'standard_charge' => $request->standard_charge,
    //             'tax' => $request->tax ?? 0, // Optional tax field
    //             'parameter' => $parameter,
    //         ]
    //     );

    //     return redirect()->route('admin.pathology.test.index')
    //         ->with('success', 'Test updated successfully.');
    // }


    public function pathology_test_delete($id)
    {
        $test = PathologyTest::findOrFail($id);
        $test->delete();

        return redirect()->back()->with('success', 'Test deleted successfully.');
    }

    public function pathology_bill(Request $request, $id)
    {
        $invoice = 'PB' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $bill = PathologyTest::with(['patient', 'doctor', 'category', 'unit'])
            ->findOrFail($id);

        // Decode the parameters JSON
        $parameters = [];
        if ($bill->parameter) {
            $parameters = json_decode($bill->parameter, true);
            // Load parameter details if needed
            foreach ($parameters as &$param) {
                if (isset($param['parameter_id'])) {
                    $param['details'] = PathologyParameter::find($param['parameter_id']);
                }
            }
        }

        // Calculate financial values
        $subtotal = $bill->standard_charge ?? 0;
        $taxPercent = $bill->tax ?? 0; // Set your tax percentage here if needed
        $taxAmount = ($taxPercent ?? 0); // Assuming tax is a percentage
        $totalAmount = $subtotal + $taxAmount;

        $pdf = Pdf::loadView('pathology.billing-pdf', [
            'invoice' => $invoice,
            'bill' => $bill,
            'parameters' => $parameters,
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'report_delivery_time' => $bill->report_days ? $bill->report_days . ' hours' : '24 hours'
        ]);

        return $pdf->stream("pathology-bill-{$invoice}.pdf");
    }
    public function pathology_bill_index()
    {
        $perPage = request()->input('perPage', 10);

        // Get inputs
        $search = request()->input('search', '');
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');
        $payment_status = request()->input('payment_status'); // NEW Input

        $query = PathologyBill::with(['patient', 'doctor']);

        // 1. Date filter
        if (!empty($from_date) && !empty($to_date)) {
            $query->whereBetween('bill_date', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }

        // 2. Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_no', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    });
            });
        }

        // 3. Payment Status Filter (NEW Logic)
        if (!empty($payment_status)) {
            if ($payment_status == 'paid') {
                // अगर due_amount 0 या उससे कम है तो Paid माना जाएगा
                $query->where('due_amount', '<=', 0);
            } elseif ($payment_status == 'due') {
                // अगर due_amount 0 से ज्यादा है तो Due माना जाएगा
                $query->where('due_amount', '>', 0);
            }
        }

        $bills = $query->orderByDesc('id')->paginate($perPage);

        // Pass all filter variables to view for maintaining state
        return view('pathology.generate_bill', compact(
            'bills',
            'search',
            'from_date',
            'to_date',
            'payment_status' // Pass back to view to keep dropdown selected
        ));
    }
    public function pathology_bill_print($id)
    {
        $bill = PathologyBill::with(['patient', 'doctor'])->findOrFail($id);

        // Fetch saved tests with their historical prices
        $tests = CollectionOfPathologyTest::where('bill_id', $bill->id)
            ->with('test') // Load test details for name/code
            ->get();

        $uhid = DB::table("patients")->where('user_id', $bill->patient_id)->value('patient_unique_id');

        // $pdf = Pdf::loadView('pathology.billing-pdf', [
        //     'bill' => $bill,
        //     'tests' => $tests,
        // ]);

        return view('pathology.billing-pdf', compact('bill', 'tests', 'uhid'));
    }
    public function pathology_bill_add()
    {
        $patients = User::role('Patient')->select('id', 'name', 'email')->orderBy('id', 'desc')->get();
        // dd($patients);
        $doctors = User::role('Doctor')->select('id', 'name')->get();
        $test_names = PathologyTest::all();
        $categories = PathologyCategory::all();
        $charge_categories = ChargeCategory::all();
        $parameters = PathologyParameter::all();
        return view('pathology.newpathologybill', compact(
            'patients',
            'doctors',
            'test_names',
            'categories',
            'charge_categories',
            'parameters',
        ));
    }

    public function pathology_bill_edit($id)
    {
        $bill = PathologyBill::with(['patient'])->findOrFail($id);

        // Dropdowns ke liye data
        $patients = User::role('Patient')->select('id', 'name')->get();
        $doctors = User::role('Doctor')->select('id', 'name')->get();

        // Tests for Dropdown
        $test_names = PathologyTest::select('id', 'test_name', 'standard_charge', 'report_days', 'tax')->get();

        // Pehle se saved Tests fetch karein
        $selectedTests = CollectionOfPathologyTest::where('bill_id', $id)->get();

        return view('pathology.editpathologybill', compact('bill', 'patients', 'doctors', 'test_names', 'selectedTests'));
    }

    public function pathology_bill_update(Request $request, $id)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'payment_mode_id' => 'required|string',
            'amount' => 'required|numeric|min:0',      // New Total Bill
            'paid_amount' => 'required|numeric|min:0', // Edited Paid Amount (Cumulative)
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'tests' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bill = PathologyBill::findOrFail($id);

        // *** STEP 1: Store Previous Paid Amount (To calculate difference) ***
        $previous_paid_amount = $bill->paid_amount;

        // --- Recalculate Payment Status ---
        $new_total = $request->amount;
        $new_paid = $request->paid_amount;
        $new_due = $new_total - $new_paid;

        // Due negative nahi hona chahiye
        if ($new_due < 0)
            $new_due = 0;

        // Status decide karein
        $status = 'unpaid';
        if ($new_due <= 0) {
            $status = 'paid';
        } elseif ($new_paid > 0) {
            $status = 'partial';
        }

        // 2. Update Bill Details (Main Table)
        $bill->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'payment_mode_id' => $request->payment_mode_id, // Current mode update
            'amount' => $new_total,
            'paid_amount' => $new_paid,
            'due_amount' => $new_due,
            'payment_status' => $status,
            'tax' => $request->tax ?? 0,
            'discount' => $request->discount ?? 0,
            'notes' => $request->notes,
            'bill_date' => $request->bill_date ? \Carbon\Carbon::parse($request->bill_date) : $bill->bill_date,
        ]);

        // *** STEP 3: Handle New Payment Logic (Transaction & Event) ***
        // Agar naya paid amount purane se jyada hai, iska matlab abhi payment hui hai
        if ($new_paid > $previous_paid_amount) {

            $difference = $new_paid - $previous_paid_amount; // Kitna extra pay kiya

            // A. Naya Transaction Record Banayein (History ke liye)
            PathologyPayment::create([
                'bill_id' => $bill->id,
                'ipd_patient_id' => $bill->ipd_patient_id, // FIXED: Link to IPD
                'amount' => $difference, // Sirf current transaction ka amount
                'payment_mode' => $request->payment_mode_id,
                'payment_date' => $request->bill_date ? \Carbon\Carbon::parse($request->bill_date) : now(),
            ]);

            // B. Fire Payment Event
            // Hum bill ka clone banayenge aur amount override karenge
            // taaki event me sirf 'difference' amount register ho, total nahi.
            $paymentData = $bill->replicate();
            $paymentData->id = $bill->id; // Replicate ID hata deta hai, wapas lagayein
            $paymentData->amount = $difference; // Important: Set amount to the transaction value
            $paymentData->payment_method = $request->payment_mode_id;

            event(new PaymentCollectionEvent($paymentData, 'Pathology Bill Payment', 4));
        }

        // 4. Sync Tests (Delete Old & Create New)
        CollectionOfPathologyTest::where('bill_id', $id)->delete();

        if ($request->has('tests')) {
            foreach ($request->tests as $item) {
                CollectionOfPathologyTest::create([
                    'bill_id' => $bill->id,
                    'test_id' => $item['test_id'],
                    'amount' => $item['amount'],
                    'tax' => $item['tax'] ?? 0
                ]);
            }
        }

        return redirect()->route('admin.pathology.bill.index')
            ->with('success', 'Bill updated successfully. New Due: ₹' . number_format($new_due, 2));
    }
    
    // public function pathology_bill_save(Request $request)
    // {

    //     // dd($request->all());
    //     // 1. Validation
    //     $validator = Validator::make($request->all(), [
    //         'patient_id' => 'required|exists:users,id',
    //         'doctor_id' => 'nullable|exists:users,id',
    //         'payment_mode_id' => 'required|string|max:255',
    //         'amount' => 'required|numeric|min:0',      // Total Bill Amount
    //         'paid_amount' => 'required|numeric|min:0', // NEW: Customer ne kitna pay kiya
    //         'tax' => 'nullable|numeric|min:0',
    //         'discount' => 'nullable|numeric|min:0',
    //         'notes' => 'nullable|string|max:1000',

    //         'tests' => 'required|array',
    //         'tests.*.test_id' => 'required|exists:pathology_tests,id',
    //         'tests.*.amount' => 'required|numeric|min:0',
    //         'tests.*.tax' => 'nullable|numeric|min:0',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->with('error', 'Validation errors occurred!')
    //             ->with('errorsop', $validator->errors())
    //             ->withInput();
    //     }

    //     // 2. Logic for Due Amount & Payment Status
    //     $total_bill = $request->amount;
    //     $paid_now = $request->paid_amount;
    //     $due_amount = $total_bill - $paid_now;

    //     // Negative Due handle karne ke liye (agar user galti se jyada pay daal de)
    //     if ($due_amount < 0)
    //         $due_amount = 0;

    //     $payment_status = 'unpaid';
    //     if ($due_amount <= 0) {
    //         $payment_status = 'paid';
    //     } elseif ($paid_now > 0) {
    //         $payment_status = 'partial';
    //     }

    //     // 3. Save Bill
    //     $bill_no = 'PB' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

    //     $bill = PathologyBill::create([
    //         'bill_no' => $bill_no,
    //         'patient_id' => $request->patient_id,
    //         'doctor_id' => $request->doctor_id,
    //         'payment_mode_id' => $request->payment_mode_id,
    //         'payment_method' => $request->payment_mode_id,

    //         'amount' => $total_bill,       // Total Bill Value
    //         'paid_amount' => $paid_now,    // New Column: Paid Now
    //         'due_amount' => $due_amount,   // New Column: Balance Due
    //         'payment_status' => $payment_status, // New Column: Status

    //         'tax' => $request->tax ?? 0,
    //         'discount' => $request->discount ?? 0,
    //         'notes' => $request->notes,
    //         'bill_date' => now(),
    //     ]);

    //     // 4. Handle Payment Event (ONLY for the amount actually paid)
    //     // Hum bill object ka clone banayenge aur usme 'amount' ko replace karenge 'paid_amount' se
    //     // taaki accounting/dashboard me sirf received cash hi jude.

    //     if ($paid_now > 0) {
    //         $paymentData = $bill->replicate();
    //         $paymentData->id = $bill->id; // Replicate ID hata deta hai, wapas daalo
    //         $paymentData->amount = $paid_now; // Override Total with Paid amount for the log

    //         event(new PaymentCollectionEvent($paymentData, 'Pathology Bill Payment', 4));

    //         PathologyPayment::create([
    //             'bill_id' => $bill->id,
    //             'amount' => $paid_now,
    //             'payment_mode' => $request->payment_mode_id,
    //             'payment_date' => now(),
    //         ]);
    //     }

    //     // 5. Save Tests (Snapshot of Prices)
    //     if ($request->has('tests')) {
    //         foreach ($request->tests as $item) {
    //             CollectionOfPathologyTest::create([
    //                 'bill_id' => $bill->id,
    //                 'test_id' => $item['test_id'],
    //                 'amount' => $item['amount'], // Snapshot Price
    //                 'tax' => $item['tax'] ?? 0
    //             ]);
    //         }
    //     }

    //     return redirect()->route('admin.pathology.bill.index')
    //         ->with('success', 'Pathology bill created successfully.');
    // }


   public function pathology_bill_save(Request $request)
{
    // 1. Validation
    $validator = Validator::make($request->all(), [
        'patient_id' => 'required|exists:users,id',
        'doctor_id' => 'nullable|exists:users,id',
        'payment_mode_id' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',      // Total Bill Amount
        'paid_amount' => 'required|numeric|min:0', // Paid Now
        'tax' => 'nullable|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string|max:1000',
        'tests' => 'required|array',
        'tests.*.test_id' => 'required|exists:pathology_tests,id',
        'tests.*.amount' => 'required|numeric|min:0',
        'tests.*.tax' => 'nullable|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->with('error', 'Validation errors occurred!')
            ->with('errorsop', $validator->errors())
            ->withInput();
    }

    DB::beginTransaction(); // <--- Transaction Start

    try {
        // 2. Logic for Due Amount & Payment Status
        $total_bill = $request->amount;
        $paid_now = $request->paid_amount;
        $due_amount = $total_bill - $paid_now;

        $payment_status = 'unpaid';
        if ($due_amount <= 0.01) { 
            $payment_status = 'paid';
        } elseif ($paid_now > 0) {
            $payment_status = 'partial';
        }

        // --- IPD Logic Start ---
        $ipdPatientId = null;
        if (class_exists('App\Models\IpdPatient')) {
            $activeIpd = IpdPatient::where('patient_id', $request->patient_id)
                ->whereNull('discharge_date')
                ->latest()
                ->first();

            if ($activeIpd) {
                $ipdPatientId = $activeIpd->id;
            }
        }
        // --- IPD Logic End ---

        // 3. Bill Number Generation (DUPLICATE FIX)
        $year = date('Y');
        $prefix = 'CBPB' . $year; // Prefix fix kar diya (CBPB)

        // Last bill fetch karein same prefix wala
        $lastBill = PathologyBill::where('bill_no', 'like', '%PB' . $year . '%')
            ->orderBy('id', 'desc')
            ->first();

        // Initial Sequence Setup
        $nextSequence = 1;
        if ($lastBill) {
            // Last 5 digits nikalein
            $lastSequence = intval(substr($lastBill->bill_no, -5));
            $nextSequence = $lastSequence + 1;
        }

        // --- UNIQUE CHECK LOOP (Fix for Duplicate Entry) ---
        $bill_no = '';
        do {
            // Generate Number: THPB202600001
            $bill_no = $prefix . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
            
            // Check Database
            $exists = PathologyBill::where('bill_no', $bill_no)->exists();
            
            if ($exists) {
                $nextSequence++; // Agar duplicate hai to +1 karo aur loop repeat karo
            }
        } while ($exists);
        // ---------------------------------------------------

        // 4. Save Main Bill
        $bill = PathologyBill::create([
            'bill_no' => $bill_no, // Unique Number
            'patient_id' => $request->patient_id,
            'ipd_patient_id' => $ipdPatientId,
            'doctor_id' => $request->doctor_id,
            'payment_mode_id' => $request->payment_mode_id,
            'payment_method' => $request->payment_mode_id,
            'amount' => $total_bill,
            'paid_amount' => $paid_now,
            'due_amount' => $due_amount,
            'payment_status' => $payment_status,
            'tax' => $request->tax ?? 0,
            'discount' => $request->discount ?? 0,
            'notes' => $request->notes,
            'bill_date' => $request->bill_date ? \Carbon\Carbon::parse($request->bill_date) : now(),
        ]);

        // 5. Handle Payment Event
        if ($paid_now > 0) {
            PathologyPayment::create([
                'bill_id' => $bill->id,
                'ipd_patient_id' => $ipdPatientId, // FIXED: Link to IPD
                'amount' => $paid_now,
                'payment_mode' => $request->payment_mode_id,
                'payment_date' => $request->bill_date ? \Carbon\Carbon::parse($request->bill_date) : now(),
            ]);

            // Fire Event
            $paymentData = $bill->replicate();
            $paymentData->id = $bill->id;
            $paymentData->amount = $paid_now;
            event(new PaymentCollectionEvent($paymentData, 'Pathology Bill Payment', 4));
        }

        // 6. Save Test Items
        if ($request->has('tests')) {
            foreach ($request->tests as $item) {
                CollectionOfPathologyTest::create([
                    'bill_id' => $bill->id,
                    'test_id' => $item['test_id'],
                    'amount' => $item['amount'],
                    'tax' => $item['tax'] ?? 0
                ]);
            }
        }

        DB::commit(); // <--- Save Changes

        return redirect()->route('admin.pathology.bill.index')
            ->with('success', 'Pathology bill created successfully.');

    } catch (\Exception $e) {
        DB::rollBack(); // <--- Undo Changes on Error
        
        return redirect()->back()
            ->with('error', 'Error creating bill: ' . $e->getMessage())
            ->withInput();
    }
}


    public function getPaymentDetails($id)
    {
        $bill = PathologyBill::with('patient')->findOrFail($id);

        // Fetch Transactions
        $transactions = PathologyPayment::where('bill_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'bill' => $bill,
            'transactions' => $transactions
        ]);
    }

    // 2. Add Partial Payment Logic (Update)
    public function add_payment(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|exists:pathology_bills,id',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $currentBill = PathologyBill::findOrFail($request->bill_id);
            $amountToDistribute = $request->amount;

            // 1. सबसे पहले CURRENT BILL को पे करें
            $currentDue = $currentBill->amount - $currentBill->paid_amount;

            // अगर डिस्काउंट मोड है, तो लॉजिक अलग होगा
            if ($request->payment_mode == 'Discount') {
                $currentBill->increment('discount', $amountToDistribute);
                $currentBill->decrement('amount', $amountToDistribute);
                $currentBill->decrement('due_amount', $amountToDistribute);

                // Discount में एक्स्ट्रा पैसा बचने का सवाल नहीं होता, वो सिर्फ इसी बिल के लिए होता है
                $amountToDistribute = 0;
            } else {
                // Normal Payment Logic

                // इस बिल के लिए कितना पैसा चाहिए?
                $payForCurrent = min($amountToDistribute, $currentDue);

                // पे करें
                $currentBill->increment('paid_amount', $payForCurrent);
                $currentBill->decrement('due_amount', $payForCurrent);

                // ट्रांजेक्शन रिकॉर्ड करें
                PathologyPayment::create([
                    'bill_id' => $currentBill->id,
                    'amount' => $payForCurrent,
                    'payment_mode' => $request->payment_mode,
                    'payment_date' => now(),
                    'note' => 'Payment Received'
                ]);

                // बचा हुआ पैसा (Excess)
                $amountToDistribute -= $payForCurrent;
            }

            // स्टेटस अपडेट
            $this->updateBillStatus($currentBill);

            // 2. FIFO SETTLEMENT (अगर IPD Patient है और पैसा बचा है)
            if ($amountToDistribute > 0 && $currentBill->ipd_patient_id) {

                // दूसरे पेंडिंग बिल निकालें (Oldest first)
                $otherBills = PathologyBill::where('ipd_patient_id', $currentBill->ipd_patient_id)
                    ->where('id', '!=', $currentBill->id) // Current bill छोड़ दें
                    ->where('payment_status', '!=', 'paid')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($otherBills as $otherBill) {
                    if ($amountToDistribute <= 0)
                        break;

                    $otherDue = $otherBill->amount - $otherBill->paid_amount;
                    if ($otherDue <= 0)
                        continue;

                    // कितना पे करें?
                    $payForOther = min($amountToDistribute, $otherDue);

                    // पे करें
                    $otherBill->increment('paid_amount', $payForOther);
                    $otherBill->decrement('due_amount', $payForOther);
                    $this->updateBillStatus($otherBill);

                    // ट्रांजेक्शन रिकॉर्ड
                    PathologyPayment::create([
                        'bill_id' => $otherBill->id,
                        'amount' => $payForOther,
                        'payment_mode' => $request->payment_mode, // सेम मोड
                        'payment_date' => now(),
                        'note' => 'Settled from excess payment of Bill #' . $currentBill->bill_no
                    ]);

                    $amountToDistribute -= $payForOther;
                }
            }

            // 3. अगर अब भी पैसा बचा है (ADVANCE)
            // इसे हम IPD Ledger (Payment Table) में 'Advance' के तौर पर डाल सकते हैं 
            // या आखिरी बिल में Negative Due (Advance) दिखा सकते हैं।
            // अभी के लिए हम इसे CURRENT BILL में Advance दिखा रहे हैं जैसा आपका पिछला लॉजिक था।

            if ($amountToDistribute > 0) {
                // बचे हुए पैसे को करंट बिल में ही डाल दो (Advance दिखाने के लिए)
                $currentBill->increment('paid_amount', $amountToDistribute);
                $currentBill->decrement('due_amount', $amountToDistribute); // ये Negative हो जाएगा
                $currentBill->payment_status = 'paid'; // Advance means paid
                $currentBill->save();

                // इसकी एंट्री भी कर दो ताकि हिसाब मिले
                PathologyPayment::create([
                    'bill_id' => $currentBill->id,
                    'amount' => $amountToDistribute,
                    'payment_mode' => $request->payment_mode,
                    'payment_date' => now(),
                    'note' => 'Advance Amount (Excess Payment)'
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment Added & Settled Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // हेल्पर फंक्शन स्टेटस अपडेट के लिए
    private function updateBillStatus($bill)
    {
        if ($bill->due_amount <= 0.01) {
            $bill->payment_status = 'paid';
        } else {
            $bill->payment_status = 'partial';
        }
        $bill->save();
    }

    // 3. Print Specific Transaction Receipt
    public function print_transaction_receipt($id)
    {
        // Sirf us specific transaction ko fetch karein
        $transaction = PathologyPayment::with('bill.patient')->findOrFail($id);
        $uhid = DB::table("patients")->where('user_id', $transaction->bill->patient_id)->value('patient_unique_id');

        // $pdf = Pdf::loadView('pathology.payment_receipt', compact('transaction'));
        return view('pathology.payment_receipt', compact('transaction', 'uhid'));
    }
    public function pathology_bill_delete($id)
    {
        DB::beginTransaction();
        try {
            $bill = PathologyBill::findOrFail($id);

            // A. If paid via IPD Adjustment, we should technically refund the IPD ledger
            // Check transactions for 'IPD Adjustment' mode and reverse them in IPD Payment table.
            // (Skipping for now to keep it simple as per request, but good to know).

            // B. Delete Children
            PathologyPayment::where('bill_id', $id)->delete();
            CollectionOfPathologyTest::where('bill_id', $id)->delete();
            PathologyReport::where('bill_id', $id)->delete();

            // C. Delete Bill
            $bill->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Bill deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function pathology_report_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        $query = PathologyReport::with(['bill.patient', 'bill.doctor']);

        // Date filter (applies to all queries)
        if (!empty($from_date) && !empty($to_date)) {
            $query->whereBetween('created_at', [$from_date, $to_date]);
        }

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('report_no', 'like', '%' . $search . '%')
                    ->orWhereHas('bill.patient', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $reports = $query->orderByDesc('id')->paginate($perPage);

        return view('pathology.pathologyreport', compact('reports'));
    }
    public function pathology_report_add()
    {
        $bills = PathologyBill::with(['patient', 'doctor'])->get();
        return view('pathology.newpathologyreport', compact('bills'));
    }

    public function pathology_report_save(Request $request)
{
    // 1. Validation
    $validator = Validator::make($request->all(), [
        'test_id' => 'required|exists:pathology_tests,id',
        'bill_id' => 'required|exists:pathology_bills,id',
        'approved_by' => 'nullable|exists:users,id',
        'result' => 'nullable|string|max:1000',
        'upload_result' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'parameter_id' => 'array',
        'report_value' => 'array',
        'reference_range' => 'array',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->with('error', 'Validation errors occurred!')
            ->withErrors($validator)
            ->withInput();
    }

    // 2. File Upload Logic
    $filePath = null;
    if ($request->hasFile('upload_result')) {
        $file = $request->file('upload_result');
        $filePath = $file->store('reports', 'public');
    }

    // 3. Create or Update Main Report
    // We match based on test_id AND bill_id to ensure uniqueness
    $report = PathologyReport::where('test_id', $request->test_id)
        ->where('bill_id', $request->bill_id)
        ->first();

    if (!$report) {
        $year = date('Y');
        $prefix = setting('pathology_report_prefix', 'PATH-REP-') . $year;

        $lastReport = PathologyReport::where('report_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReport) {
            $lastSequence = intval(substr($lastReport->report_no, -5));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        $report_no = '';
        do {
            $report_no = $prefix . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
            $exists = PathologyReport::where('report_no', $report_no)->exists();
            if ($exists) {
                $nextSequence++;
            }
        } while ($exists);
    } else {
        $report_no = $report->report_no;
    }

    $report = PathologyReport::updateOrCreate(
        [
            'test_id' => $request->test_id,
            'bill_id' => $request->bill_id,
        ],
        [
            'report_no' => $report_no,
            'approve_date' => $request->approve_date ?? now(),
            'approved_by' => $request->approved_by ?? auth()->id(),
            'pathology_center' => $request->pathology_center,
            'result' => $request->result ?? '',
            // Only update file path if a new file is uploaded
            'upload_result' => $filePath ? $filePath : DB::raw('upload_result'),
        ]
    );

    // 4. Save Parameters (THE FIX IS HERE)
    if ($request->has('parameter_id') && $request->has('report_value')) {
        
        foreach ($request->parameter_id as $index => $parameterId) {
            
            // 'firstOrNew' use karein. Ye check karega agar record hai to use layega,
            // nahi to naya instance create karega (NULL nahi return karega).
            $reportParameter = ReportParameterValue::firstOrNew([
                'report_id' => $report->id,
                'parameter_id' => $parameterId
            ]);

            // Ab properties assign karein (Ab ye error nahi dega)
            $reportParameter->test_id = $request->test_id;
            $reportParameter->bill_id = $request->bill_id; // Save Bill ID
            
            $reportParameter->value = $request->report_value[$index] ?? null;
            $reportParameter->unit = $request->unit[$index] ?? null;
            $reportParameter->reference_range = $request->reference_range[$index] ?? null;
            
            $reportParameter->save();
        }
    }

    return redirect()->back()->with('success', 'Report saved successfully.');
}


    public function pathology_report_edit($id)
    {
        $report = PathologyReport::findOrFail($id);
        $bills = PathologyBill::with(['patient', 'doctor'])->get();
        return view('pathology.editpathologyreport', compact('report', 'bills'));
    }
    public function pathology_report_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bill_id' => 'required|exists:pathology_bills,id',
            'Approved_by' => 'required|exists:users,id',
            'result' => 'required|string|max:1000',
            'upload_result' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        $report = PathologyReport::findOrFail($id);
        $report->fill($request->except('upload_result'));
        if ($request->hasFile('upload_result')) {
            $report->upload_result = $request->file('upload_result')->store('pathology_reports');
        }
        $report->save();

        return redirect()->route('admin.pathology.report.index')->with('success', 'Report updated successfully.');
    }
    public function pathology_report_delete($id)
    {
        $report = PathologyReport::findOrFail($id);
        $report->delete();

        return redirect()->back()->with('success', 'Report deleted successfully.');
    }


    public function pathology_report_download($id)
    {
        // dd($id);
        // $id is now the REPORT ID, specific to one test in one bill
        $report = PathologyReport::findOrFail($id);

        $bill = PathologyBill::with(['patient', 'doctor'])
            ->findOrFail($report->bill_id);

        $test = PathologyTest::with(['category', 'unit'])
            ->findOrFail($report->test_id);

        $invoice = $report->report_no ?? 'REP-' . $report->id;

        // Fetch parameters specifically for this report ID and test ID
        $parameters = ReportParameterValue::where('report_id', $report->id)
            ->where('test_id', $test->id)
            ->with('parameter.unit')
            ->get()
            ->map(function ($param) {
                return [
                    'name' => $param->parameter->name ?? 'Unknown',
                    'value' => $param->value,
                    'unit' => $param->unit ?? ($param->parameter->unit->name ?? ''),
                    'reference_range' => $param->reference_range,
                ];
            });

        $category = PathologyCategory::find($test->category_id);

        $pdf = Pdf::loadView('pathology.report', [
            'invoice' => $invoice,
            'bill' => $bill,
            'tests' => $test, // Singular test object
            'parameters' => $parameters,
            'report' => $report,
            'category' => $category,
            'report_delivery_time' => $test->report_days ? $test->report_days . ' days' : 'Same Day'
        ]);

        return $pdf->stream("pathology-report-{$invoice}.pdf");
    }


    public function settleFromIpdAdvance(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|exists:pathology_bills,id',
            'amount' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $bill = PathologyBill::findOrFail($request->bill_id);

            // Check: Sirf IPD link wale bill par hi ye action allow karein
            if (!$bill->ipd_patient_id) {
                return response()->json(['status' => 0, 'message' => 'This bill is not linked to an IPD Patient.']);
            }

            // 1. Pathology Payment Record Create karein
            // Hum mode ko 'IPD Advance' set kar rahe hain taaki report me pata chale paisa kaise aaya
            PathologyPayment::create([
                'bill_id' => $bill->id,
                'amount' => $request->amount,
                'payment_mode' => 'IPD Advance', // Mode updated for clarity
                'payment_date' => now(),
                'note' => 'Settled internally via IPD Advance'
            ]);

            // 2. Bill Amount Update karein
            $bill->increment('paid_amount', $request->amount);

            // Recalculate Due (Allowing Negative for Advance)
            $bill->due_amount = $bill->amount - $bill->paid_amount;

            // 3. Status Update
            if ($bill->due_amount <= 0.01) {
                $bill->payment_status = 'paid';
            } else {
                $bill->payment_status = 'partial';
            }

            $bill->save();

            // 4. NOTE: Humne IPD Payment table me deduction NAHI kiya hai (User Requirement)

            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Bill Settled Successfully in Pathology!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // 5. ACCOUNT SETTLEMENT (FIFO - Bulk Payment)
    public function save_account_payment(Request $request)
    {
        $request->validate([
            'ipd_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $remainingAmount = $request->amount;
            $totalCollected = 0;

            // 1. पहले वो बिल ढूंढो जिनमे पैसा बाकी है (Oldest First)
            // हम यहाँ सिर्फ POSITIVE DUE वाले बिल लेंगे
            $unpaidBills = PathologyBill::where('ipd_patient_id', $request->ipd_id)
                ->where('due_amount', '>', 0)
                ->orderBy('id', 'asc')
                ->get();

            foreach ($unpaidBills as $bill) {
                if ($remainingAmount <= 0.01)
                    break;

                $currentDue = $bill->due_amount; // already calculated in DB

                // कितना पे करना है? (या तो पूरा Due, या जितना पैसा हाथ में है)
                $amountToPay = min($remainingAmount, $currentDue);

                // एंट्री बनाओ
                PathologyPayment::create([
                    'bill_id' => $bill->id,
                    'ipd_patient_id' => $request->ipd_id,
                    'amount' => $amountToPay,
                    'payment_mode' => $request->payment_mode,
                    'payment_date' => now(),
                    'note' => 'Bill Settlement (Bulk)'
                ]);

                // बिल अपडेट करो
                $bill->increment('paid_amount', $amountToPay);
                $bill->decrement('due_amount', $amountToPay);

                if ($bill->due_amount <= 0.01) {
                    $bill->payment_status = 'paid';
                } else {
                    $bill->payment_status = 'partial';
                }
                $bill->save();

                $remainingAmount -= $amountToPay;
                $totalCollected += $amountToPay;
            }

            // 2. [IMPORTANT] अगर अभी भी पैसा बचा है (ADVANCE)
            // तो इसे मरीज के 'सबसे आखिरी (Latest)' बिल में जोड़ दो
            if ($remainingAmount > 0) {

                // सबसे नया बिल ढूंढो
                $latestBill = PathologyBill::where('ipd_patient_id', $request->ipd_id)
                    ->orderBy('id', 'desc') // Newest First
                    ->first();

                if ($latestBill) {
                    // Latest Bill में पैसा जोड़ो (चाहे वो पहले से Advance में क्यों न हो)
                    $latestBill->increment('paid_amount', $remainingAmount);

                    // Due Amount को और कम करो (Negative me jayega = Advance Badhega)
                    $latestBill->due_amount = $latestBill->amount - $latestBill->paid_amount;

                    $latestBill->payment_status = 'paid'; // Advance means paid
                    $latestBill->save();

                    // इसकी पेमेंट एंट्री भी बनाओ (Bill ID ke sath)
                    PathologyPayment::create([
                        'bill_id' => $latestBill->id,
                        'ipd_patient_id' => $request->ipd_id,
                        'amount' => $remainingAmount,
                        'payment_mode' => $request->payment_mode,
                        'payment_date' => now(),
                        'note' => 'Advance Payment (Added to Latest Bill)'
                    ]);

                    $totalCollected += $remainingAmount;
                } else {
                    // अगर मरीज का एक भी बिल नहीं बना है, तो मजबूरी में इसे 
                    // IPD Main Ledger (Payment Model) में डालना पड़ेगा
                    if (class_exists('App\Models\Payment')) {
                        \App\Models\Payment::create([
                            'ipd_patient_id' => $request->ipd_id,
                            'amount' => $remainingAmount,
                            'payment_mode' => $request->payment_mode,
                            'date' => date('Y-m-d'),
                            'note' => 'Advance Deposit (No Pathology Bill Found)'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment of ₹' . number_format($totalCollected, 2) . ' recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // --- IPD PATHOLOGY DASHBOARD ---
    public function ipd_pathology_profile($ipd_id)
    {
        // IPD Patient ढूंढे और उसके पैथोलॉजी के रिलेशन्स लोड करें
        // ध्यान दें: PathologyBill और PathologyPayment का रिलेशन IpdPatient मॉडल में होना चाहिए
        // अगर नहीं है, तो हम मैनुअली क्वेरी करेंगे।

        $ipdPatient = IpdPatient::with('patient', 'doctor')->findOrFail($ipd_id);

        // 1. सारे बिल
        $bills = PathologyBill::where('ipd_patient_id', $ipd_id)->orderBy('created_at', 'desc')->get();

        // 2. सारी पेमेंट्स
        // हम PathologyPayment में ipd_patient_id नहीं स्टोर कर रहे थे, इसलिए हम Bills के थ्रू निकालेंगे
        $billIds = $bills->pluck('id');
        $payments = PathologyPayment::whereIn('bill_id', $billIds)->orderBy('payment_date', 'desc')->get();

        // 3. Totals
        $total_bill_amount = $bills->sum('amount');
        $total_paid = $bills->sum('paid_amount');

        // Balance (Total Bill - Total Paid)
        // Positive = Due, Negative = Advance
        $net_balance = $total_bill_amount - $total_paid;

        return view('pathology.ipd_profile', compact('ipdPatient', 'bills', 'payments', 'total_bill_amount', 'total_paid', 'net_balance'));
    }


    // --- IPD CONSOLIDATED BILL (STATEMENT) ---
    public function print_consolidated_bill($ipd_id)
    {
        $ipdPatient = IpdPatient::with(['patient', 'doctor'])->findOrFail($ipd_id);

        // Fetch all bills for this IPD admission
        $bills = PathologyBill::where('ipd_patient_id', $ipd_id)
            ->with(['tests.test']) // Eager load tests
            ->orderBy('created_at', 'asc')
            ->get();

        // Fetch all payments (Linked via bill or direct IPD payment)
        // Note: Agar aapne migration chala di hai to 'ipd_patient_id' payments me hoga
        $payments = PathologyPayment::where('ipd_patient_id', $ipd_id)
            ->orWhereIn('bill_id', $bills->pluck('id'))
            ->orderBy('payment_date', 'asc')
            ->get();

        // Calculations
        $totalBill = $bills->sum('amount');
        $totalPaid = $payments->sum('amount');
        $balance = $totalBill - $totalPaid;

        // View return karein (PDF ke liye)
        return view('pathology.print_consolidated', compact('ipdPatient', 'bills', 'payments', 'totalBill', 'totalPaid', 'balance'));
    }


    public function ajax_ipd_search(Request $request)
    {
        $search = $request->term; // Select2 sends search term as 'term'

        $patients = IpdPatient::with('patient')
            ->where(function ($q) use ($search) {
                $q->where('ipd_no', 'LIKE', "%$search%") // Search by IPD No
                    ->orWhereHas('patient', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%$search%") // Search by Name
                            ->orWhere('phone', 'LIKE', "%$search%"); // Search by Phone
                    });
            })
            ->whereNull('discharge_date') // Optional: Sirf admitted patients dikhane ke liye
            ->limit(20)
            ->get();

        $results = [];
        foreach ($patients as $p) {
            $results[] = [
                'id' => $p->id, // Is ID par redirect karenge
                'text' => $p->patient->name . ' (' . $p->ipd_no . ') - ' . ($p->patient->phone ?? 'No Phone')
            ];
        }

        return response()->json(['results' => $results]);
    }
}
