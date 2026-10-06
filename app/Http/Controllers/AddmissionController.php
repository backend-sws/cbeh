<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Addmission; // Make sure to import the Admission model
 // Assuming you have an Admission model
use App\Models\Bed; // Import the Bed model if needed
use App\Models\BedType; // Import the BedType model if needed
use App\Models\User; // Import the User model for patients and doctors
use App\Models\Patient;
use App\Models\Doctor; // Import the Doctor model

use Illuminate\Support\Facades\Validator;

class AddmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admissions = Addmission::with(['patient', 'doctor']) // Eager load patient and doctor with their user information
            ->orderBy('created_at', 'desc')
            ->get(); // Fetch all admissions with patient and doctor information
        return view('admissions.index', compact('admissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get(); // Fetch all patients with their user information
         $doctors=User::role('Doctor')->select('id','name','email')->get();
        $bedTypes = BedType::all(); // Fetch all bed types if needed
        $packages = [
            'General' => 'General',
            'Private' => 'Private',
            'Deluxe' => 'Deluxe',
            'Suite' => 'Suite',
        ];
        return view('admissions.create', compact('patients', 'doctors', 'bedTypes', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'admission_date' => 'required|date',
           
        ]);
$admission_no = 'AD-' . strtoupper(uniqid()); // Generate a unique admission number
        $request->merge(['admission_no' => $admission_no]); // Add admission_no to the request data
        if ($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Addmission::create($request->all());
        return redirect()->route('admin.admissions.index')->with('success', 'Admission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admission = Addmission::findOrFail($id);
        return view('admissions.show', compact('admission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admission = Addmission::findOrFail($id);
         $patients = Patient::with('user')->get(); // Fetch all patients with their user information
         $doctors=User::role('Doctor')->select('id','name','email')->get();
        $bedTypes = BedType::all(); // Fetch all bed types if needed
        $packages = [
            'General' => 'General',
            'Private' => 'Private',
            'Deluxe' => 'Deluxe',
            'Suite' => 'Suite',
        ];
$beds=Bed::all();
        return view('admissions.edit', compact('admission','patients','doctors','bedTypes','packages','beds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admission = Addmission::findOrFail($id);
        $admission->update($request->all());
        return redirect()->route('admin.admissions.index')->with('success', 'Admission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admission = Addmission::findOrFail($id);
        $admission->delete();
        return redirect()->route('admin.admissions.index')->with('success', 'Admission deleted successfully.');
    }
    public function toggleStatus($id)
{
    $admission = Addmission::findOrFail($id);
    $admission->status = $admission->status === 'active' ? 'inactive' : 'active';
    $admission->save();

    return back()->with('success', 'Status updated successfully.');
}

}

    

