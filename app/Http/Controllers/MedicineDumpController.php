<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\MedicineStockDump;
use App\Models\PurchasedMedicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicineDumpController extends Controller
{
    // 1. Expired Batches List
    public function index()
    {
        $today = date('Y-m-d');

        // Logic: Wo batch nikalo jo expire ho gaye hain aur jisme STOCK (Pieces) bacha hai
        $expired_batches = PurchasedMedicine::with('medicine')
            ->where('expiry_date', '<', $today)
            ->where('available_quantity', '>', 0) // Change: Check available_quantity
            ->orderBy('expiry_date', 'asc')
            ->get();

        return view('medicine_dump.index', compact('expired_batches'));
    }

    // 2. Dump Action (Batch se stock hatana)
    public function store(Request $request)
    {
        $request->validate([
            'purchased_medicine_id' => 'required', // Batch ID
            'quantity' => 'required|numeric|min:0.1', // Quantity (usually in Strips/Units)
            'reason' => 'required'
        ]);

        // Specific Batch find karo
        $batch = PurchasedMedicine::findOrFail($request->purchased_medicine_id);
        
        // Parent Medicine find karo
        $medicine = Medicine::findOrFail($batch->medicine_id);

        // --- QUANTITY LOGIC (Strips to Pieces) ---
        // User hamesha Strip/Unit me input dalega, par DB me pieces hain
        $stripSize = ($medicine->strip_quantity > 0) ? $medicine->strip_quantity : 1;
        
        // Convert Dump Qty to Pieces
        $dump_pieces = $request->quantity * $stripSize;

        // Check Stock in Batch
        if ($batch->available_quantity < $dump_pieces) {
            return response()->json([
                'status' => 0, 
                'message' => 'Batch me itna stock nahi hai! Available Pieces: ' . $batch->available_quantity . 
                             ' (Approx ' . ($batch->available_quantity / $stripSize) . ' Strips)'
            ]);
        }

        DB::beginTransaction();

        try {
            // A. Loss Calculation
            // Batch ki purchase price usually per strip hoti hai
            $loss = $batch->purchase_price * $request->quantity; 

            // B. Dump Record Create
            MedicineStockDump::create([
                'medicine_id' => $medicine->id,
                'purchased_medicine_id' => $batch->id, // Track Batch ID optionally
                'quantity' => $request->quantity, // Record in Strips/Main Unit
                'dump_date' => now(),
                'reason' => $request->reason . ' (Batch: ' . $batch->lot_no . ')',
                'loss_amount' => $loss,
                'dumped_by' => Auth::id(),
                'note' => $request->note
            ]);

            // C. Batch se Pieces Minus karo
            $batch->decrement('available_quantity', $dump_pieces);

            // D. Main Medicine Table (Total Stock) se Pieces Minus karo
            $medicine->decrement('total_quantity', $dump_pieces);

            // E. UI Cached Field Update karo (Available Strips)
            $medicine->refresh();
            $newAvailableStrips = floor($medicine->total_quantity / $stripSize);
            $medicine->update(['available_quantity' => $newAvailableStrips]);

            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Stock Dumped Successfully & Inventory Updated!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        $query = MedicineStockDump::with('medicine');

        if ($request->from_date) {
            $query->whereDate('dump_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('dump_date', '<=', $request->to_date);
        }

        $dumps = $query->orderBy('dump_date', 'desc')->get();
        $total_loss = $dumps->sum('loss_amount');

        return view('medicine_dump.report', compact('dumps', 'total_loss'));
    }
}