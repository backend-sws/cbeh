<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\BedAssign;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    // public function Dashboard(Request $request)
    // {
    //     $from = $request->input('from');
    //     $to = $request->input('to');

    //     $patientQuery = \App\Models\Patient::query();
    //     if ($from)
    //         $patientQuery->whereDate('created_at', '>=', $from);
    //     if ($to)
    //         $patientQuery->whereDate('created_at', '<=', $to);

    //     $paymentCollectionQuery = \App\Models\PaymentCollection::query();
    //     if ($from)
    //         $paymentCollectionQuery->whereDate('created_at', '>=', $from);
    //     if ($to)
    //         $paymentCollectionQuery->whereDate('created_at', '<=', $to);

    //     $data = [
    //         'totalRevenue' => $paymentCollectionQuery->sum('amount'),
    //         'opdCollection' => (clone $paymentCollectionQuery)->where('department_type', 1)->sum('amount'),
    //         'diagnosisCollection' => (clone $paymentCollectionQuery)->where('department_type', 2)->sum('amount'),
    //         'ipdCollection' => (clone $paymentCollectionQuery)->where('department_type', 3)->sum('amount'),
    //         'totalPatients' => $patientQuery->count(),
    //     ];

    //     $today = now()->toDateString();
    //     $data['todayRevenue'] = \App\Models\PaymentCollection::whereDate('created_at', $today)->sum('amount');
    //     $data['todayOpdCollection'] = \App\Models\PaymentCollection::where('department_type', 1)->whereDate('created_at', $today)->sum('amount');
    //     $data['todayDiagnosisCollection'] = \App\Models\PaymentCollection::where('department_type', 2)->whereDate('created_at', $today)->sum('amount');
    //     $data['todayIpdCollection'] = \App\Models\PaymentCollection::where('department_type', 3)->whereDate('created_at', $today)->sum('amount');
    //     $data['todayNewPatients'] = \App\Models\Patient::whereDate('created_at', $today)->count();
    //     $data['todayNewOpdPatients'] = \App\Models\OpdPatient::whereDate('created_at', $today)->count();
    //     $data['todayNewIpdPatients'] = \App\Models\IpdPatient::whereDate('created_at', $today)->count();

    //     // dd($data); // For debugging purposes, remove this line in production
    //     return view('admin.dashboard', $data);
    // }



// public function Dashboard(Request $request)
// {
//     $from = $request->input('from');
//     $to = $request->input('to');
//     $user = auth()->user();

//     $patientQuery = \App\Models\Patient::query();
//     if ($from) $patientQuery->whereDate('created_at', '>=', $from);
//     if ($to) $patientQuery->whereDate('created_at', '<=', $to);

//     $paymentCollectionQuery = \App\Models\PaymentCollection::query();

//     // Admin ko chhod ke, staff user ka data filter karo
//     if ($user->role != 'admin' && $user->role_id != 1) { // Adjust as per your role logic
//         $paymentCollectionQuery->where('user_id', $user->id);
//     }

//     if ($from) $paymentCollectionQuery->whereDate('created_at', '>=', $from);
//     if ($to) $paymentCollectionQuery->whereDate('created_at', '<=', $to);

//     $data = [
//         'totalRevenue' => $paymentCollectionQuery->sum('amount'),
//         'opdCollection' => (clone $paymentCollectionQuery)->where('department_type', 1)->sum('amount'),
//         'diagnosisCollection' => (clone $paymentCollectionQuery)->where('department_type', 2)->sum('amount'),
//         'ipdCollection' => (clone $paymentCollectionQuery)->where('department_type', 3)->sum('amount'),
//         'pathologyCollection' => (clone $paymentCollectionQuery)->where('department_type', 4)->sum('amount'),
//         'totalPatients' => $patientQuery->count(),
//     ];

//     $today = now()->toDateString();

//     $todayPaymentQuery = \App\Models\PaymentCollection::query();
//     if ($user->role != 'admin' && $user->role_id != 1) {
//         $todayPaymentQuery->where('user_id', $user->id);
//     }
//     $todayPaymentQuery->whereDate('created_at', $today);

