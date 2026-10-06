<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\DoctorDepartment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $appointments = Appointment::with('doctor', 'patient', 'department')->paginate($perPage);
        return view('appointment.appointment', compact('appointments'));
    }
    public function add_appointment()
    {
        $departments = DoctorDepartment::select('id', 'department_name')->get();
        $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
        $patients = User::role('Patient')->select('id', 'name', 'email')->get();
        return view('appointment.new-appointment', compact('doctors', 'patients', 'departments'));
    }

    public function edit_appointment($id)
    {
        $appointment = Appointment::where('id', $id)->first();
        if ($appointment) {
            $doctors = User::role('Doctor')->select('id', 'name', 'email')->get();
            $patients = User::role('Patient')->select('id', 'name', 'email')->get();
            $departments = DoctorDepartment::select('id', 'department_name')->get();
            return view('appointment.edit-appointment', compact('appointment', 'id', 'patients', 'doctors', 'departments'));
        } else {
            return redirect()->back();
        }
    }

    public function appointment_save(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'patient' => 'required|exists:users,id|unique:appointments,patient_id',
                'doctor' => 'required|exists:users,id',
                'doctor_department' => 'required|exists:doctor_department,id',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'status' => 'required|boolean',
                'can_you_extend' => 'required|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'msg' => 'Some validation errors occurred!',
                    'errors' => $validator->errors(),
                    'status' => 422
                ]);
            }

            // Start a database transaction
            DB::beginTransaction();

            // Create  Appointment record
            Appointment::create(
                [
                    'patient_id' => $request->patient,
                    'doctor_id' => $request->doctor,
                    'doctor_department_id' => $request->doctor_department,
                    'date' => $request->date,
                    'description' => $request->description,
                    'date_of_birth' => $request->date_of_birth,
                    'status' => $request->status,
                    'can_you_extend' => $request->can_you_extend,
                ]
            );
            DB::commit();
            $redirect_to = route('admin.appointment.index');
            return response()->json([
                'msg' => 'Appointment Record Saved Successfully!',
                'redirect_to' => $redirect_to,
                'status' => 200
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'msg' => 'An error occurred while saving the Appointment record!',
                'errors' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function edit_appointment_save(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'patient' => "required|exists:users,id|unique:appointments,patient_id,$id,id",
                'doctor' => "required|exists:users,id",
                'doctor_department' => 'required|exists:doctor_department,id',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'status' => 'required|boolean',
                'can_you_extend' => 'required|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'msg' => 'Some validation errors occurred!',
                    'errors' => $validator->errors(),
                    'status' => 422
                ]);
            }

            // Start a database transaction
            DB::beginTransaction();

            // update  Appointment record
            $appointment = Appointment::findOrFail($id);
            $appointment->update(
                [
                    'patient_id' => $request->patient,
                    'doctor_id' => $request->doctor,
                    'doctor_department_id' => $request->doctor_department,
                    'date' => $request->date,
                    'description' => $request->description,
                    'date_of_birth' => $request->date_of_birth,
                    'status' => $request->status,
                    'can_you_extend' => $request->can_you_extend,
                ]
            );
            DB::commit();
            $redirect_to = route('admin.appointment.index');
            return response()->json([
                'msg' => 'Appointment Record Updated Successfully!',
                'redirect_to' => $redirect_to,
                'status' => 200
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'msg' => 'An error occurred while updating the Appointment record!',
                'errors' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }


 
}
