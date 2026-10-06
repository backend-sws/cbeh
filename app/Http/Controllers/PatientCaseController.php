<?php
namespace App\Http\Controllers;

use App\Models\PatientCase;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class PatientCaseController extends Controller
{
    public function index()
    {
        $cases = PatientCase::with(['patients', 'doctors'])->paginate(10);
      
        return view('patient_cases.index', compact('cases'));
    }

    public function create()
    {
        $patient = Role::where('name', "Patient")->first();
        $doctor = Role::where('name', "Doctor")->first();
        
        $doctors = $doctor->users()
                    ->get()
                    ->map(fn ($d) => ['id' => $d->id, 'label' => $d->full_name]);

        $patients = $patient->users()
                    ->get()
                    ->map(fn ($p) => ['id' => $p->id, 'label' => $p->full_name]);
                    
        return view('patient_cases.create',compact('doctors','patients'));
    }

    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'fee' => 'required|numeric',
            'phone' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return show_validation_error($validator,true);
        }
        $validated = $validator->validate();

        $validated['case_id'] = PatientCase::generateUniqueCaseId();
        $validated['currency_symbol'] = '₹';

        PatientCase::create($validated);

        $redirect_to=route('patient-cases.index');
        return response()->json([
            'message' => 'Patient case created successfully.',
            'redirect_to'=>$redirect_to,
            'status' => 'success',
            'status_code' => 1
        ]);

        // return redirect()->route('patient-cases.index')->with('success', 'Patient case created successfully.');
    }

    public function show(PatientCase $patientCase)
    {
        return view('patient_cases.show', compact('patientCase'));
    }

    public function edit(PatientCase $patientCase)
    {
        return view('patient_cases.edit', compact('patientCase'));
    }

    public function update(Request $request, PatientCase $patientCase)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'fee' => 'required|numeric',
            'phone' => 'nullable|numeric',  
            'description' => 'nullable|string',
        ]);

        $patientCase->update($validated);

        return redirect()->back()->with('success', 'Patient case updated successfully.');
    }

    public function destroy(PatientCase $patientCase)
    {
        $patientCase->delete();
        return redirect()->back()->with('success', 'Patient case deleted successfully.');
    }

    public function getCases($id)
    {
        $cases = PatientCase::where('patient_id', $id)->get(['id', 'case_id','description']);
        
        return response()->json($cases);
    }

}
