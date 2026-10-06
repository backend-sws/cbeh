<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\OpdDoctorCharge;
use App\Models\User;
use Illuminate\Http\Request;


class DoctorChargeController extends Controller
{
    public function list()
    {
        $doctorcharges = OpdDoctorCharge::with('doctor')->get();
        //  $doctors=Doctor::all();
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        return view('hospitalCharge.doctorcharge', compact('doctorcharges', 'doctors'));
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
    'doctor_id' => 'required|numeric|unique:opd_doctor_charges,doctor_id',
    'charge' => 'required',
]);


            $data = $request->only([
                'doctor_id',
                'charge'
            ]);




            $doctorcharges = OpdDoctorCharge::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Add successfully',
                'redirect_to' => url()->previous(), // or route('your.route.name')
                'status_code' => 1
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record',
                'error' => $e,
                'status_code' => 500
            ], 500);
        }
    }
    public function edit($id)
    {
        $doctorcharge = OpdDoctorCharge::findOrFail($id);
        return response()->json(['data' => $doctorcharge]);
    }

    public function update(Request $request, $id)
    {
        try {
            $doctorcharge = OpdDoctorCharge::findOrFail($id);
            $doctorcharge->doctor_id = $request->doctor_id;
            $doctorcharge->charge = $request->charge;

            $doctorcharge->save();
            return response()->json([
                'success' => true,
                'message' => 'Update successfully',
                'redirect_to' => url()->previous(), // or route('your.route.name')
                'status_code' => 1
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record',
                'error' => $e,
                'status_code' => 500
            ], 500);
        }
    }
    public function deletecharge($id)
    {
        $doctorcharge = OpdDoctorCharge::findOrFail($id)->delete();
        return redirect()->back();
    }


}