//     $data['todayRevenue'] = $todayPaymentQuery->sum('amount');
//     $data['todayOpdCollection'] = (clone $todayPaymentQuery)->where('department_type', 1)->sum('amount');
//     $data['todayDiagnosisCollection'] = (clone $todayPaymentQuery)->where('department_type', 2)->sum('amount');
//     $data['todayIpdCollection'] = (clone $todayPaymentQuery)->where('department_type', 3)->sum('amount');
//     $data['todayPathologyCollection'] = (clone $todayPaymentQuery)->where('department_type', 4)->sum('amount');
//     $data['todayNewPatients'] = \App\Models\Patient::whereDate('created_at', $today)->count();
//     $data['todayNewOpdPatients'] = \App\Models\OpdPatient::whereDate('created_at', $today)->count();
//     $data['todayNewIpdPatients'] = \App\Models\IpdPatient::whereDate('created_at', $today)->count();

//     $data['totalPatients'] = \App\Models\Patient::all()->count();
//     $data['totalIpdPatients'] = \App\Models\IpdPatient::all()->count();
//     $data['totalopdPatients'] = \App\Models\OpdPatient::all()->count();
// $unassignedBeds = DB::table('beds')
//     ->whereNotIn('id', function ($query) {
//         $query->select('bed_id')
//               ->from('bed_assigns')
//               ->whereNull('discharged_date'); // optional: only exclude active assignments
//     })
//     ->get();

//     $data['available_bebs'] = $unassignedBeds->count();
//     $data['doctors'] = \App\Models\User::role('Doctor')->count();
//     $data['nurses'] = \App\Models\User::role('Nurse')->count();
//     $data['admins'] = \App\Models\User::role('Admin')->count();
//     $data['accountants'] = \App\Models\User::role('Accountant')->count();
//     $data['labTechnians'] = \App\Models\User::role('Lab Technician')->count();
//     $data['pharmacists'] = \App\Models\User::role('Pharmacist')->count();
//     $data['receptionists'] = \App\Models\User::role('Receptionist')->count();
  

//     return view('admin.dashboard', $data);
// }


