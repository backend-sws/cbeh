<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\Patient;
use App\Models\Bed_assign;
use App\Models\BedAssign;
use App\Models\BedStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;


class BedController extends Controller
{

    public function bedStatus()
    {
        // Get all bed types with their beds and assigned beds with patient
        $bedTypes = BedType::with([
            'beds.bed_assigns' => function ($q) {
                $q->where('status', 'assigned')->with('patient'); // load patient
            }
        ])->get();

        // Mark availability for each bed
        foreach ($bedTypes as $type) {
            foreach ($type->beds as $bed) {
                $bed->is_available = $bed->bed_assigns->isEmpty();

                // Optionally, you can attach patient info directly for easier access in the view
                if (!$bed->is_available && $bed->bed_assigns->isNotEmpty()) {
                    $bed->assigned_patient = $bed->bed_assigns->first()->patient ?? null;
                }
            }
        }

        $totalBeds = $bedTypes->sum(fn($type) => $type->beds->count());
        $totalAllotted = $bedTypes->sum(fn($type) => $type->beds->where('is_available', false)->count());

        $today = now()->toDateString();
        $allottedToday = \App\Models\BedAssign::whereDate('assigned_date', $today)->count();
        $dischargedToday = \App\Models\BedAssign::whereDate('discharged_date', $today)->count();

        return view('bed.status', compact('bedTypes', 'totalBeds', 'totalAllotted', 'allottedToday', 'dischargedToday'));
    }

    public function newBedAssign()
    {
        $assigns = BedAssign::with(['patient', 'bed'])->get();
        $cases = DB::table('ipd_patients')
            ->select('id', 'patient_id', 'case', 'ipd_no')
            ->where('status', '=', 1)
            ->get();
        $beds = Bed::with('bedType')->get();
        return view('bed.newassign', compact('beds', 'assigns', 'cases'));
    }
    public function bedAssignById(Request $request, $id)
    {
        $selectedBedId = Bed::find($id);
        if ($selectedBedId) {
            $cases = DB::table('ipd_patients')
                ->select('id', 'patient_id', 'case', 'ipd_no')
                ->where('status', '=', 1)
                ->get();
            $beds = Bed::with('bedType')->get();
            return view('bed.newBedAssignbyid', compact('beds', 'cases', 'selectedBedId'));
        } else {
            return redirect()->back()->with('error', 'Bed assignment not found.');
        }
    }
    public function assignpost(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'bed_id' => 'required|exists:beds,id',
            'case' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $assign = new BedAssign();
        $assign->patient_id = $request->patient_id;
        $assign->bed_id = $request->bed_id;
        $assign->case = $request->case;
        $assign->ipd_no = $request->ipd_no;
        $assign->assigned_date = $request->assigned_date ?? now();

        $assign->save();

        return response()->json([
            'success' => true,
            'message' => 'Bed Assign successfully',
            'redirect_to' => url()->previous(),
            'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
        ]);
    }
    public function bedAssignEdit($id)
    {
        $assign = BedAssign::find($id);
        if ($assign) {
            $cases = DB::table('ipd_patients')
                ->select('id', 'patient_id', 'case', 'ipd_no')
                ->where('status', '=', 1)
                ->get();
            $beds = Bed::with('bedType')->get();
            return view('bed.newassignEdit', compact('beds', 'cases', 'assign'));
        } else {
            return redirect()->back();
        }
    }

