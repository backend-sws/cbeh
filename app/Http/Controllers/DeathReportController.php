<?php

namespace App\Http\Controllers;

use App\Models\IpdPatient;
use App\Models\DeathReport;
use Illuminate\Http\Request;

class DeathReportController extends Controller
{
    // 1. List
    public function index(Request $request)
    {
        $query = DeathReport::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('patient_name', 'like', "%$search%")
                  ->orWhere('reg_no', 'like', "%$search%");
        }

        $reports = $query->orderBy('id', 'desc')->paginate(10);
        return view('death_report.index', compact('reports'));
    }

    // 2. Create Form
    public function create()
    {
        $patients = IpdPatient::with('patient')->orderBy('id', 'desc')->get();
        return view('death_report.create', compact('patients'));
    }

    // 3. Store Data
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string',
            'death_date' => 'required|date',
            'death_time' => 'required',
            'cause_of_death' => 'required|string',
            'gender' => 'required',
        ]);

        // Generate ID like DCB000001
        $lastReport = DeathReport::orderBy('id', 'desc')->first();
        $nextId = $lastReport ? $lastReport->id + 1 : 1;
        $regNo = 'DCB' . str_pad($nextId, 7, '0', STR_PAD_LEFT);

        DeathReport::create([
            'ipd_patient_id' => $request->ipd_patient_id,
            'reg_no' => $regNo,
            'patient_name' => $request->patient_name,
            'gender' => $request->gender,
            'death_date' => $request->death_date,
            'death_time' => $request->death_time,
            'cause_of_death' => $request->cause_of_death,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.death_report.index')->with('success', 'Death Certificate Generated Successfully');
    }

    // 4. Print
    public function print($id)
    {
        $death = DeathReport::findOrFail($id);
        return view('death_report.print', compact('death'));
    }
}