public function Dashboard(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');
    $user = auth()->user();

    // 1. Patient Filtering
    $patientQuery = \App\Models\Patient::query();
    if ($from) $patientQuery->whereDate('created_at', '>=', $from);
    if ($to) $patientQuery->whereDate('created_at', '<=', $to);

    // 2. Hospital Payments (OPD, IPD, Path, etc.)
    $paymentCollectionQuery = \App\Models\PaymentCollection::query();
    
    // Role Check
    if ($user->role != 'Admin' && $user->role_id != 1) { 
        $paymentCollectionQuery->where('user_id', $user->id);
    }

    if ($from) $paymentCollectionQuery->whereDate('created_at', '>=', $from);
    if ($to) $paymentCollectionQuery->whereDate('created_at', '<=', $to);

    // 3. MEDICINE (PHARMACY) REVENUE & PROFIT
    // ---------------------------------------------------
    // FIXED: Medicine Revenue based on actual Payments (Transactions)
    $medicinePaymentQuery = \App\Models\MedicineBillPayment::query();
    if ($from) $medicinePaymentQuery->whereDate('payment_date', '>=', $from);
    if ($to) $medicinePaymentQuery->whereDate('payment_date', '<=', $to);

    $medicineRevenue = $medicinePaymentQuery->sum('amount');

    // Calculate Medicine Profit
    // Logic: Get all sold items within date range -> (Sale Price - Purchase Price) * Qty
    $medicineProfit = 0;
    
    // Eager load 'medicine' to avoid N+1 queries
    $soldMedicines = \App\Models\SaleMedicine::with('medicine')
        ->whereHas('bill', function($q) use ($from, $to) {
            if ($from) $q->whereDate('created_at', '>=', $from);
            if ($to) $q->whereDate('created_at', '<=', $to);
        })->get();

    foreach($soldMedicines as $item) {
        // Ensure 'purchase_price' exists in your 'medicines' table
        $buyPrice = $item->medicine->buying_price ?? 0; 
        $sellPrice = $item->amount; // Price stored in sale_medicines table

    
        // Calculate Profit
        $medicineProfit += ($sellPrice - $buyPrice) ;
    }

    // 4. PREPARE TOTALS DATA
    $hospitalRevenue = $paymentCollectionQuery->sum('amount');

    $data = [
        // Combine Hospital + Medicine for Grand Total
        'totalRevenue' => $hospitalRevenue + $medicineRevenue, 
        
        'opdCollection' => (clone $paymentCollectionQuery)->where('department_type', 1)->sum('amount'),
        'diagnosisCollection' => (clone $paymentCollectionQuery)->where('department_type', 2)->sum('amount'),
        'ipdCollection' => (clone $paymentCollectionQuery)->where('department_type', 3)->sum('amount'),
        'pathologyCollection' => (clone $paymentCollectionQuery)->where('department_type', 4)->sum('amount'),
        
        // Medicine Specific Data
        'medicineCollection' => $medicineRevenue,
        'medicineProfit' => $medicineProfit,

        'totalPatients' => $patientQuery->count(),
    ];

    // 5. TODAY'S DATA CALCULATION
    $today = now()->toDateString();

    // Hospital Today
    $todayPaymentQuery = \App\Models\PaymentCollection::query();
    if ($user->role != 'Admin' && $user->role_id != 1) {
        $todayPaymentQuery->where('user_id', $user->id);
    }
    $todayPaymentQuery->whereDate('created_at', $today);

    // Medicine Today (FIXED: Transaction based)
    $todayMedicineRevenue = \App\Models\MedicineBillPayment::whereDate('payment_date', $today)->sum('amount');
    
    // Medicine Today Profit
    $todayMedicineProfit = 0;
    $todaySoldItems = \App\Models\SaleMedicine::with('medicine')
        ->whereDate('created_at', $today)
        ->get();

    foreach($todaySoldItems as $item) {
        $buyPrice = $item->medicine->buying_price ?? 0;
        $todayMedicineProfit += ($item->amount - $buyPrice);
    }

    $data['todayRevenue'] = $todayPaymentQuery->sum('amount') + $todayMedicineRevenue; // Combined
    $data['todayOpdCollection'] = (clone $todayPaymentQuery)->where('department_type', 1)->sum('amount');
    $data['todayDiagnosisCollection'] = (clone $todayPaymentQuery)->where('department_type', 2)->sum('amount');
    $data['todayIpdCollection'] = (clone $todayPaymentQuery)->where('department_type', 3)->sum('amount');
    $data['todayPathologyCollection'] = (clone $todayPaymentQuery)->where('department_type', 4)->sum('amount');
    
    // Today Medicine Data
    $data['todayMedicineRevenue'] = $todayMedicineRevenue;
    $data['todayMedicineProfit'] = $todayMedicineProfit;

    // 6. COUNTS
    $data['todayNewPatients'] = \App\Models\Patient::whereDate('created_at', $today)->count();
    $data['todayNewOpdPatients'] = \App\Models\OpdPatient::whereDate('created_at', $today)->count();
    $data['todayNewIpdPatients'] = \App\Models\IpdPatient::whereDate('created_at', $today)->count();

    $data['totalPatients'] = \App\Models\Patient::all()->count();
    $data['totalIpdPatients'] = \App\Models\IpdPatient::all()->count();
    $data['totalopdPatients'] = \App\Models\OpdPatient::all()->count();

    // 7. RESOURCES
    $unassignedBeds = \DB::table('beds')
        ->whereNotIn('id', function ($query) {
            $query->select('bed_id')
                ->from('bed_assigns')
                ->whereNull('discharged_date');
        })
        ->get();

    $data['available_bebs'] = $unassignedBeds->count();
    $data['doctors'] = \App\Models\User::role('Doctor')->count();
    $data['nurses'] = \App\Models\User::role('Nurse')->count();
    $data['admins'] = \App\Models\User::role('Admin')->count();
    $data['accountants'] = \App\Models\User::role('Accountant')->count();
    $data['labTechnians'] = \App\Models\User::role('Lab Technician')->count();
    $data['pharmacists'] = \App\Models\User::role('Pharmacist')->count();
    $data['receptionists'] = \App\Models\User::role('Receptionist')->count();

    return view('admin.dashboard', $data);
}
}

