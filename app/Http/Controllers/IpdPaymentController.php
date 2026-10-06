<?php

namespace App\Http\Controllers;

use App\Models\BedType;
use App\Models\Patient;
use App\Models\Opration;
use App\Models\AddCharge;
use App\Models\BedAssign;
use App\Models\Operation;
use App\Models\Addmission;
use App\Models\IpdPatient;
use App\Models\IpdPayment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PaymentCollection;
use Faker\Provider\ar_EG\Payment;
use App\Events\PaymentCollectionEvent;
use Illuminate\Support\Facades\Storage;



class IpdPaymentController extends Controller
{
    public function list($id)
    {
        $types = IpdPayment::with('payment_mode')->where('ipd_no', $id)->get();
        return response()->json($types);
    }
    public function billcharge($id)
    {
        // Sum of other charges from AddCharge
        $Totalcharge = AddCharge::where('ipd_no', $id)->sum('total');

        // Get bed assignments from today onward with their related bed data
        $bedAssignments = BedAssign::with('bed')
            ->where('ipd_no', $id)->first();

        // Manually sum up bed charges from the related beds
        $totalDays = 1;
        $assignedDate = Carbon::parse($bedAssignments->assigned_date);
        if ($bedAssignments->discharged_date) {
            $totalDays = ceil($assignedDate->diffInDays(Carbon::parse($bedAssignments->discharged_date)));
        } else {
            $totalDays = ceil($assignedDate->diffInDays(Carbon::now()));
        }

        $BedAssignCharge = $bedAssignments->bed->charge * $totalDays;

        // Fetch all payment entries with related payment modes
        $types = IpdPayment::with('payment_mode')
            ->where('ipd_no', $id)
            ->get();

        // Fetch all individual charges
        $chargesList = AddCharge::where('ipd_no', $id)->get();

        return response()->json([
            'total_other_charges' => $Totalcharge,
            'bed_charges_from_today' => $BedAssignCharge,
            'payments' => $types,
            'charges' => $chargesList
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([

                'amount' => 'required|numeric|min:0',
                'payment_date' => 'required|date',
                'payment_mode_id' => 'required|exists:payment_modes,id',
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
                'notes' => 'nullable|string',
            ]);

            $data = $request->only([
                'amount',
                'patient_id',
                'ipd_no',
                'payment_date',
                'payment_mode_id',
                'notes'
            ]);



            // IPD Patient ko find karein using ipd_no
            $patientStatus = IpdPatient::where('ipd_no', $request->ipd_no)->first();

            // Check karein agar patient exist karta hai aur discharged hai
            // Yahan 'discharged' aapke database column ka naam hai. 
            // Agar aap 'status' column use karte hain to: $patientStatus->status == 'Discharged' likhein
            if ($patientStatus && $patientStatus->discharge_date == !null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient is already Discharged. Payment cannot be added.',
                    'status_code' => 0
                ], 422);
            }
            $imageName = null;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); // Generate a unique file name
                $file->move(public_path('doc'), $imageName); // Move file to 'public/avatar' directory
                $data['document'] = $imageName;
            }


            $ipdPayment = IpdPayment::create($data);


            // Dispatch event here
              if ($request->payment_mode_id != 6) {
              
   

                // Dispatch event here
                event(new PaymentCollectionEvent($ipdPayment, 'ipd Payment', $department_type = 3));

            }


            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'redirect_to' => url()->previous(), // or route('your.route.name')
                'status_code' => 1
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment',
                'error' => $e,
                'status_code' => 500
            ], 500);
        }
    }

    public function deletecharge($id)
    {

        try {
            $payment = IpdPayment::findOrFail($id);

            // Delete associated file if exists
            if ($payment->document) {
                $filePath = public_path('doc/' . $payment->document);
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }

            $collectionEntry = PaymentCollection::where('department_type', 3) // 3 = IPD
                ->where('patient_id', $payment->patient_id) // Same Patient
                ->where('amount', $payment->amount) // Same Amount
                ->whereDate('created_at', $payment->created_at->format('Y-m-d')) // Optional: Same Date match karein taaki galat delete na ho
                ->orderBy('id', 'desc') // Latest entry pehle uthayenge
                ->first();

            if ($collectionEntry) {
                $collectionEntry->delete();
            }


            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage(), // Only send error message for security
                'status_code' => 500
            ], 500);
        }
    }
    public function pdf(Request $request, $id)
    {

        // dd($request->all());

        $invoice = 'INV-' . strtoupper(Str::random(10)); // e.g., INV-A8F9XZ3P2D

        $charges = AddCharge::with(['chargeCategory', 'chargeType'])->where('ipd_no', $id)->get();
        // Get the IPD patient data

        $ipd_patient = IpdPatient::with('doctor', 'patient', 'discharge')->where('ipd_no', $id)->first();
        $UHID = Patient::select('patient_unique_id')->where('user_id', $ipd_patient->patient->id)->first();
        $age = $ipd_patient->patient->age; // e.g., '1990-06-15'


        // Sum of other charges
        $Totalcharge = AddCharge::where('ipd_no', $id)
            ->get()
            ->sum(function ($c) {
                return $c->quantity * $c->applied_charge;
            });


        $TotalPayment = IpdPayment::where('ipd_no', $id)->sum('amount');


        $beds = BedAssign::with('bed', 'bed.bedType')
            ->where('ipd_no', $id)
            ->first();

        //$wards = BedType::where('patient_id', $id)->first();
        $totalDays = 1;
        $assignedDate = Carbon::parse($beds->assigned_date);
        if ($beds->discharged_date) {
            $totalDays = ceil($assignedDate->diffInDays(Carbon::parse($beds->discharged_date)));
        } else {
            $totalDays = ceil($assignedDate->diffInDays(Carbon::now()));
        }


        // Sum up bed charges from related beds
        $BedAssignCharge = $beds->bed->charge * $totalDays;

        // Get all payments with payment mode
        $payments = IpdPayment::with('payment_mode')
            ->where('ipd_no', $id)
            ->get();
        $grossTotal = $Totalcharge;
        // Generate PDF
        // $discount = $request->query('discount', 0);
        //    dd($request->all(), $discount);
        // $tax = $request->query('tax', 0);
        // $otherCharges = $request->query('otherCharge', 0);
        $finalTotal = $request->query('finalTotal', 0);
        if ($finalTotal > 0) {
            $netPayable = $finalTotal - $TotalPayment;
        } else {
            $netPayable = $grossTotal - $TotalPayment;
        }
        // Or: $grossTotal - $TotalPayment + $otherCharges + tax - discount

        $bill_date = now();
        // $discountby = $request->get('discountBy');

        $surgery = Operation::with('operation_name')->where('patient_id', $ipd_patient->patient->id)->first();
        $admission = Addmission::where('patient_id', $ipd_patient->patient->id)->first();

        // $pdf = Pdf::loadView('ipd.Ipd-billing-pdf', compact(
        //     'charges',
        //     'ipd_patient',
        //     'payments',
        //     'Totalcharge',
        //     'BedAssignCharge',
        //     'TotalPayment',
        //     'grossTotal',
        //     'discount',
        //     'tax',
        //     'otherCharges',
        //     'netPayable',
        //     'bill_date',
        //     'discountby',
        //     'beds',
        //     'age',
        //     'surgery',
        //     'admission',
        //     'UHID',
        //     'invoice'

        // ));

        // dd($Totalcharge, $BedAssignCharge, $grossTotal, $TotalPayment, $netPayable);
        return view('ipd.Ipd-billing-pdf', compact(
            'charges',
            'ipd_patient',
            'payments',
            'Totalcharge',
            'BedAssignCharge',
            'TotalPayment',
            'grossTotal',
            // 'discount',
            // 'tax',
            // 'otherCharges',
            'netPayable',
            'bill_date',
            // 'discountby',
            'beds',
            'age',
            'surgery',
            'admission',
            'UHID',
            'invoice'

        ));
    }

    public function pdfPerDay(Request $request, $id)
    {

        $invoice = 'INV-' . strtoupper(Str::random(10)); // e.g., INV-A8F9XZ3P2D
        $selectedDate = $request->query('date', Carbon::now()->format('Y-m-d'));
        $carbonDate = Carbon::parse($selectedDate);

        // Filter charges by selected date
        $charges = AddCharge::with(['chargeCategory', 'chargeType'])
            ->where('ipd_no', $id)
            ->whereDate('date', $selectedDate)
            ->get();

        // Get the IPD patient data
        $ipd_patient = IpdPatient::with('doctor', 'patient')->where('ipd_no', $id)->first();
        $UHID = Patient::select('patient_unique_id')->where('user_id', $ipd_patient->patient->id)->first();
        $age = $ipd_patient->patient->age; // e.g., '1990-06-15'


        // Sum of other charges
        $Totalcharge = $charges->sum(function ($c) {
                return $c->quantity * $c->applied_charge;
            });


        // Filter payments by selected date
        $payments = IpdPayment::with('payment_mode')
            ->where('ipd_no', $id)
            ->whereDate('payment_date', $selectedDate)
            ->get();

        $TotalPayment = $payments->sum('amount');


        $beds = BedAssign::with('bed', 'bed.bedType')
            ->where('ipd_no', $id)
            ->first();

        $BedAssignCharge = 0;
        if ($beds) {
            $assignedDate = Carbon::parse($beds->assigned_date)->startOfDay();
            $dischargeDate = $beds->discharged_date ? Carbon::parse($beds->discharged_date)->endOfDay() : null;
            $checkDate = Carbon::parse($selectedDate)->startOfDay();

            // Agar selected date admission aur discharge (agar ho) ke beech me hai
            if ($checkDate->greaterThanOrEqualTo($assignedDate) && 
                (!$dischargeDate || $checkDate->lessThanOrEqualTo($dischargeDate))) {
                $BedAssignCharge = $beds->bed->charge;
                $Totalcharge += $BedAssignCharge;
            }
        }

        $grossTotal = $Totalcharge;

        // For specific day, net payable is charges during that day minus payments during that day
        $netPayable = $grossTotal - $TotalPayment;

        $bill_date = $carbonDate;

        $surgery = Operation::with('operation_name')->where('patient_id', $ipd_patient->patient->id)->first();
        $admission = Addmission::where('patient_id', $ipd_patient->patient->id)->first();

        return view('ipd.Ipd-billing-per-day-pdf', compact(
            'charges',
            'selectedDate',
            'ipd_patient',
            'payments',
            'Totalcharge',
            'BedAssignCharge',
            'TotalPayment',
            'grossTotal',
            'netPayable',
            'bill_date',
            'beds',
            'age',
            'surgery',
            'admission',
            'UHID',
            'invoice'
        ));
    }
}
