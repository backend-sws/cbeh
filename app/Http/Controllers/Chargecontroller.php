<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\AddCharge;
use App\Models\ChargeType;
use Illuminate\Http\Request;
use App\Models\PathologyTest;
use App\Models\ChargeCategory;
use App\Models\HospitalCharge;
use App\Services\HospitalChargeImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChargeController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'patient_id' => 'required|integer',
            'ipd_no' => 'required',
            'charge_type' => 'required|string',
            'charge_category' => 'nullable|string',
            'code' => 'nullable|string',
            'standard_charge' => 'required|numeric',
            'applied_charge' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',
        ]);



        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        AddCharge::create($request->only([
            'date',
            'patient_id',
            'ipd_no',
            'charge_type',
            'charge_category',
            'code',
            'standard_charge',
            'applied_charge',
            'quantity',
            'total',
        ]));

        return response()->json([
            'message' => 'New Charge Created Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'charge_type' => 'required|string',
            'charge_category' => 'nullable|string',
            'code' => 'nullable|string',
            'standard_charge' => 'required|numeric',
            'applied_charge' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',


        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        $charge = AddCharge::findOrFail($request->chargeid);

        $charge->update($request->only([
            'date',
            'patient_id',
            'charge_type',
            'charge_category',
            'code',
            'standard_charge',
            'applied_charge',
            'quantity',
            'total',
        ]));

        return response()->json([
            'message' => 'Charge updated successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function deletecharge($id)
    {
        try {
            AddCharge::findOrFail($id)->delete();

            return response()->json([
                'message' => 'Delete Successfully!',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the charge!',
                'errors' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }

    public function chargetype()
    {
        $types = ChargeType::select('id', 'name')->get();
        return response()->json($types);
    }

    public function categoryByChargeType($id)
    {
        $types = ChargeCategory::where('charge_type', $id)->get();
        return response()->json($types);
    }

    public function chargedata(Request $request, $id)
    {
        $categories = HospitalCharge::where('charge_category', $id)->first();
        return response()->json($categories);
    }

    public function chargecatlist()
    {
        $chargeCategories = ChargeCategory::all();
        $chargeType = ChargeType::all();
        return view('hospitalCharge.chargeCategory', compact('chargeCategories', 'chargeType'));
    }

    public function chargecatstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'charge_category' => 'required',
            'charge_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        ChargeCategory::create($request->only([
            'charge_category',
            'description',
            'charge_type',
        ]));

        return response()->json([
            'message' => 'New Charge Category Created Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function EditHospitalchargecat(Request $request, $id)
    {
        try {
            $charge = ChargeCategory::findOrFail($id);

            $validated = $request->validate([
                'charge_category' => 'required',
                'description' => 'nullable|string',
            ]);
            // dd($validated);

            $charge->update($validated);

            return response()->json([
                'message' => 'Hospital Charge Category Updated Successfully!',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while updating the hospital charge category!',
                'errors' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }

    public function deletechargeType($id)
    {
        ChargeCategory::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Delete Charge Category Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function storeHospitalcharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'charge_type' => 'required|string',
            'charge_category' => 'nullable|string',
            'code' => 'nullable|string',
            'standard_charge' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        HospitalCharge::create($request->all());

        return response()->json([
            'message' => 'Hospital Charge Created Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function Hospitalchargelist()
    {
        $charges = HospitalCharge::with('chargeCategory', 'chargetype')->get();
        $chargeTypes = ChargeType::all();
        $chargeCategories = ChargeCategory::all();
        return view('hospitalCharge.charge', compact('charges', 'chargeTypes', 'chargeCategories'));
    }

    public function importHospitalCharges(Request $request, HospitalChargeImportService $importService)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please upload a valid Excel (.xlsx) or CSV file.',
                    'errors' => $validator->errors()->all()
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Please upload a valid Excel (.xlsx) or CSV file.')
                ->withErrors($validator);
        }

        $file = $request->file('excel_file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['xlsx', 'csv', 'txt'])) {
            $msg = 'Invalid file format. Only Excel (.xlsx) and CSV (.csv) files are supported.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $result = $importService->import($file->getRealPath(), $ext);

        if (!$result['success']) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($result, 400);
            }
            return redirect()->back()->with('error', $result['message']);
        }

        $successMsg = "Import Complete: {$result['imported']} new charges added, {$result['updated']} updated" . ($result['skipped'] > 0 ? ", {$result['skipped']} skipped" : "") . ".";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'data' => $result
            ]);
        }

        return redirect()->back()->with('success', $successMsg);
    }

    public function downloadSampleCharges(HospitalChargeImportService $importService)
    {
        $csvContent = $importService->generateSampleCsv();
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="hospital_charges_sample.csv"',
        ]);
    }

    public function deleteHospitalcharge($id)
    {
        HospitalCharge::findOrFail($id)->delete();
        return redirect()->back();
    }

    public function EditHospitalcharge(Request $request, $id)
    {
        try {
            $charge = HospitalCharge::findOrFail($id);

            $validated = $request->validate([
                'charge_type' => 'required',
                'charge_category' => 'required',
                'code' => 'nullable|string|max:255',
                'standard_charge' => 'required',
                'description' => 'nullable|string',
            ]);

            // 1. Hospital Charge Update karein
            $charge->update($validated);

    

           PathologyTest::where('charge_category', $request->charge_category)
            ->update(['standard_charge' => $request->standard_charge]);

            return response()->json([
                'message' => 'Hospital Charge & Related Tests Updated Successfully!',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while updating!',
                'errors' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }

    public function getCategoriesByType($id)
    {
        $categories = ChargeCategory::where('charge_type', $id)->get();
        return response()->json($categories);
    }

    public function chargetypelist()
    {
        $chargetypes = ChargeType::all();
        return view('hospitalCharge.Chargetype', compact('chargetypes'));
    }

    public function chargetypestore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        ChargeType::create($request->only(['name', 'description']));

        return response()->json([
            'message' => 'New Charge Type Added Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function chargetypeupdate(Request $request, $id)
    {
        dd($request->all());
        $chargetype = ChargeType::findOrFail($id);
        $chargetype->name = $request->name;
        $chargetype->description = $request->description;
        $chargetype->save();

        return response()->json([
            'message' => 'Charge Type Updated Successfully!',
            'redirect_to' => url()->previous(),
            'status_code' => 1
        ]);
    }

    public function chargetypedelete($id)
    {
        ChargeType::findOrFail($id)->delete();

        return back()->with([
            'message' => 'Charge Type Deleted Successfully!',
            'redirect_to' => url()->previous(),
            'status' => 'success',
            'status_code' => 1
        ]);
    }

    public function Hospitalchargebyid($id)
    {
        try {
            $charge = DB::table('hospital_charges as hc')
                ->leftJoin('charge_categories as cc', 'hc.charge_category', '=', 'cc.id')
                ->leftJoin('charge_types as ct', 'hc.charge_type', '=', 'ct.id')
                ->select(
                    'hc.*',
                    'cc.charge_category as category_name',
                    'cc.description as category_description',
                    'ct.name as type_name',
                    'ct.description as type_description'
                )
                ->where('hc.id', $id)
                ->first();

            return response()->json($charge);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the hospital charge!',
                'errors' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }

    public function typeById($id)
    {
        try {
            $chargeType = ChargeType::findOrFail($id);
            return response()->json($chargeType);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the charge type!',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
    public function EditHospitalchargetype(Request $request, $id)
    {
        try {
            // dd($request->all());
            $chargeType = ChargeCategory::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $chargeType->update([
                'charge_category' => $validated['name'],
                'description' => $validated['description'],
            ]);

            $chargeType->save();



            return response()->json([
                'message' => 'Charge Type Updated Successfully!',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while updating the charge type!',
                'errors' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }
    public function getCategoriesById($id)
    {
        try {
            $chargeCategory = ChargeCategory::findOrFail($id);
            return response()->json($chargeCategory);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the charge category!',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}