    public function bedAssignUpdate(Request $request, $id)
    {


        $assign = BedAssign::find($id);
        if ($assign) {
            $assign->patient_id = $request->patient_id;
            $assign->bed_id = $request->bed_id;
            $assign->case = $request->case;
            $assign->ipd_no = $request->ipd_no;
            $assign->assigned_date = $request->assigned_date ?? now();
            $assign->discharged_date = $request->discharge_date;
            $assign->status = $request->status ?? 'assigned'; // Default to 'assigned' if not provided
            $assign->save();

            return response()->json([
                'success' => true,
                'message' => 'Bed Assign update successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Bed Assign update Fail',
                'redirect_to' => url()->previous(),
                'status_code' => 400 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);
        }
    }
    public function bedAssignDelete($id)
    {
        $assign = BedAssign::find($id);
        if ($assign) {
            $assign->delete();
            return redirect()->back()->with('success', 'Bed assignment deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Bed assignment not found.');
        }
    }

    function getpatient(Request $request)
    {
        $patientId = $request->input('patient_id');
        $patient = User::find($patientId);

        if ($patient) {
            return response()->json([
                'status' => 'success',
                'data' => $patient
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Patient not found.'
            ]);
        }
    }



    public function newBedAssignList()
    {
        $assigns = BedAssign::with(['patient', 'bed'])->orderBy('id', 'desc')->get();
        return view('bed.newassignlist', compact('assigns'));
    }

    public function beds()
    {
        $beds = Bed::with(['bedType', 'bed_assigns'])->get();
        $bedTypes = BedType::all();
        return view('bed.beds', compact('beds', 'bedTypes'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bed_type_id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => true,
                'message' => 'Validity FAil',
                'redirect_to' => url()->previous(),
                'status_code' => 400 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);
        }
        do {
            $bedNumber = Str::upper(Str::random(8)); // e.g., A8K3Z1F2
        } while (Bed::where('code', $bedNumber)->exists());

        $bed = new Bed();
        $bed->name = $request->name;
        $bed->code = $bedNumber;
        $bed->bed_type_id = $request->bed_type_id;
        $bed->charge = $request->charge ?? 0;
        $bed->description = $request->description ?? '';
        $bed->is_active = true;
        $bed->save();

        return response()->json([
            'success' => true,
            'message' => 'Bed Add successfully',
            'redirect_to' => url()->previous(),
            'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
        ]);
    }
    public function edit($id)
    {
        $bed = Bed::find($id);
        if ($bed) {
            return response()->json([
                'status' => 'success',
                'data' => $bed
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Bed not found.'
            ]);
        }
    }
    public function update(Request $request, $id)
    {




        $bed = Bed::find($id);
        if ($bed) {

            $bed->bed_type_id = $request->bedtype_id;
            $bed->name = $request->name;
            $bed->charge = $request->charge ?? 0;
            $bed->description = $request->description;
            $bed->save();

            return response()->json([
                'success' => true,
                'message' => 'Bed Edit successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);
        } else {
            return redirect()->back()->with('error', 'Bed not found.');
        }
    }
    public function delete($id)
    {
        $bed = Bed::find($id);
        if ($bed) {
            $bed->delete();
            return redirect()->back()->with('success', 'Bed deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Bed not found.');
        }
    }

    public function bulkBed()
    {
        return view('bed.bulkbed');
    }

    public function bedType()
    {
        $bedTypes = BedType::all();
        return view('bed.type', compact('bedTypes'));

    }
    public function bed_type_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bedType = new BedType();
        $bedType->name = $request->name;
        $bedType->description = $request->description;
        $bedType->save();

        return response()->json([
            'success' => true,
            'message' => 'Bed type Add successfully',
            'redirect_to' => url()->previous(),
            'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
        ]);
    }
    public function bed_type_delete($id)
    {
        $bedType = BedType::find($id);
        if ($bedType) {
            $bedType->delete();
            return redirect()->back()->with('success', 'Bed type deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Bed type not found.');
        }
    }

    public function bed_type_edit($id)
    {
        $bedType = BedType::find($id);
        if ($bedType) {
            return response()->json([
                'status' => 'success',
                'data' => $bedType
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Bed type not found.'
            ]);
        }
    }
    public function bed_type_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bedType = BedType::find($id);
        if ($bedType) {
            $bedType->name = $request->name;
            $bedType->description = $request->description;
            $bedType->save();

            return response()->json([
                'success' => true,
                'message' => 'Bed Type Update successfully',
                'redirect_to' => url()->previous(),
                'status_code' => 1 // Changed from 201 (Created) to 200 (OK) for delete operations
            ]);
        } else {
            return redirect()->back()->with('error', 'Bed type not found.');
        }
    }

    public function allbeds(Request $request, $bedtype_id)
    {
        $beds = Bed::with(['bedType', 'bed_assigns'])
            ->when($bedtype_id, function ($query) use ($bedtype_id) {
                return $query->where('bed_type_id', $bedtype_id);
            })
            ->whereDoesntHave('bed_assigns', function ($query) {
                $query->where('status', 'assigned');
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $beds
        ]);
    }




}
