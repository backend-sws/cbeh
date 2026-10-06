<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Medicine;
use App\Models\MedicineBill;
use App\Models\SaleMedicine;
use Illuminate\Http\Request;
use App\Models\MedicineBrand;
use App\Models\MedicineCategory;
use App\Models\PurchaseMedicine;
use App\Models\PurchasedMedicine;
use Illuminate\Support\Facades\DB;
use App\Models\MedicineBillPayment;
use App\Models\IpdPatient; // <--- CHANGE 1: Model Import Added
use Illuminate\Support\Facades\Validator;
use App\Services\MedicineImportService;

class MedicineController extends Controller
{
    function medicine_category_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = MedicineCategory::query();
        $query = $query->where('medicine_category', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $medicineCategory = $query ?? collect();
        return view('medicine_categories.medcategory', compact('medicineCategory'));
    }
    function medicine_category_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medicine_category' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            MedicineCategory::create([
                'medicine_category' => $request->medicine_category,
                'status' => $request->status == 'on' ? 1 : 0,
            ]);
            return redirect()->back()->with('success', 'Medicine category saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while saving the record.')->withInput();
        }
    }
    function medicine_category_delete(Request $request, $id)
    {
        try {
            $medicineCategory = MedicineCategory::findOrFail($id);
            $medicineCategory->delete();
            return redirect()->back()->with('success', 'medicine category record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the medicine category.')
                ->withInput();
        }
    }

    function medicine_category_toggle(Request $request)
    {

        $status = $request->status == 1 ? 0 : 1;
        MedicineCategory::where('id', $request->id)->update([
            'status' => $status,
        ]);
        return response()->json([
            'msg' => 'Status Updated Successfully !',
            'status' => $status
        ]);
    }

    public function brand_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = MedicineBrand::query();
        $query = $query->where('brand', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $medicineBrands = $query ?? collect();
        return view('medicine_categories.medicinebrand', compact('medicineBrands'));
    }

    public function brand_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            MedicineBrand::create([
                'brand' => $request->brand,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return redirect()->back()->with('success', 'Medicine company saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while saving the company.', $e)->withInput();
        }
    }

    public function brand_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'brand' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            $brand = MedicineBrand::findOrFail($id);
            $brand->update([
                'brand' => $request->brand,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            return redirect()->back()->with('success', 'Medicine company updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating the company.')->withInput();
        }
    }

    function brand_delete(Request $request, $id)
    {
        try {
            $brand = MedicineBrand::findOrFail($id);
            $brand->delete();
            return redirect()->back()->with('success', 'Medicine brand deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the brand.')
                ->withInput();
        }
    }

    public function medicine_index()
    {
        // dd(request()->all());
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');
        $medicine_filter = request()->input('medicine_filter');
        $stock_filter = request()->input('stock_filter');

        $query = Medicine::with(['brandRelation', 'categoryRelation'])
            ->select('medicines.*')
            ->leftJoin('medicine_brands', 'medicines.brand', '=', 'medicine_brands.id')
            ->leftJoin('medicine_categories', 'medicines.category', '=', 'medicine_categories.id');

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('medicines.medicine', 'like', "%$search%")
                    ->orWhere('medicines.salt_composition', 'like', "%$search%")
                    ->orWhere('medicine_brands.brand', 'like', "%$search%")
                    ->orWhere('medicine_categories.medicine_category', 'like', "%$search%");
            });
        }


        // Date range filter
        if ($from_date && $to_date) {
            $query->whereDate('medicines.created_at', '>=', $from_date)
                ->whereDate('medicines.created_at', '<=', $to_date);
        } elseif ($from_date) {
            $query->whereDate('medicines.created_at', '>=', $from_date);
        } elseif ($to_date) {
            $query->whereDate('medicines.created_at', '<=', $to_date);
        }

        // Medicine specific filter
        if ($medicine_filter) {
            $query->where('medicines.id', $medicine_filter);
        }

        // Stock filter
        if ($stock_filter) {
            if ($stock_filter === 'low_stock') {
                $query->where('medicines.available_quantity', '<', 20)
                    ->where('medicines.available_quantity', '>', 0);
            } elseif ($stock_filter === 'critical_stock') {
                $query->where('medicines.available_quantity', '<', 10)
                    ->where('medicines.available_quantity', '>', 0);
            } elseif ($stock_filter === 'out_of_stock') {
                $query->where('medicines.available_quantity', '<=', 0);
            }
        }

        $medicines = $query->paginate($perPage);

        // Get all medicines for dropdown
        $all_medicines = Medicine::orderBy('medicine')->get();

        // Summary calculations with same filters
        $summaryQuery = Medicine::query();

        if ($from_date && $to_date) {
            $summaryQuery->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date);
        } elseif ($from_date) {
            $summaryQuery->whereDate('created_at', '>=', $from_date);
        } elseif ($to_date) {
            $summaryQuery->whereDate('created_at', '<=', $to_date);
        }

        if ($medicine_filter) {
            $summaryQuery->where('id', $medicine_filter);
        }

        // Stock filter for summary
        if ($stock_filter) {
            if ($stock_filter === 'low_stock') {
                $summaryQuery->where('available_quantity', '<', 20)
                    ->where('available_quantity', '>', 0);
            } elseif ($stock_filter === 'critical_stock') {
                $summaryQuery->where('available_quantity', '<', 10)
                    ->where('available_quantity', '>', 0);
            } elseif ($stock_filter === 'out_of_stock') {
                $summaryQuery->where('available_quantity', '<=', 0);
            }
        }

        $summary = [
            'total_medicines' => $summaryQuery->count(),
            'total_quantity' => $summaryQuery->sum('quantity'),
            'available_quantity' => $summaryQuery->sum('available_quantity'),
            'total_purchase_value' => $summaryQuery->sum(\DB::raw('quantity * buying_price')),
            'total_selling_value' => $summaryQuery->sum(\DB::raw('quantity * selling_price')),
            'expected_profit' => $summaryQuery->sum(\DB::raw('quantity * (selling_price - buying_price)')),
            'avg_profit_per_unit' => $summaryQuery->avg(\DB::raw('selling_price - buying_price')),
            'low_stock_count' => $summaryQuery->clone()->where('available_quantity', '<', 20)->where('available_quantity', '>', 0)->count(),
            'out_of_stock_count' => $summaryQuery->clone()->where('available_quantity', '<=', 0)->count(),
            'critical_stock_count' => $summaryQuery->clone()->where('available_quantity', '<', 10)->where('available_quantity', '>', 0)->count(),
        ];

        $hasRootTalhaFile = file_exists(base_path('TALHA.xlsx')) || file_exists(base_path('talha.xlsx'));

        return view('medicine_categories.medicine', compact('medicines', 'summary', 'all_medicines', 'hasRootTalhaFile'));
    }



    public function medicine_add()
    {
        $brands = MedicineBrand::all();
        $categories = MedicineCategory::all();
        return view('medicine_categories.newmedicine', compact('brands', 'categories')); // This should render your form
    }

    public function medicine_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medicine' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'salt_composition' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'side_effects' => 'nullable|string',
            'description' => 'nullable|string',
            'strip_quantity' => 'nullable|integer|min:1',
            'opening_stock_strips' => 'nullable|integer|min:0',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'msg' => 'Some validation errors occurred!',
                    'errors' => $validator->errors(),
                    'status' => 422
                ]);
            }
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            $stripQuantity = max(1, intval($request->strip_quantity ?? 1));
            $openingStrips = max(0, intval($request->opening_stock_strips ?? 0));
            $totalPieces = $openingStrips * $stripQuantity;

            $medicine = Medicine::create([
                'medicine' => $request->medicine,
                'category' => $request->category,
                'brand' => $request->brand,
                'salt_composition' => $request->salt_composition,
                'unit' => $request->unit,
                'buying_price' => $request->buying_price ?? 0,
                'selling_price' => $request->selling_price ?? 0,
                'side_effects' => $request->side_effects,
                'description' => $request->description,
                'strip_quantity' => $stripQuantity,
                'quantity' => $openingStrips,
                'available_quantity' => $openingStrips,
                'total_quantity' => $totalPieces,
            ]);

            return redirect()->route('admin.medicine.index')->with('success', 'Medicine Record Saved Successfully.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'msg' => 'An error occurred while saving the medicine record!',
                    'errors' => $e->getMessage(),
                    'status' => 500
                ]);
            }
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the medicine: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function medicine_import(Request $request, MedicineImportService $importService)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a valid Excel (.xlsx) or CSV file.',
                    'errors' => $validator->errors()->all()
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Please select a valid Excel (.xlsx) or CSV file.')
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

        $tempPath = $file->getRealPath();
        $result = $importService->import($tempPath, $ext);

        if (!$result['success']) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($result, 400);
            }
            return redirect()->back()->with('error', $result['message']);
        }

        $successMsg = "Import Complete: {$result['imported']} new medicines added, {$result['updated']} updated" . ($result['skipped'] > 0 ? ", {$result['skipped']} skipped" : "") . ".";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'data' => $result
            ]);
        }

        return redirect()->route('admin.medicine.index')->with('success', $successMsg);
    }

    public function medicine_import_root(MedicineImportService $importService)
    {
        $possibleFiles = ['TALHA.xlsx', 'talha.xlsx', 'tahlha.xlsx'];
        $foundPath = null;
        foreach ($possibleFiles as $name) {
            $path = base_path($name);
            if (file_exists($path)) {
                $foundPath = $path;
                break;
            }
        }

        if (!$foundPath) {
            return redirect()->back()->with('error', 'TALHA.xlsx file was not found in the root directory.');
        }

        $result = $importService->import($foundPath);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        $successMsg = "Root Excel file (TALHA.xlsx) imported successfully: {$result['imported']} new medicines created, {$result['updated']} updated" . ($result['skipped'] > 0 ? ", {$result['skipped']} skipped" : "") . ".";

        return redirect()->route('admin.medicine.index')->with('success', $successMsg);
    }

    public function medicine_import_sample(MedicineImportService $importService)
    {
        $csvContent = $importService->generateSampleCsv();
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="medicine_bulk_import_sample.csv"',
        ]);
    }


    function medicine_delete(Request $request, $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->delete();
            return redirect()->back()->with('success', 'Medicine  deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the medicine.')
                ->withInput();
        }
    }

    public function medicine_edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        $brands = MedicineBrand::all();
        $categories = MedicineCategory::all();

        return view('medicine_categories.editmedicine', compact('medicine', 'brands', 'categories'));
    }

    public function medicine_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medicine' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'salt_composition' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'buying_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'side_effects' => 'nullable|string',
            'description' => 'nullable|string',
            'strip_quantity' => 'nullable|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
            'total_quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'msg' => 'Validation failed!',
                    'errors' => $validator->errors(),
                    'status' => 422
                ]);
            }
            return redirect()->back()
                ->with('error', 'Validation failed!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            $medicine = Medicine::findOrFail($id);
            $stripSize = max(1, intval($request->strip_quantity ?? $medicine->strip_quantity ?? 1));

            // Sync stock fields
            $availableStrips = $request->has('available_quantity')
                ? max(0, intval($request->available_quantity))
                : $medicine->available_quantity;

            $totalPieces = $request->has('total_quantity')
                ? max(0, intval($request->total_quantity))
                : ($availableStrips * $stripSize);

            // If available_quantity was submitted but total_quantity wasn't
            if ($request->filled('available_quantity') && !$request->filled('total_quantity')) {
                $totalPieces = $availableStrips * $stripSize;
            } elseif ($request->filled('total_quantity') && !$request->filled('available_quantity')) {
                $availableStrips = $stripSize > 0 ? floor($totalPieces / $stripSize) : 0;
            }

            $medicine->update([
                'medicine' => $request->medicine,
                'category' => $request->category,
                'brand' => $request->brand,
                'salt_composition' => $request->salt_composition,
                'unit' => $request->unit ?? $medicine->unit,
                'buying_price' => $request->buying_price ?? $medicine->buying_price,
                'selling_price' => $request->selling_price ?? $medicine->selling_price,
                'strip_quantity' => $stripSize,
                'quantity' => $availableStrips,
                'available_quantity' => $availableStrips,
                'total_quantity' => $totalPieces,
                'side_effects' => $request->side_effects,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.medicine.index')->with('success', 'Medicine updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'msg' => 'An error occurred!',
                    'errors' => $e->getMessage(),
                    'status' => 500
                ]);
            }
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function purchase_index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = PurchaseMedicine::query();

        // Purchase Number Search
        if (!empty($search)) {
            $query->where('purchase_no', 'like', '%' . $search . '%');
        }

        if (!empty($from_date)) {
            $query->whereDate('created_at', '>=', $from_date); // purchase_date ki jagah created_at
        }
        if (!empty($to_date)) {
            $query->whereDate('created_at', '<=', $to_date); // purchase_date ki jagah created_at
        }

        $purchase_medicine = $query->latest('id')->paginate($perPage);

        // Filter values ko view mein wapas bhejna zaroori hai input fields fill rakhne ke liye
        return view('medicine_categories.purchasemedicine', compact('purchase_medicine', 'search', 'from_date', 'to_date'));
    }
    public function purchase_medicine_add()
    {
        $medicines = Medicine::orderBy('medicine', 'asc')->get();
        return view('medicine_categories.add_purchase_medicine', compact('medicines'));
    }
    public function getMedicineByCategory(Request $request, $id)
    {
        $medicines = Medicine::where('category', $id)->get();

        return response()->json($medicines);
    }


    public function getAllMedicines()
    {
        return Medicine::select('id', 'medicine')
            ->orderBy('medicine', 'asc') // 'asc' का मतलब है A-Z (Alphabetical Order)
            ->get();
    }


    public function purchase_medicine_save(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'medicine_id' => 'required|array',
            'medicine_id.*' => 'required|exists:medicines,id',
            'lot_no.*' => 'required|string|max:255',
            'expiry_date.*' => 'required',
            'sale_price.*' => 'required|numeric|min:0',
            'purchase_price.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|integer|min:1',
            'tax.*' => 'nullable|numeric|min:0|max:100',
            'amount.*' => 'required|numeric|min:0',

            // Global Bill Fields
            'note' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|integer', // Ensure integer
            'payment_note' => 'nullable|string',
            'supplier_name' => 'nullable|string|max:255',
            'bill_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'status_code' => 0,
                'msg' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            // 2. Create Main Purchase Record
            $purchase = PurchaseMedicine::create([
                'purchase_no' => 'PUR-' . strtoupper(uniqid()),
                'supplier_id' => null,
                'note' => $request->note,
                'discount' => floatval($request->discount ?? 0),
                'total' => floatval($request->total),
                'tax' => floatval($request->tax_amount),
                'net_amount' => floatval($request->net_amount),
                'payment_type' => intval($request->payment_type), // Type-safe integer
                'payment_note' => $request->payment_note,
                'purchase_date' => now(),
                'supplier_name' => $request->supplier_name,
                'bill_number' => $request->bill_number,
            ]);

            // 3. Save Purchase Items & Update Stock/Prices
            if ($request->has('medicine_id')) {
                foreach ($request->medicine_id as $index => $medicineId) {

                    // --- DATE LOGIC: Handle YYYY-MM correctly ---
                    $inputDate = $request->expiry_date[$index];
                    try {
                        $finalExpiryDate = Carbon::parse($inputDate)->endOfMonth()->toDateString();
                    } catch (\Exception $e) {
                        $finalExpiryDate = now()->addYear()->toDateString(); // Fallback date if invalid
                    }

                    $salePrice = floatval($request->sale_price[$index] ?? 0);
                    $buyPrice = floatval($request->purchase_price[$index] ?? 0);
                    $qty = intval($request->quantity[$index] ?? 0);
                    $tax = floatval($request->tax[$index] ?? 0);
                    $amount = floatval($request->amount[$index] ?? 0);

                    $purchase->purchasedMedicines()->create([
                        'medicine_id' => $medicineId,
                        'lot_no' => $request->lot_no[$index] ?? null,
                        'expiry_date' => $finalExpiryDate,
                        'sale_price' => $salePrice,
                        'purchase_price' => $buyPrice,
                        'quantity' => $qty,
                        'available_quantity' => $qty,
                        'tax' => $tax,
                        'amount' => $amount,
                    ]);

                    // 4. Update Medicine Master Table
                    $medicine = Medicine::find($medicineId);

                    if ($medicine) {
                        // A. Update Stock (Available Quantity in strips)
                        $stripSize = $medicine->strip_quantity > 0 ? $medicine->strip_quantity : 1;
                        $piecesToAdd = $qty * $stripSize;
                        $medicine->increment('total_quantity', $piecesToAdd);  // Pieces
                        $medicine->increment('available_quantity', $qty);  // Strips

                        // B. UPDATE MASTER PRICES (Critical Fix)
                        // Update master prices with latest values from this purchase
                        // This ensures fallback prices are always available
                        // Batch-level prices remain stored in PurchasedMedicine table
                        if ($buyPrice > 0) {
                            $medicine->buying_price = $buyPrice;
                        }
                        if ($salePrice > 0) {
                            $medicine->selling_price = $salePrice;
                        }

                        $medicine->save();
                    }
                }
            }

            DB::commit();

            $redirect_to = route('admin.medicine.purchase.index');

            return response()->json([
                'status' => 'success',
                'status_code' => 1,
                'message' => 'Purchase recorded successfully!',
                'redirect_to' => $redirect_to,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'status_code' => 0,
                'msg' => 'Server Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function bill_index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        $query = MedicineBill::query();

        // Date filter
        if (!empty($from_date) && !empty($to_date)) {
            $query->whereDate('bill_date', '>=', $from_date)
                ->whereDate('bill_date', '<=', $to_date);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%$search%")
                    ->orWhere('patient_name', 'like', "%$search%")
                    ->orWhere('online_payment_type', 'like', "%$search%");
            });
        }

        // FIXED: Analytics based on actual Transactions (Payments) within the date range
        $paymentQuery = MedicineBillPayment::query();
        if (!empty($from_date) && !empty($to_date)) {
            $paymentQuery->whereDate('payment_date', '>=', $from_date)
                        ->whereDate('payment_date', '<=', $to_date);
        }

        $total_cash = $paymentQuery->clone()->where('payment_mode', 'Cash')->sum('amount');
        $total_online = $paymentQuery->clone()->where('payment_mode', 'Online')->sum('amount');
        
        // Total revenue based on net_amount of bills created in this period
        $summaryQuery = clone $query;
        $total_revenue = $summaryQuery->sum('net_amount');

        // Fetch paginated results
        $medicine_bills = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Make sure the view path matches your folder structure
        return view('medicine_categories.bill', compact('medicine_bills', 'total_cash', 'total_online', 'total_revenue', 'search', 'from_date', 'to_date'));
    }

    public function bill_add()
    {
        $patients = User::role('Patient')->with('patientProfile')->latest()->get();
        // dd($patients);
        $medicineCategory = MedicineCategory::all();
        return view('medicine_categories.add_bill', compact('patients', 'medicineCategory'));
    }

    public function getMedicineDetails($id)
    {
        $medicine = Medicine::findOrFail($id);

        // FEFO Logic: Wo batch dhundo jo stock me hai aur sabse pehle expire hoga
        $next_expiring_batch = PurchasedMedicine::where('medicine_id', $id)
            ->where('available_quantity', '>', 0) // Sirf wahi jisme stock bacha hai
            ->orderBy('expiry_date', 'asc')       // Jo jaldi expire hoga wo pehle
            ->first();

        // Agar batch nahi mila - Get oldest batch for negative sale reference
        if (!$next_expiring_batch) {
            // Get oldest batch (by creation date) to use for negative sales
            $oldestBatch = PurchasedMedicine::where('medicine_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            $fallbackExpiry = $oldestBatch ? Carbon::parse($oldestBatch->expiry_date)->format('Y-m-d') : null;
            $fallbackLotNo = $oldestBatch ? $oldestBatch->lot_no : '';
            $fallbackTax = $oldestBatch ? ($oldestBatch->tax ?? 0) : 0;
            if (!$fallbackTax) {
                $latestBatchWithTax = PurchasedMedicine::where('medicine_id', $id)
                    ->whereNotNull('tax')
                    ->where('tax', '>', 0)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($latestBatchWithTax) {
                    $fallbackTax = $latestBatchWithTax->tax;
                }
            }

            return response()->json([
                'error' => 'No stock available for this medicine',
                'expiry_date' => $fallbackExpiry,  // Use oldest batch's expiry or null
                'lot_no' => $fallbackLotNo,
                'sale_price' => $medicine->selling_price ?? 0,
                'tax' => floatval($fallbackTax ?? 0),
                'available_quantity' => 0,
                'total_quantity' => $medicine->total_quantity ?? 0,
                'strip_quantity' => $medicine->strip_quantity,
                'warning' => 'Negative sale will be recorded'
            ]);
        }

        // Agar batch mila to date format karo
        $expiry_date = Carbon::parse($next_expiring_batch->expiry_date)->format('Y-m-d');
        $lot_no = $next_expiring_batch->lot_no;
        // Get price from batch (NOT from master medicine)
        $sale_price = $next_expiring_batch->sale_price ?? $medicine->selling_price ?? 0;
        $tax = $next_expiring_batch->tax;
        if ($tax === null || $tax == 0) {
            $latestBatchWithTax = PurchasedMedicine::where('medicine_id', $id)
                ->whereNotNull('tax')
                ->where('tax', '>', 0)
                ->orderBy('id', 'desc')
                ->first();
            if ($latestBatchWithTax) {
                $tax = $latestBatchWithTax->tax;
            }
        }

        return response()->json([
            'expiry_date' => $expiry_date,
            'lot_no' => $lot_no,
            'sale_price' => $sale_price,  // From batch level
            'tax' => floatval($tax ?? 0),
            'available_quantity' => $medicine->available_quantity, // Total available strips
            'total_quantity' => $medicine->total_quantity, // Total pieces
            'strip_quantity' => $medicine->strip_quantity,
        ]);
    }

    public function bill_save(Request $request)
    {
        // 1. Validate the incoming request
        $validator = Validator::make($request->all(), [
            'patient_select' => 'required',
            'bill_date' => 'required|date',
            'sale_medicines' => 'required|array|min:1',
            'sale_medicines.*.medicine_id' => 'required|exists:medicines,id',
            'sale_medicines.*.quantity' => 'required|numeric|min:1',
            'sale_medicines.*.unit' => 'required|in:strip,piece',
            'sale_medicines.*.price' => 'required|numeric',
            'sale_medicines.*.amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 0,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // 2. Determine Patient Logic
            $patientId = null;
            $patientName = $request->patient_select;
            $ipdPatientId = null;

            // Check if input is a numeric ID (Existing Patient)
            if (is_numeric($request->patient_select)) {
                $patient = User::find($request->patient_select);
                if ($patient) {
                    $patientId = $patient->id;
                    $patientName = $patient->name;

                    // Check Active IPD Admission
                    $activeIpd = IpdPatient::where('patient_id', $patientId)
                        ->whereNull('discharge_date')
                        ->latest()
                        ->first();

                    if ($activeIpd) {
                        $ipdPatientId = $activeIpd->id;
                    }
                }
            }

            // --- NET AMOUNT & PAYMENT STATUS LOGIC ---
            $subTotal = floatval($request->total ?? 0);
            $taxAmount = floatval($request->tax_amount ?? 0);
            $discount = floatval($request->discount ?? 0);
            $exactNetAmount = round($subTotal + $taxAmount - $discount, 2);
            $netAmount = $exactNetAmount;

            $totalPaid = round(floatval($request->paid_cash ?? 0) + floatval($request->paid_online ?? 0), 2);

            $paymentStatus = 'unpaid';
            if ($totalPaid >= ($netAmount - 0.05)) {  // 0.05 tolerance for float precision
                $paymentStatus = 'paid';
            } elseif ($totalPaid > 0) {
                $paymentStatus = 'partial';
            }

            // Generate Bill Number
            $year = date('Y');
            $lastBill = MedicineBill::where('bill_number', 'like', '%PHAR' . $year . '%')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastBill) {
                $lastSequence = intval(substr($lastBill->bill_number, -5));
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            $billNumber = 'CBPHAR' . $year . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);

            $bill = MedicineBill::create([
                'bill_number' => $billNumber,
                'patient_id' => $patientId,
                'ipd_patient_id' => $ipdPatientId,
                'patient_name' => $patientName,
                'bill_date' => $request->bill_date,
                'total' => $request->total,
                'discount' => $request->discount ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'net_amount' => $netAmount,
                'paid_cash' => $request->paid_cash ?? 0,
                'paid_online' => $request->paid_online ?? 0,
                'online_payment_type' => $request->online_payment_type,
                'payment_status' => $paymentStatus,
                'note' => $request->note,
                'created_by' => auth()->id(),
            ]);

            // 4. Process Medicine Items & Update Stock (Negative Stock Allowed)
            foreach ($request->sale_medicines as $item) {

                if (empty($item['medicine_id']))
                    continue;

                $medicine = Medicine::findOrFail($item['medicine_id']);

                // Get Strip Size (Default to 1)
                $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;

                // Calculate Total Needed in Pieces (Base Unit)
                $qty_needed_pieces = 0;
                if ($item['unit'] == 'strip') {
                    $qty_needed_pieces = $item['quantity'] * $stripSize;
                } else {
                    $qty_needed_pieces = $item['quantity'];
                }

                // Step A: Consume form Batches First (FEFO)
                $batches = \App\Models\PurchasedMedicine::where('medicine_id', $item['medicine_id'])
                    ->where('available_quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qty_needed_pieces <= 0)
                        break;

                    // Take what is available
                    $take_pieces = min($qty_needed_pieces, $batch->available_quantity);

                    // Convert pieces back to user unit for recording
                    $recorded_qty = 0;
                    if ($item['unit'] == 'strip') {
                        $recorded_qty = $take_pieces / $stripSize;
                    } else {
                        $recorded_qty = $take_pieces;
                    }

                    // Create Sale Record (With Batch Info)
                    SaleMedicine::create([
                        'medicine_bill_id' => $bill->id,
                        'medicine_id' => $item['medicine_id'],
                        'lot_no' => $batch->lot_no,
                        'expiry_date' => $batch->expiry_date,
                        'quantity' => $recorded_qty,
                        'unit_type' => $item['unit'],
                        'price' => $item['price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'amount' => ($recorded_qty * $item['price']),
                    ]);

                    // Deduct from Batch
                    $batch->decrement('available_quantity', $take_pieces);

                    // Deduct from Main Stock
                    $medicine->decrement('total_quantity', $take_pieces);

                    $qty_needed_pieces -= $take_pieces;
                }

                // Step B: Handle Remaining Quantity (NEGATIVE STOCK LOGIC - ALLOWED)
                // Agar batch khatam ho gaye lekin abhi bhi aur quantity chahiye
                if ($qty_needed_pieces > 0) {

                    // Convert remaining pieces back to user unit
                    $recorded_qty = 0;
                    if ($item['unit'] == 'strip') {
                        $recorded_qty = $qty_needed_pieces / $stripSize;
                    } else {
                        $recorded_qty = $qty_needed_pieces;
                    }

                    // ✅ NEGATIVE SALE: Get expiry_date from the oldest (last to be created) batch
                    // This ensures we track the negative sale against an existing batch's expiry
                    $oldestBatch = \App\Models\PurchasedMedicine::where('medicine_id', $item['medicine_id'])
                        ->orderBy('created_at', 'desc')  // Get the last created batch
                        ->first();

                    $negativeExpiryDate = $oldestBatch ? $oldestBatch->expiry_date : now()->addYear();
                    $negativeLotNo = $oldestBatch ? $oldestBatch->lot_no : 'NEGATIVE_' . date('YmdHis');

                    // Create sale record for NEGATIVE STOCK
                    // This indicates stock was sold beyond available quantity
                    SaleMedicine::create([
                        'medicine_bill_id' => $bill->id,
                        'medicine_id' => $item['medicine_id'],
                        'lot_no' => $negativeLotNo,  // Reference to existing batch
                        'expiry_date' => $negativeExpiryDate,  // Use existing batch's expiry
                        'quantity' => $recorded_qty,
                        'unit_type' => $item['unit'],
                        'price' => $item['price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'amount' => ($recorded_qty * $item['price']),
                    ]);

                    // Deduct from Main Stock (ALLOWED TO GO NEGATIVE)
                    $medicine->decrement('total_quantity', $qty_needed_pieces);
                }

                // Step C: Update UI Cached Fields
                $medicine->refresh();
                // Negative value allowed in UI display
                $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
                $medicine->update(['available_quantity' => $newAvailableStrips]);
            }

            // 5. Record Initial Payments
            if ($totalPaid > 0) {
                // Cash Entry
                if ($request->paid_cash > 0) {
                    MedicineBillPayment::create([
                        'medicine_bill_id' => $bill->id,
                        'ipd_patient_id' => $ipdPatientId, // FIXED: Link to IPD
                        'payment_date' => now(),
                        'amount' => $request->paid_cash,
                        'payment_mode' => 'Cash',
                        'note' => 'Initial Payment (Bill Creation)',
                    ]);
                }

                // Online Entry
                if ($request->paid_online > 0) {
                    MedicineBillPayment::create([
                        'medicine_bill_id' => $bill->id,
                        'ipd_patient_id' => $ipdPatientId, // FIXED: Link to IPD
                        'payment_date' => now(),
                        'amount' => $request->paid_online,
                        'payment_mode' => $request->online_payment_type ?? 'Online',
                        'note' => 'Initial Payment (Bill Creation)',
                    ]);
                }
            }

            DB::commit();

            $printUrl = route('admin.medicine.bill.show', $bill->id);

            return response()->json([
                'status_code' => 1,
                'status' => 'success',
                'message' => 'Bill created successfully',
                'print_url' => $printUrl
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status_code' => 0,
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    //     public function bill_save(Request $request)
//     {
//         // 1. Validate the incoming request
//         $validator = Validator::make($request->all(), [
//             'patient_select' => 'required', // Can be ID (int) or Name (string)
//             'bill_date' => 'required|date',
//             'sale_medicines' => 'required|array|min:1',
//             'sale_medicines.*.medicine_id' => 'required|exists:medicines,id',
//             'sale_medicines.*.quantity' => 'required|numeric|min:1',
//             'sale_medicines.*.unit' => 'required|in:strip,piece',
//             'sale_medicines.*.price' => 'required|numeric',
//             'sale_medicines.*.amount' => 'required|numeric',
//         ]);

    //         if ($validator->fails()) {
//             return response()->json([
//                 'status_code' => 0,
//                 'status' => 'error',
//                 'message' => $validator->errors()->first()
//             ], 422);
//         }

    //         DB::beginTransaction();

    //         try {
//             // 2. Determine Patient Logic
//             $patientId = null;
//             $patientName = $request->patient_select; // Default to string input
//             $ipdPatientId = null; // <--- CHANGE 2: Init Variable

    //             // Check if input is a numeric ID matching a user
//             if (is_numeric($request->patient_select)) {
//                 $patient = User::find($request->patient_select);
//                 if ($patient) {
//                     $patientId = $patient->id;
//                     $patientName = $patient->name;

    //                     // --- CHANGE 2: NEW LOGIC - Check Active IPD Admission ---
//                     $activeIpd = IpdPatient::where('patient_id', $patientId)
//                         ->whereNull('discharge_date') // Assuming null means admitted
//                         ->latest()
//                         ->first();

    //                     if ($activeIpd) {
//                         $ipdPatientId = $activeIpd->id;
//                     }
//                     // ---------------------------------------------
//                 }
//             }

    //             // 3. Create Main Bill Record
//             // Calculate status based on payment
//             $totalPaid = ($request->paid_cash ?? 0) + ($request->paid_online ?? 0);
//             $netAmount = $request->net_amount;

    //             $paymentStatus = 'unpaid';
//             if ($totalPaid >= $netAmount - 0.1) { // Tolerance for float
//                 $paymentStatus = 'paid';
//             } elseif ($totalPaid > 0) {
//                 $paymentStatus = 'partial';
//             }

    //             // Generate Bill Number (Format: PHAR-Ymd-Random)
//             // 1. Current Year nikalein (e.g., 2026)
//             $year = date('Y');

    //             // 2. Database se last bill number fetch karein jo is saal ka ho
// // (Yahan 'MedicineBill' aapka model name hai, use replace kar lein agar alag hai)
//             $lastBill = MedicineBill::where('bill_number', 'like', 'THPHAR' . $year . '%')
//                 ->orderBy('id', 'desc')
//                 ->first();

    //             // 3. Sequence Determine karein
//             if ($lastBill) {
//                 // Agar last bill mila, to uska number extract karke +1 karein
//                 // Format: THPHAR202600001 (Total length check karein ya last 5 digits lein)

    //                 // Last 5 digits (sequence) nikalna
//                 $lastSequence = intval(substr($lastBill->bill_number, -5));
//                 $nextSequence = $lastSequence + 1;
//             } else {
//                 // Agar koi bill nahi hai is saal ka, to 1 se shuru karein
//                 $nextSequence = 1;
//             }

    //             // 4. Naya Bill Number Format karein (THPHAR + Year + 5 Digit Padding)
// // str_pad() se 1 ko 00001 banayenge
//             $billNumber = 'THPHAR' . $year . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);

    //             // Result: THPHAR202600001, THPHAR202600002, etc.

    //             $bill = MedicineBill::create([
//                 'bill_number' => $billNumber,
//                 'patient_id' => $patientId,
//                 'ipd_patient_id' => $ipdPatientId, // <--- CHANGE 2: Save IPD ID
//                 'patient_name' => $patientName,
//                 'bill_date' => $request->bill_date,
//                 'total' => $request->total,
//                 'discount' => $request->discount ?? 0,
//                 'tax_amount' => $request->tax_amount ?? 0,
//                 'net_amount' => $netAmount,
//                 'paid_cash' => $request->paid_cash ?? 0,
//                 'paid_online' => $request->paid_online ?? 0,
//                 'online_payment_type' => $request->online_payment_type,
//                 'payment_status' => $paymentStatus,
//                 'note' => $request->note,
//                 'created_by' => auth()->id(), // Track who created
//             ]);

    //             // 4. Process Medicine Items & Update Stock
//             foreach ($request->sale_medicines as $item) {
//                 // Skip if no medicine selected
//                 if (empty($item['medicine_id']))
//                     continue;

    //                 $medicine = Medicine::findOrFail($item['medicine_id']);

    //                 // Calculate pieces to deduct
//                 $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;
//                 $deductedPieces = 0;

    //                 if ($item['unit'] == 'strip') {
//                     $deductedPieces = $item['quantity'] * $stripSize;
//                 } else {
//                     // Piece
//                     $deductedPieces = $item['quantity'];
//                 }

    //                 // Save Item
//                 SaleMedicine::create([
//                     'medicine_bill_id' => $bill->id,
//                     'medicine_id' => $item['medicine_id'],
//                     'quantity' => $item['quantity'],
//                     'unit_type' => $item['unit'],
//                     'price' => $item['price'],
//                     'discount_percent' => $item['discount_percent'] ?? 0,
//                     'tax' => $item['tax'] ?? 0,
//                     'amount' => $item['amount'],
//                     'expiry_date' => $item['expiry_date'] ?? null,
//                 ]);

    //                 // Update Inventory (Allow negative stock if needed, or check here)
//                 $medicine->decrement('total_quantity', $deductedPieces);

    //                 // Update the cached 'available_quantity' (Strips count) for UI
//                 $medicine->refresh();
//                 $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
//                 $medicine->update(['available_quantity' => $newAvailableStrips]);
//             }

    //             // 5. Record Initial Payments in History Table
//             if ($totalPaid > 0) {
//                 // Cash Entry
//                 if ($request->paid_cash > 0) {
//                     MedicineBillPayment::create([
//                         'medicine_bill_id' => $bill->id,
//                         'payment_date' => now(),
//                         'amount' => $request->paid_cash,
//                         'payment_mode' => 'Cash',
//                         'note' => 'Initial Payment (Bill Creation)',
//                         // 'created_by' => auth()->id()
//                     ]);
//                 }

    //                 // Online Entry
//                 if ($request->paid_online > 0) {
//                     MedicineBillPayment::create([
//                         'medicine_bill_id' => $bill->id,
//                         'payment_date' => now(),
//                         'amount' => $request->paid_online,
//                         'payment_mode' => $request->online_payment_type ?? 'Online',
//                         'note' => 'Initial Payment (Bill Creation)',
//                         // 'created_by' => auth()->id()
//                     ]);
//                 }
//             }

    //             DB::commit();

    //             // 6. Return Success with Print URL
//             // Ensure you have a route named 'admin.medicine.bill.show' that accepts ID
//             $printUrl = route('admin.medicine.bill.show', $bill->id);

    //             return response()->json([
//                 'status_code' => 1,
//                 'status' => 'success',
//                 'message' => 'Bill created successfully',
//                 'print_url' => $printUrl // JS will open this
//             ]);

    //         } catch (\Exception $e) {
//             DB::rollBack();
//             return response()->json([
//                 'status_code' => 0,
//                 'status' => 'error',
//                 'message' => 'Error: ' . $e->getMessage()
//             ], 500);
//         }
//     }
    // public function bill_delete($id)
    // {
    //     $MedicineBill = MedicineBill::findOrFail($id);


    //     $MedicineBill->delete();

    //     return redirect()->back()->with('success', 'Medicine bill has been deleted successfully');
    // }

    public function bill_delete($id)
    {
        DB::beginTransaction();

        try {
            // 1. Bill Dhundo
            $MedicineBill = MedicineBill::with('saleMedicine')->findOrFail($id);

            // 2. Bill ke items nikalo
            $soldItems = $MedicineBill->saleMedicine;

            // 3. Stock Return Logic
            foreach ($soldItems as $item) {

                $medicine = Medicine::find($item->medicine_id);

                if ($medicine) {
                    // A. Strip Size compute karo
                    $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;

                    // B. Quantity convert karo (Pieces me)
                    $qtyToRestore = 0;

                    if ($item->unit_type == 'strip') {
                        $qtyToRestore = $item->quantity * $stripSize;
                    } else {
                        $qtyToRestore = $item->quantity;
                    }

                    // --- C. BATCH STOCK RESTORE (WITH ERROR HANDLING) ---
                    if ($item->lot_no) {
                        // Batch ko dhundo
                        $batch = \App\Models\PurchasedMedicine::where('medicine_id', $item->medicine_id)
                            ->where('lot_no', $item->lot_no)
                            ->first();

                        if ($batch) {
                            // Batch mil gaya, stock restore karo
                            $batch->increment('available_quantity', $qtyToRestore);
                        } else {
                            // ⚠️ Log warning: Batch was deleted but sale record exists
                            \Log::warning("Batch Not Found: Medicine {$item->medicine_id}, Lot {$item->lot_no}. Sale record ID: {$item->id}. Stock will only be restored to main table.");
                            // Stock badhenge sirf main table me
                        }
                    } else {
                        // Negative sale (NULL batch) - just restore main stock
                        // No batch to restore to
                    }

                    // --- D. MAIN STOCK RESTORE ---
                    $medicine->increment('total_quantity', $qtyToRestore);

                    // E. Available Strips recalculate karo
                    $medicine->refresh();
                    $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
                    $medicine->update(['available_quantity' => $newAvailableStrips]);
                }
            }

            // 4. Sold Items Delete karo
            $MedicineBill->saleMedicine()->delete();

            // 5. Payment History Delete karo (Try both relationship names)
            try {
                $MedicineBill->medicinePayments()->delete();
            } catch (\Exception $e) {
                // Try alternate relationship name
                $MedicineBill->payments()->delete();
            }

            // 6. Main Bill Delete karo
            $MedicineBill->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Bill deleted & Stock restored successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function sale_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = SaleMedicine::query();
        $query = $query->paginate($perPage);
        $sale_medicines = $query ?? collect();
        return view('medicine_categories.sale', compact('sale_medicines'));
    }
    public function bill_show($id)
    {
        $bill = MedicineBill::with(['patient', 'saleMedicine.medicine'])->findOrFail($id);
        // dd($bill);

        return view('medicine_categories.bill_pdf', compact('bill'));
    }


    public function purchase_medicine_show($id)
    {
        $purchase = PurchaseMedicine::with(['purchasedMedicines.medicine'])
            ->findOrFail($id);
        // dd($purchase);

        return view('medicine_categories.purchase_medicine_pdf', compact('purchase'));
    }

    public function purchase_medicine_delete($id)
    {
        $purchase = PurchaseMedicine::findOrFail($id);
        $purchase->delete();

        return redirect()->route('admin.medicine.purchase.index')->with('success', 'Purchase medicine deleted successfully.');
    }

    // Edit Form Show karna
    public function bill_edit($id)
    {
        $patients = User::role('Patient')->select('id', 'name', 'email')->latest()->get();
        $bill = MedicineBill::with('saleMedicine.medicine')->findOrFail($id);
        // dd($bill);
        $medicineCategory = MedicineCategory::all(); // Dropdown ke liye

        return view('medicine_categories.bill_edit', compact('bill', 'medicineCategory', 'patients'));
    }

    public function bill_update(Request $request, $id)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'patient_select' => 'required',
            'bill_date' => 'required|date',
            'sale_medicines' => 'required|array|min:1',
            'sale_medicines.*.medicine_id' => 'required|exists:medicines,id',
            'sale_medicines.*.quantity' => 'required|numeric|min:0.1',
            'sale_medicines.*.unit' => 'required|in:strip,piece',
            'sale_medicines.*.price' => 'required|numeric',
            'sale_medicines.*.amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 0,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $bill = MedicineBill::with('saleMedicine')->findOrFail($id);

            // *** STEP 0: Capture Previous Payment State ***
            $prev_paid_cash = $bill->paid_cash;
            $prev_paid_online = $bill->paid_online;

            // --- STEP 1: REVERT OLD STOCK (Put items back into Batches) ---
            foreach ($bill->saleMedicine as $oldItem) {
                $medicine = Medicine::find($oldItem->medicine_id);

                if ($medicine) {
                    $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;

                    // Calculate pieces to return
                    $returnPieces = 0;
                    if ($oldItem->unit_type == 'strip') {
                        $returnPieces = $oldItem->quantity * $stripSize;
                    } else {
                        $returnPieces = $oldItem->quantity;
                    }

                    // 1. Restore to Specific Batch (If Batch No exists)
                    if ($oldItem->lot_no) {
                        $batch = \App\Models\PurchasedMedicine::where('medicine_id', $oldItem->medicine_id)
                            ->where('lot_no', $oldItem->lot_no)
                            ->first();

                        if ($batch) {
                            $batch->increment('available_quantity', $returnPieces);
                        }
                    }

                    // 2. Restore to Main Stock
                    $medicine->increment('total_quantity', $returnPieces);

                    // 3. Update UI Cache
                    $medicine->refresh();
                    $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
                    $medicine->update(['available_quantity' => $newAvailableStrips]);
                }

                // Delete the old item record
                $oldItem->delete();
            }

            // --- STEP 2: DETERMINE PATIENT LOGIC ---
            $patient_id = null;
            $patient_name = $request->patient_select;
            $ipdPatientId = null;

            if (is_numeric($request->patient_select)) {
                $user = User::find($request->patient_select);
                if ($user) {
                    $patient_id = $user->id;
                    $patient_name = $user->name;

                    // Check Active IPD Admission
                    $activeIpd = IpdPatient::where('patient_id', $patient_id)
                        ->whereNull('discharge_date')
                        ->latest()
                        ->first();

                    if ($activeIpd) {
                        $ipdPatientId = $activeIpd->id;
                    }
                }
            }

            // --- STEP 3: CALCULATE NET AMOUNT & STATUS ---
            $subTotal = floatval($request->total ?? 0);
            $taxAmount = floatval($request->tax_amount ?? 0);
            $discount = floatval($request->discount ?? 0);
            $exactNetAmount = round($subTotal + $taxAmount - $discount, 2);
            $netAmount = $exactNetAmount;

            // Calculate Payment Status
            $totalPaidNow = round(floatval($request->paid_cash ?? 0) + floatval($request->paid_online ?? 0), 2);
            $paymentStatus = 'unpaid';

            if ($totalPaidNow >= ($netAmount - 0.05)) {  // 0.05 tolerance for float precision
                $paymentStatus = 'paid';
            } elseif ($totalPaidNow > 0) {
                $paymentStatus = 'partial';
            }

            // Update Bill Header
            $bill->update([
                'patient_id' => $patient_id,
                'ipd_patient_id' => $ipdPatientId,
                'patient_name' => $patient_name,
                'bill_date' => $request->bill_date,
                'discount' => $request->discount ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'total' => $request->total,
                'net_amount' => $netAmount, // Rounded
                'paid_cash' => $request->paid_cash ?? 0,
                'paid_online' => $request->paid_online ?? 0,
                'online_payment_type' => $request->online_payment_type,
                'payment_status' => $paymentStatus,
                'note' => $request->note,
            ]);

            // --- STEP 3.5: MERGE DUPLICATE MEDICINES (Prevent duplicate entries for same medicine) ---
            $mergedMedicines = [];
            foreach ($request->sale_medicines as $item) {
                if (empty($item['medicine_id']))
                    continue;

                $medId = $item['medicine_id'];
                if (!isset($mergedMedicines[$medId])) {
                    $mergedMedicines[$medId] = $item;
                } else {
                    // Medicine already exists - combine quantities
                    $mergedMedicines[$medId]['quantity'] += $item['quantity'];
                    // Recalculate amount
                    $price = floatval($mergedMedicines[$medId]['price']);
                    $qty = floatval($mergedMedicines[$medId]['quantity']);
                    $discPercent = floatval($mergedMedicines[$medId]['discount_percent'] ?? 0);
                    $taxRate = floatval($mergedMedicines[$medId]['tax'] ?? 0);

                    $base = $price * $qty;
                    $discAmt = $base * $discPercent / 100;
                    $taxable = $base - $discAmt;
                    $taxAmt = $taxable * $taxRate / 100;
                    $mergedMedicines[$medId]['amount'] = $taxable + $taxAmt;
                }
            }

            // --- STEP 4: ADD NEW ITEMS (FEFO + NEGATIVE STOCK LOGIC) ---
            foreach ($mergedMedicines as $item) {
                if (empty($item['medicine_id']))
                    continue;

                $medicine = Medicine::findOrFail($item['medicine_id']);
                $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;

                // Calculate Needed Pieces
                $qty_needed_pieces = 0;
                if ($item['unit'] == 'strip') {
                    $qty_needed_pieces = $item['quantity'] * $stripSize;
                } else {
                    $qty_needed_pieces = $item['quantity'];
                }

                // Logic A: Consume from Batches (FEFO)
                $batches = \App\Models\PurchasedMedicine::where('medicine_id', $item['medicine_id'])
                    ->where('available_quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qty_needed_pieces <= 0)
                        break;

                    $take_pieces = min($qty_needed_pieces, $batch->available_quantity);

                    // Convert back to unit
                    $recorded_qty = ($item['unit'] == 'strip') ? ($take_pieces / $stripSize) : $take_pieces;

                    SaleMedicine::create([
                        'medicine_bill_id' => $bill->id,
                        'medicine_id' => $item['medicine_id'],
                        'lot_no' => $batch->lot_no,
                        'expiry_date' => $batch->expiry_date,
                        'quantity' => $recorded_qty,
                        'unit_type' => $item['unit'],
                        'price' => $item['price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'amount' => ($recorded_qty * $item['price']),
                    ]);

                    $batch->decrement('available_quantity', $take_pieces);
                    $medicine->decrement('total_quantity', $take_pieces);
                    $qty_needed_pieces -= $take_pieces;
                }

                // Logic B: Handle Remaining (NEGATIVE STOCK - ALLOWED)
                if ($qty_needed_pieces > 0) {
                    $recorded_qty = ($item['unit'] == 'strip') ? ($qty_needed_pieces / $stripSize) : $qty_needed_pieces;

                    // ✅ NEGATIVE SALE: Get expiry_date from the oldest batch
                    // This ensures we track the negative sale against an existing batch's expiry
                    $oldestBatch = \App\Models\PurchasedMedicine::where('medicine_id', $item['medicine_id'])
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $negativeExpiryDate = $oldestBatch ? $oldestBatch->expiry_date : now()->addYear();
                    $negativeLotNo = $oldestBatch ? $oldestBatch->lot_no : 'NEGATIVE_' . date('YmdHis');

                    // Record negative sale with existing batch's expiry date
                    SaleMedicine::create([
                        'medicine_bill_id' => $bill->id,
                        'medicine_id' => $item['medicine_id'],
                        'lot_no' => $negativeLotNo,
                        'expiry_date' => $negativeExpiryDate,
                        'quantity' => $recorded_qty,
                        'unit_type' => $item['unit'],
                        'price' => $item['price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'amount' => ($recorded_qty * $item['price']),
                    ]);

                    // Deduct from Main Stock (ALLOWED TO GO NEGATIVE)
                    $medicine->decrement('total_quantity', $qty_needed_pieces);
                }

                // Update UI Cache
                $medicine->refresh();
                $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
                $medicine->update(['available_quantity' => $newAvailableStrips]);
            }

            // --- STEP 5: RECORD PAYMENT HISTORY (DIFFERENCE LOGIC) ---
            $new_paid_cash = $request->paid_cash ?? 0;
            $new_paid_online = $request->paid_online ?? 0;

            // Cash Difference
            if ($new_paid_cash > $prev_paid_cash) {
                MedicineBillPayment::create([
                    'medicine_bill_id' => $bill->id,
                    'ipd_patient_id' => $ipdPatientId, // FIXED: Link to IPD
                    'payment_date' => now(),
                    'amount' => $new_paid_cash - $prev_paid_cash,
                    'payment_mode' => 'Cash',
                    'note' => 'Update Bill: Additional Payment'
                ]);
            }

            // Online Difference
            if ($new_paid_online > $prev_paid_online) {
                MedicineBillPayment::create([
                    'medicine_bill_id' => $bill->id,
                    'ipd_patient_id' => $ipdPatientId, // FIXED: Link to IPD
                    'payment_date' => now(),
                    'amount' => $new_paid_online - $prev_paid_online,
                    'payment_mode' => $request->online_payment_type ?? 'Online',
                    'note' => 'Update Bill: Additional Payment'
                ]);
            }

            DB::commit();

            return response()->json([
                'status_code' => 1,
                'status' => 'success',
                'message' => 'Bill updated successfully & payment recorded',
                'redirect_to' => route('admin.medicine.bill.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status_code' => 0,
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // --- 1. SHOW DUE BILLS LIST ---
    public function due_bills_index(Request $request)
    {
        // Search Filter
        $search = $request->input('search', '');

        // Query: Fetch bills where (paid_cash + paid_online) < net_amount
        $query = MedicineBill::whereRaw('(paid_cash + paid_online + discount) < net_amount');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%$search%")
                    ->orWhere('patient_name', 'like', "%$search%");
            });
        }

        $due_bills = $query->orderBy('bill_date', 'desc')->paginate(10);

        return view('medicine_categories.due_bills', compact('due_bills', 'search'));
    }

    // --- 2. SAVE DUE PAYMENT ---
    public function pay_due_save(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|exists:medicine_bills,id',
            'pay_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:Cash,Online,Discount',
        ]);

        try {
            $bill = MedicineBill::findOrFail($request->bill_id);

            // Calculate Current Due
            $totalPaid = $bill->paid_cash + $bill->paid_online;
            $dueAmount = $bill->net_amount - $totalPaid;

            // if ($request->pay_amount > $dueAmount) {
            //     return response()->json(['status' => 0, 'message' => 'Amount cannot be greater than due amount!']);
            // }

            // 1. Create Entry in Payment History Table
            $payment = MedicineBillPayment::create([
                'medicine_bill_id' => $bill->id,
                'ipd_patient_id' => $bill->ipd_patient_id,
                'payment_date' => now(),
                'amount' => $request->pay_amount,
                'payment_mode' => $request->payment_mode,
                'note' => $request->note ?? 'Due Clearance',
            ]);
            // dd($payment);

            // 2. Update Main Bill Aggregates
            if ($request->payment_mode == 'Cash') {
                $bill->increment('paid_cash', $request->pay_amount);
            } elseif ($request->payment_mode == 'Online') {
                $bill->increment('paid_online', $request->pay_amount);
            } elseif ($request->payment_mode == 'Discount') {
                $bill->increment('discount', $request->pay_amount);
                // Removed: $bill->decrement('net_amount', $request->pay_amount);
            }

            // 3. Update Status
            $bill->refresh();
            // Total Settled = Real Money (Cash/Online) + Waiver (Discount)
            $totalSettled = $bill->paid_cash + $bill->paid_online + $bill->discount;

            if ($totalSettled >= $bill->net_amount - 0.1) {
                $bill->update(['payment_status' => 'paid']);
            } else {
                $bill->update(['payment_status' => 'partial']);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Payment successful!',
                // Yahan hum Payment Receipt ka URL bhej rahe hain
                'print_url' => route('admin.medicine.payment.print', $payment->id)
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }


    }
    public function print_payment_receipt($id)
    {
        // IPD Patient aur Bill dono ko load karein
        $payment = MedicineBillPayment::with(['bill', 'ipdPatient.patient'])->findOrFail($id);

        $billTotal = 0;
        $balanceDue = 0;
        $isAccountPayment = false;

        // CASE 1: Agar ye payment kisi Specific Bill ka hai
        if ($payment->medicine_bill_id && $payment->bill) {
            $billTotal = $payment->bill->net_amount;

            // Calculate Remaining Due for THAT bill
            $totalPaidTillNow = $payment->bill->payments()->where('id', '<=', $id)->sum('amount');
            $balanceDue = $billTotal - $totalPaidTillNow;
        }
        // CASE 2: Agar ye General Account Payment hai (IPD Ledger)
        else {
            $isAccountPayment = true;
            $ipdPatient = $payment->ipdPatient;

            if ($ipdPatient) {
                // Total Account Calculation
                $totalBill = $ipdPatient->medicineBills->sum('net_amount');
                $totalPaid = $ipdPatient->pharmacyPayments->sum('amount');

                $billTotal = $totalBill; // Total Liability
                $balanceDue = $totalBill - $totalPaid; // Net Balance (Due/Advance)
            }
        }

        return view('medicine_categories.print_payment', compact('payment', 'balanceDue', 'billTotal', 'isAccountPayment'));
    }

    public function getPaymentHistory($id)
    {
        // Bill ke saath uske saare payments load karein
        $bill = MedicineBill::with([
            'payments' => function ($q) {
                $q->orderBy('payment_date', 'desc');
            }
        ])->findOrFail($id);

        // Data format karke bhejein taaki JS me asani ho
        $history = $bill->payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'date' => Carbon::parse($payment->payment_date)->format('d-M-Y h:i A'),
                'amount' => number_format($payment->amount, 2),
                'mode' => $payment->payment_mode,
                'note' => $payment->note ?? '-',
                'print_url' => route('admin.medicine.payment.print', $payment->id) // Print Route
            ];
        });

        return response()->json([
            'status' => 1,
            'bill_no' => $bill->bill_number,
            'data' => $history
        ]);

    }



    // 1. IPD Patient Search Page
    public function ipd_search_index(Request $request)
    {
        $search = $request->input('search');
        $patients = collect();

        if ($search) {
            // Search in IPD Patients table (by IPD No or Patient Name)
            $patients = IpdPatient::with('patient')
                ->where('ipd_no', 'like', "%$search%")
                ->orWhereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                })
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('medicine_categories.ipd_search', compact('patients', 'search'));
    }

    // 2. New Bill Form (Pre-filled for IPD Patient)
    public function bill_add_for_ipd($ipd_id)
    {
        $ipdPatient = IpdPatient::with('patient', 'doctor')->findOrFail($ipd_id);
        $medicineCategory = MedicineCategory::all();

        // Return a specific view for IPD Billing
        return view('medicine_categories.add_bill_ipd', compact('ipdPatient', 'medicineCategory'));
    }

    // --- 1. IPD PHARMACY PROFILE (DASHBOARD) ---
    // public function ipd_pharmacy_profile($id)
    // {
    //     $ipdPatient = IpdPatient::with(['patient', 'medicineBills', 'pharmacyPayments'])->findOrFail($id);

    //     // 1. Total Bill (Sabhi bills ka jod)
    //     $total_bill_amount = $ipdPatient->medicineBills->sum('net_amount');

    //     // 2. Total Paid (Sabhi payments ka jod)
    //     $total_paid = $ipdPatient->pharmacyPayments->sum('amount');

    //     // 3. Balance Calculation
    //     $net_balance = $total_bill_amount - $total_paid;
    //     // Note: Agar $net_balance (+) hai to Due hai, (-) hai to Advance hai.

    //     return view('medicine_categories.ipd_pharmacy_profile', compact('ipdPatient', 'total_bill_amount', 'total_paid', 'net_balance'));
    // }

    public function ipd_pharmacy_profile($id)
    {
        $ipdPatient = IpdPatient::with(['patient', 'medicineBills', 'pharmacyPayments'])->findOrFail($id);

        // 1. Total Bill (Value of all invoices)
        $total_bill_amount = $ipdPatient->medicineBills->sum('net_amount');

        // 2. Total Paid (Real money received)
        $total_paid = $ipdPatient->pharmacyPayments
            ->filter(fn($p) => strtolower($p->payment_mode) !== 'discount')
            ->sum('amount');

        // 3. Total Settlement Discount (Waivers recorded in payment history)
        $total_discount_given = $ipdPatient->pharmacyPayments
            ->filter(fn($p) => strtolower($p->payment_mode) === 'discount')
            ->sum('amount');

        // 4. Balance Calculation
        // This formula is safe for both OLD and NEW records:
        // Old records have smaller net_amount and 0 settlement discounts.
        // New records have larger net_amount and recorded settlement discounts.
        $net_balance = $total_bill_amount - ($total_paid + $total_discount_given);
        $total_medicine_cost = $total_bill_amount;

        return view('medicine_categories.ipd_pharmacy_profile', compact('ipdPatient', 'total_bill_amount', 'total_paid', 'total_discount_given', 'net_balance', 'total_medicine_cost'));
    }

    // --- 2. SAVE ACCOUNT PAYMENT (Unlimited Amount) ---
    // public function save_account_payment(Request $request)
    // {
    //     // dd($request->all()); // Debugging hata dein
    //     $request->validate([
    //         'ipd_id' => 'required',
    //         'amount' => 'required|numeric|min:1',
    //         'payment_mode' => 'required'
    //     ]);

    //     // 1. Payment Record Save Karein (Audit ke liye ki paisa aaya hai)
    //     $payment = MedicineBillPayment::create([
    //         'ipd_patient_id' => $request->ipd_id,
    //         'medicine_bill_id' => null, // Specific bill ID nahi hai
    //         'payment_date' => now(),
    //         'amount' => $request->amount,
    //         'payment_mode' => $request->payment_mode,
    //         'note' => $request->note ?? 'Account Payment ',
    //     ]);

    //     // --- SETTLEMENT LOGIC START ---

    //     // 2. Patient ke wo bills nikalein jo abhi tak poore pay nahi hue hain (Oldest First)
    //     // Hum bills ko ID (ya date) se sort karenge taaki purana udhaar pehle chuke.
    //     $unpaidBills = MedicineBill::where('ipd_patient_id', $request->ipd_id)
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     $remainingAmount = $request->amount; // Jitna paisa haath mein hai distribute karne ke liye

    //     foreach ($unpaidBills as $bill) {

    //         // Agar paisa khatam ho gaya, to loop rok dein
    //         if ($remainingAmount <= 0) {
    //             break;
    //         }

    //         // Bill ka Due amount calculate karein
    //         $paidSoFar = $bill->paid_cash + $bill->paid_online;
    //         $billDue = $bill->net_amount - $paidSoFar;

    //         // Agar bill already paid hai (due 0 ya negative), to skip karein
    //         if ($billDue <= 0.1) {
    //             continue;
    //         }

    //         // 3. Kitna pay karna hai decide karein
    //         // Ya to pura due clear hoga, ya jitna paisa bacha hai utna hi pay hoga
    //         $amountToPay = min($remainingAmount, $billDue);

    //         // 4. Bill Table Update Karein
    //         if ($request->payment_mode == 'Online') {
    //             $bill->paid_online += $amountToPay;
    //         } else {
    //             // Cash ya anya mode ko Cash column me daal rahe hain (ya logic change kar sakte hain)
    //             $bill->paid_cash += $amountToPay;
    //         }



    //         $bill->save();

    //         // 5. Remaining amount kam karein
    //         $remainingAmount -= $amountToPay;
    //     }

    //     // --- SETTLEMENT LOGIC END ---

    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Payment Received & Bills Settled Successfully!',
    //         'print_url' => route('admin.medicine.payment.print', $payment->id)
    //     ]);
    // }

    public function save_account_payment(Request $request)
    {
        // 1. Validation (Added allowed values for payment_mode)
        $request->validate([
            'ipd_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:Cash,Online,Discount'
        ]);

        // 2. Payment Record Save (Audit Log)
        $payment = MedicineBillPayment::create([
            'ipd_patient_id' => $request->ipd_id,
            'medicine_bill_id' => null, // Specific bill ID nahi hai (Account Payment)
            'payment_date' => now(),
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'note' => $request->note ?? 'Account Payment (Settlement)',
        ]);

        // --- SETTLEMENT LOGIC START ---

        // 3. Fetch Unpaid Bills (Oldest First)
        $unpaidBills = MedicineBill::where('ipd_patient_id', $request->ipd_id)
            ->orderBy('id', 'asc')
            ->get();

        $remainingAmount = $request->amount; // Amount available to distribute

        foreach ($unpaidBills as $bill) {

            // Agar paisa khatam ho gaya, to loop rok dein
            if ($remainingAmount <= 0.01) { // Floating point safety
                break;
            }

            // Bill ka Due amount calculate karein
            $paidSoFar = $bill->paid_cash + $bill->paid_online + $bill->discount;
            $billDue = $bill->net_amount - $paidSoFar;

            // Agar bill already paid hai, to skip karein
            if ($billDue <= 0.1) {
                continue;
            }

            // 4. Kitna settle karna hai decide karein
            $amountToPay = min($remainingAmount, $billDue);

            // 5. Bill Table Update Karein (Based on Payment Mode)
            if ($request->payment_mode == 'Online') {
                // Online Payment: Increase Paid Online
                $bill->increment('paid_online', $amountToPay);

            } elseif ($request->payment_mode == 'Discount') {
                // [UPDATED] Discount: Increase Discount Offset (Waiver)
                $bill->increment('discount', $amountToPay);
                // Removed: $bill->decrement('net_amount', $amountToPay);

            } else {
                // Cash (Default): Increase Paid Cash
                $bill->increment('paid_cash', $amountToPay);
            }

            // 6. Update Payment Status (Paid/Partial)
            $bill->refresh();
            // Total Settled = Money Received + Discounts/Waivers Given
            $totalSettledNow = $bill->paid_cash + $bill->paid_online + $bill->discount;

            if ($totalSettledNow >= $bill->net_amount - 0.1) {
                $bill->payment_status = 'paid';
            } else {
                $bill->payment_status = 'partial';
            }

            $bill->save();

            // 7. Remaining amount kam karein
            $remainingAmount -= $amountToPay;
        }

        // --- SETTLEMENT LOGIC END ---

        return response()->json([
            'status' => 1,
            'message' => 'Payment Received & Bills Settled Successfully!',
            'print_url' => route('admin.medicine.payment.print', $payment->id)
        ]);
    }

    // --- 3. PRINT CONSOLIDATED BILL (FINAL BILL) ---
    public function print_consolidated_bill($ipd_id)
    {
        $ipdPatient = IpdPatient::with(['patient', 'medicineBills.saleMedicine.medicine', 'pharmacyPayments'])
            ->findOrFail($ipd_id);

        // Medicines List
        $medicines = collect();
        foreach ($ipdPatient->medicineBills as $bill) {
            foreach ($bill->saleMedicine as $item) {
                $medicines->push([
                    'date' => $bill->bill_date,
                    'bill_no' => $bill->bill_number,
                    'name' => $item->medicine->medicine,
                    'qty' => $item->quantity,
                    'unit' => $item->unit_type,
                    'price' => $item->price,
                    'amount' => $item->amount
                ]);
            }
        }

        // 1. Total Bill
        $totalBill = $ipdPatient->medicineBills->sum('net_amount');

        // 2. Separate Actual Payments & Discounts
        $actualPayments = $ipdPatient->pharmacyPayments->filter(function ($payment) {
            return strtolower($payment->payment_mode) !== 'discount';
        });

        $discountRecords = $ipdPatient->pharmacyPayments->filter(function ($payment) {
            return strtolower($payment->payment_mode) === 'discount';
        });

        // 3. Calculate Totals
        $totalPaid = $actualPayments->sum('amount');
        $totalDiscount = $discountRecords->sum('amount');

        // 4. Final Balance = Total Invoice - (Actual Payments + Waiver/Discounts)
        $balance = $totalBill - ($totalPaid + $totalDiscount);

        return view('medicine_categories.print_consolidated', compact(
            'ipdPatient',
            'medicines',
            'totalBill',
            'totalPaid',
            'totalDiscount',
            'balance',
            'actualPayments',
            'discountRecords'
        ));
    }

    public function delete_account_payment($id)
    {
        $payment = \App\Models\MedicineBillPayment::findOrFail($id);
        $ipdPatientId = $payment->ipd_patient_id;

        $payment->delete();

        // Recalculate settlements
        $this->recalculateIpdPharmacySettlements($ipdPatientId);

        return response()->json([
            'status' => 1,
            'message' => 'Payment deleted successfully, and bills have been recalculated!'
        ]);
    }

    public function update_account_payment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:Cash,Online,Discount'
        ]);

        $payment = \App\Models\MedicineBillPayment::findOrFail($id);
        $ipdPatientId = $payment->ipd_patient_id;

        $payment->update([
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'note' => $request->note ?? 'Account Payment (Updated)',
        ]);

        // Recalculate settlements
        $this->recalculateIpdPharmacySettlements($ipdPatientId);

        return response()->json([
            'status' => 1,
            'message' => 'Payment updated successfully, and bills have been recalculated!'
        ]);
    }

    private function recalculateIpdPharmacySettlements($ipdPatientId)
    {
        // 1. Reset all bills for this IPD patient to unpaid/0 paid
        $bills = \App\Models\MedicineBill::where('ipd_patient_id', $ipdPatientId)->get();
        foreach ($bills as $bill) {
            $bill->paid_cash = 0;
            $bill->paid_online = 0;
            $bill->discount = 0;
            $bill->payment_status = 'unpaid';
            $bill->save();
        }

        // 2. Fetch all payments for this IPD patient in chronological order
        $payments = \App\Models\MedicineBillPayment::where('ipd_patient_id', $ipdPatientId)
            ->orderBy('id', 'asc')
            ->get();

        // 3. Re-distribute payments
        foreach ($payments as $payment) {
            $remainingAmount = $payment->amount;

            foreach ($bills as $bill) {
                if ($remainingAmount <= 0.01) {
                    break;
                }

                $paidSoFar = $bill->paid_cash + $bill->paid_online + $bill->discount;
                $billDue = $bill->net_amount - $paidSoFar;

                if ($billDue <= 0.1) {
                    continue;
                }

                $amountToPay = min($remainingAmount, $billDue);

                if ($payment->payment_mode == 'Online') {
                    $bill->increment('paid_online', $amountToPay);
                } elseif ($payment->payment_mode == 'Discount') {
                    $bill->increment('discount', $amountToPay);
                } else {
                    $bill->increment('paid_cash', $amountToPay);
                }

                $bill->refresh();
                $totalSettledNow = $bill->paid_cash + $bill->paid_online + $bill->discount;

                if ($totalSettledNow >= $bill->net_amount - 0.1) {
                    $bill->payment_status = 'paid';
                } else {
                    $bill->payment_status = 'partial';
                }
                $bill->save();

                $remainingAmount -= $amountToPay;
            }
        }
    }
}