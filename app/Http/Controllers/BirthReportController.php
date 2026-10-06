<?php

namespace App\Http\Controllers;

use App\Models\IpdPatient;
use App\Models\BirthReport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BirthReportController extends Controller
{
    // 1. Show List of Generated Certificates
    public function index(Request $request)
    {
        $query = BirthReport::with('ipdPatient.patient');

        // 1. Text Search Logic
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reg_no', 'like', "%$search%")
                    ->orWhere('mother_name', 'like', "%$search%")
                    ->orWhere('father_name', 'like', "%$search%")
                    ->orWhere('child_name', 'like', "%$search%");
            });
        }

        // 2. Date Filter Logic
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('birth_date', $request->date);
        }

        $reports = $query->orderBy('id', 'desc')->paginate(10);

        return view('birth_report.index', compact('reports'));
    }

    // 2. Show Create Form
    public function create()
    {
        // Get all currently admitted or discharged patients
        $patients = IpdPatient::with('patient')->orderBy('id', 'desc')->get();
        return view('birth_report.create', compact('patients'));
    }

    // 3. Save Data
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'ipd_patient_id' => 'nullable|exists:ipd_patients,id',
            'birth_date' => 'required|date',
            'birth_time' => 'required',
            'gender' => 'required',
            'mother_name' => 'required|string',
            'father_name' => 'required|string',
        ]);

        // 2. Generate Custom Reg No (TH0000044 Format)

        // a. Last record nikalo
        $lastReport = BirthReport::orderBy('id', 'desc')->first();

        // b. Agar last record hai to uski ID + 1, nahi to 1 se shuru karo
        $nextId = $lastReport ? $lastReport->id + 1 : 1;

        // c. Format banao: CB + 7 digit padding (e.g., CB0000044)
        $regNo = 'CB' . str_pad($nextId, 7, '0', STR_PAD_LEFT);

        // 3. Create Record
        $report = BirthReport::create([
            'ipd_patient_id' => $request->ipd_patient_id,
            'reg_no' => $regNo, // <-- Updated Reg No here
            'child_name' => $request->child_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'birth_time' => $request->birth_time,
            'weight' => $request->weight,
            'mother_name' => $request->mother_name,
            'father_name' => $request->father_name,
            'address' => $request->address,
            'issue_date' => now(),
        ]);

        return redirect()->route('admin.birth_report.index')
            ->with('success', 'Birth Certificate Generated Successfully. Reg No: ' . $regNo);
    }
    // 4. Print Certificate
    public function print($id)
    {
        $birth = BirthReport::findOrFail($id);
        return view('birth_report.print', compact('birth'));
    }
}
