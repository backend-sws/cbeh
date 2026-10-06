<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\AddCharge;
use App\Models\OpdPatient;
use App\Models\OpdPayment;
use App\Models\PaymentCollection;
use App\Events\PaymentCollectionEvent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OpdPaymentController extends Controller
{
    public function list($id)
    {
        // $id is opd_no
        $opdPatient = OpdPatient::where('opd_no', $id)->first();
        $payments = OpdPayment::with('payment_mode')->where('opd_no', $id)->get();
        
        $result = [];
        if ($opdPatient && $opdPatient->final_amount > 0) {
            $result[] = [
                'id' => 0,
                'amount' => $opdPatient->final_amount,
                'payment_date' => $opdPatient->appointment_date ? Carbon::parse($opdPatient->appointment_date)->format('Y-m-d') : now()->format('Y-m-d'),
                'payment_mode' => ['name' => $opdPatient->payment_mode ?? 'Cash'],
                'notes' => 'OPD Consultation Fee (At Registration)',
                'document' => null,
            ];
        }
        foreach ($payments as $payment) {
            $result[] = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'payment_mode' => $payment->payment_mode ? ['name' => $payment->payment_mode->name] : ['name' => 'Cash'],
                'notes' => $payment->notes,
                'document' => $payment->document,
            ];
        }

        return response()->json($result);
    }

    public function billcharge($id)
    {
        // $id is opd_no
        $opdPatient = OpdPatient::where('opd_no', $id)->first();
        $otherChargesSum = AddCharge::where('ipd_no', $id)->get()->sum(function ($c) {
            return $c->quantity * $c->applied_charge;
        });

        $consultantCharge = $opdPatient ? (float)$opdPatient->standard_charge : 0;
        $totalCharges = $consultantCharge + $otherChargesSum;

        $payments = OpdPayment::with('payment_mode')->where('opd_no', $id)->get();
        $chargesList = AddCharge::with(['chargeCategory', 'chargeType'])->where('ipd_no', $id)->get();

        $formattedPayments = [];
        if ($opdPatient && $opdPatient->final_amount > 0) {
            $formattedPayments[] = [
                'id' => 0,
                'amount' => $opdPatient->final_amount,
                'payment_date' => $opdPatient->appointment_date ? Carbon::parse($opdPatient->appointment_date)->format('Y-m-d') : now()->format('Y-m-d'),
                'payment_mode' => ['name' => $opdPatient->payment_mode ?? 'Cash'],
                'notes' => 'OPD Consultation Fee',
            ];
        }
        foreach ($payments as $payment) {
            $formattedPayments[] = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'payment_mode' => $payment->payment_mode ? ['name' => $payment->payment_mode->name] : ['name' => 'Cash'],
                'notes' => $payment->notes,
                'document' => $payment->document,
            ];
        }

        return response()->json([
            'total_other_charges' => $totalCharges,
            'consultant_charge' => $consultantCharge,
            'payments' => $formattedPayments,
            'charges' => $chargesList,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'payment_date' => 'required|date',
                'payment_mode_id' => 'required|exists:payment_modes,id',
                'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
                'notes' => 'nullable|string',
            ]);

            $data = $request->only([
                'amount',
                'patient_id',
                'opd_no',
                'payment_date',
                'payment_mode_id',
                'notes'
            ]);

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('doc'), $imageName);
                $data['document'] = $imageName;
            }

            $opdPayment = OpdPayment::create($data);

            if ($request->payment_mode_id != 6) {
                event(new PaymentCollectionEvent($opdPayment, 'opd Payment', 1));
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment',
                'error' => $e->getMessage(),
                'status_code' => 500
            ], 500);
        }
    }

    public function deletecharge($id)
    {
        try {
            if ($id == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Initial consultation fee payment cannot be deleted from here.',
                    'status_code' => 0
                ], 400);
            }

            $payment = OpdPayment::findOrFail($id);

            if ($payment->document) {
                $filePath = public_path('doc/' . $payment->document);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage(),
                'status_code' => 500
            ], 500);
        }
    }

    public function pdf(Request $request, $id)
    {
        // $id is opd_no
        $opd_patient = OpdPatient::with('doctor', 'patient')->where('opd_no', $id)->firstOrFail();
        $invoice = 'INV-' . strtoupper(Str::random(10));
        
        $patient_uhid = DB::table('patients')
            ->where('user_id', $opd_patient->patient_id)
            ->value('patient_unique_id');

        $charges = AddCharge::with(['chargeCategory', 'chargeType'])->where('ipd_no', $id)->get();

        $Totalcharge = (float)$opd_patient->standard_charge + $charges->sum(function ($c) {
            return $c->quantity * $c->applied_charge;
        });

        $payments = OpdPayment::with('payment_mode')->where('opd_no', $id)->get();
        
        // Include initial payment in total calculations
        $initialPayment = (float)$opd_patient->final_amount;
        $TotalPayment = $initialPayment + $payments->sum('amount');
        $grossTotal = $Totalcharge;
        $netPayable = $grossTotal - $TotalPayment;
        $bill_date = now();

        return view('opd.opd-billing-pdf', compact(
            'charges',
            'opd_patient',
            'payments',
            'Totalcharge',
            'TotalPayment',
            'grossTotal',
            'netPayable',
            'bill_date',
            'patient_uhid',
            'invoice',
            'initialPayment'
        ));
    }

    public function pdfPerDay(Request $request, $id)
    {
        // $id is opd_no
        $opd_patient = OpdPatient::with('doctor', 'patient')->where('opd_no', $id)->firstOrFail();
        $invoice = 'INV-' . strtoupper(Str::random(10));
        $selectedDate = $request->query('date', Carbon::now()->format('Y-m-d'));
        $carbonDate = Carbon::parse($selectedDate);

        $patient_uhid = DB::table('patients')
            ->where('user_id', $opd_patient->patient_id)
            ->value('patient_unique_id');

        $charges = AddCharge::with(['chargeCategory', 'chargeType'])
            ->where('ipd_no', $id)
            ->whereDate('date', $selectedDate)
            ->get();

        $Totalcharge = $charges->sum(function ($c) {
            return $c->quantity * $c->applied_charge;
        });

        // If selected date matches registration/appointment date, include standard charge
        $appDate = $opd_patient->appointment_date ? Carbon::parse($opd_patient->appointment_date)->format('Y-m-d') : Carbon::parse($opd_patient->created_at)->format('Y-m-d');
        $includeConsultantCharge = ($selectedDate === $appDate);
        if ($includeConsultantCharge) {
            $Totalcharge += (float)$opd_patient->standard_charge;
        }

        $payments = OpdPayment::with('payment_mode')
            ->where('opd_no', $id)
            ->whereDate('payment_date', $selectedDate)
            ->get();

        $initialPayment = 0;
        if ($includeConsultantCharge) {
            $initialPayment = (float)$opd_patient->final_amount;
        }

        $TotalPayment = $initialPayment + $payments->sum('amount');
        $grossTotal = $Totalcharge;
        $netPayable = $grossTotal - $TotalPayment;
        $bill_date = $carbonDate;

        return view('opd.opd-billing-per-day-pdf', compact(
            'charges',
            'selectedDate',
            'opd_patient',
            'payments',
            'Totalcharge',
            'TotalPayment',
            'grossTotal',
            'netPayable',
            'bill_date',
            'patient_uhid',
            'invoice',
            'includeConsultantCharge',
            'initialPayment'
        ));
    }
}
