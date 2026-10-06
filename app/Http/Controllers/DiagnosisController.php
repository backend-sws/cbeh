<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use App\Models\DiagnosisTest;
use App\Models\DiagnosisCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DiagnosisController extends Controller
{
    public function diagnosis_test_index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $query = DiagnosisTest::with(['patient', 'doctor', 'diagnosisCategory']);
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('doctor', function ($q3) use ($search) {
                    $q3->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        $diagnosisTest = $query->paginate($perPage);
        return view('prescription.diagnosis_test', compact('diagnosisTest'));
    }

    public function diagnosis_test_add(Request $request)
    {
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        $patients = User::role('Patient')->select('id', 'name', 'email')->get();
        $diagnosis_categories = DiagnosisCategory::select('id', 'diagnosis_category')->get();

        return view('prescription.new_patient_diagnosis_test', compact('doctors', 'patients', 'diagnosis_categories'));
    }

    public function diagnosis_test_edit(Request $request, $id)
    {
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        $patients = User::role('Patient')->select('id', 'name', 'email')->get();
        $diagnosis_categories = DiagnosisCategory::select('id', 'diagnosis_category')->get();
        $diagnosisTest = DiagnosisTest::with(['patient', 'doctor', 'diagnosisCategory'])->where('id', $id)->firstOrFail();
        return view('prescription.edit_patient_diagnosis_test', compact('doctors', 'patients', 'diagnosis_categories', 'id', 'diagnosisTest'));
    }
    public function diagnosis_test_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient' => 'required|exists:users,id',
            'doctor' => 'required|exists:users,id',
            'diagnosis_category' => 'required|exists:diagnosis_categories,id',
            'report_number' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:100',
            'height' => 'required|string|max:50',
            'weight' => 'required|string|max:50',
            'average_glucose' => 'required|string|max:50',
            'fasting_blood_sugar' => 'required|string|max:50',
            'urine_sugar' => 'required|string|max:50',
            'blood_pressure' => 'required|string|max:50',
            'diabetes' => 'required|string|max:50',
            'cholesterol' => 'required|string|max:50',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status' => 422
            ]);
        }

        try {
            DiagnosisTest::create([
                'patient_id' => $request->patient,
                'doctor_id' => $request->doctor,
                'diagnosis_category_id' => $request->diagnosis_category,
                'report_number' => $request->report_number,
                'age' => $request->age,
                'height' => $request->height,
                'weight' => $request->weight,
                'average_glucose' => $request->average_glucose,
                'fasting_blood_sugar' => $request->fasting_blood_sugar,
                'urine_sugar' => $request->urine_sugar,
                'blood_pressure' => $request->blood_pressure,
                'diabetes' => $request->diabetes,
                'cholesterol' => $request->cholesterol,
            ]);
            $redirect_to = route('admin.diagnosis.diagnosis_test.index');
            return response()->json([
                'msg' => 'Diagnosis test saved successfully !',
                'redirect_to' => $redirect_to,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'An error occurred while saving the diagnosis test',
                'errors' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
    public function diagnosis_test_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'patient' => 'required|exists:users,id',
            'doctor' => 'required|exists:users,id',
            'diagnosis_category' => 'required|exists:diagnosis_categories,id',
            'report_number' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:100',
            'height' => 'required|string|max:50',
            'weight' => 'required|string|max:50',
            'average_glucose' => 'required|string|max:50',
            'fasting_blood_sugar' => 'required|string|max:50',
            'urine_sugar' => 'required|string|max:50',
            'blood_pressure' => 'required|string|max:50',
            'diabetes' => 'required|string|max:50',
            'cholesterol' => 'required|string|max:50',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status' => 422
            ]);
        }

        try {
            DiagnosisTest::where('id', $id)->update([
                'patient_id' => $request->patient,
                'doctor_id' => $request->doctor,
                'diagnosis_category_id' => $request->diagnosis_category,
                'report_number' => $request->report_number,
                'age' => $request->age,
                'height' => $request->height,
                'weight' => $request->weight,
                'average_glucose' => $request->average_glucose,
                'fasting_blood_sugar' => $request->fasting_blood_sugar,
                'urine_sugar' => $request->urine_sugar,
                'blood_pressure' => $request->blood_pressure,
                'diabetes' => $request->diabetes,
                'cholesterol' => $request->cholesterol,
            ]);
            $redirect_to = route('admin.diagnosis.diagnosis_test.index');
            return response()->json([
                'msg' => 'Diagnosis test updated successfully !',
                'redirect_to' => $redirect_to,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'An error occurred while saving the diagnosis test',
                'errors' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function diagnosis_test_delete(Request $request, $id)
    {
        try {
            $diagnosisTest = DiagnosisTest::findOrFail($id);
            $diagnosisTest->delete();
            return redirect()->back()->with('success', 'Diagnosis test record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the diagnosis test.')
                ->withInput();
        }
    }


    public function diagnosis_category_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = DiagnosisCategory::query();
        $query = $query->where('diagnosis_category', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $diagnosisCategory = $query ?? collect();
        return view('prescription.diagnosis_category', compact('diagnosisCategory'));
    }

    public function diagnosis_category_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'diagnosis_category' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            DiagnosisCategory::create([
                'diagnosis_category' => $request->diagnosis_category,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'Diagnosis category record created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the group.')
                ->withInput();
        }
    }

    public function diagnosis_category_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'diagnosis_category' => 'required|string|max:255',
                'description' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            DiagnosisCategory::where('id', $id)->update([
                'diagnosis_category' => $request->diagnosis_category,
                'description' => $request->description,
            ]);

            return redirect()->back()->with('success', 'Diagnosis category record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the diagnosis category.')
                ->withInput();
        }
    }

    public function diagnosis_category_delete(Request $request, $id)
    {
        try {
            $diagnosisCategory = DiagnosisCategory::findOrFail($id);
            $diagnosisCategory->delete();
            return redirect()->back()->with('success', 'Diagnosis category record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the diagnosis category.')
                ->withInput();
        }
    }

    public function Diagnosisbypatient(Request $request, $id)
    {
$diagnosis = DiagnosisTest::with('diagnosisCategory')->where('patient_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $diagnosis
        ]);
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'opd_patient_id' => 'required|exists:opd_patients,id',
            'report_type' => 'required|string|max:255',
            'report_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
            'description' => 'nullable|string',
        ]);
        // Validate the request data
        // Ensure the opd_patient_id exists in the opd_patients table
        // Ensure the report_type is a string and has a maximum length of 255 characters
        // Ensure the report_date is a valid date
        // Ensure the document is a file and can be of specific types (pdf, jpg, jpeg, png, doc, docx)
        // Ensure the description is a string and can be nullable
        // Store the document if it exists
        // Store the diagnosis data in the database
        if (!$request->has('opd_patient_id') || !$request->has('report_type') || !$request->has('report_date')) {
            return redirect()->back()->with('error', 'Required fields are missing.');
        }
        if (!$request->hasFile('document') && !$request->has('description')) {
            return redirect()->back()->with('error', 'At least one of document or description must be provided.');
        }
        // Create an array with the validated data

        $data = $request->only(['opd_patient_id', 'report_type', 'report_date', 'description']);
        // dd($data);
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = uniqid('diagnosis_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('diagnosis'), $filename);
            $data['document'] = 'diagnosis/' . $filename;
        }
        Diagnosis::create($data);

        return redirect()->back()->with('success', 'Diagnosis added successfully!');
    }


   public function destroy($id)
{
    $diagnosis = Diagnosis::findOrFail($id);

    // Delete the document file if exists (from public/diagnosis)
    if ($diagnosis->document && file_exists(public_path($diagnosis->document))) {
        unlink(public_path($diagnosis->document));
    }

    $diagnosis->delete();

    return redirect()->back()->with('success', 'Diagnosis deleted successfully!');
}


    public function update(Request $request, $id)
    {
        $diagnosis = Diagnosis::findOrFail($id);

        $request->validate([
            'report_type' => 'required|string|max:255',
            'report_date' => 'required|date',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $diagnosis->report_type = $request->report_type;
        $diagnosis->report_date = $request->report_date;
        $diagnosis->description = $request->description;

        if ($request->hasFile('document')) {
            // Delete old file if exists
            if ($diagnosis->document && file_exists(public_path($diagnosis->document))) {
                unlink(public_path($diagnosis->document));
            }
            $file = $request->file('document');
            $filename = uniqid('diagnosis_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('diagnosis'), $filename);
            $diagnosis->document = 'diagnosis/' . $filename;
        }

        $diagnosis->save();

        return redirect()->back()->with('success', 'Diagnosis updated successfully!');
    }
}
