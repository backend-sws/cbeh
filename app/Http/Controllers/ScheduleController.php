<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ScheduleController extends Controller
{
    public function index(){
        $doctors=User::with('DoctorProfile')->role('Doctor')->get();
        return view('admin.schedule',compact('doctors'));
    }
    public function doctors_schedule(){
        $doctors=User::role('Doctor')->select('name','id')->get();
        return view('admin.doctor_schedule',compact('doctors'));
    }

    public function doctor_schedule_save(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'doctor' => 'required|exists:users,id', // Ensure the doctor exists in the users table
                'per_patient_time' => 'required|integer|min:1', // Validate the minimum patient time
                
                'monday_available_from' => 'nullable|required_with:monday_available_to',
                'monday_available_to' => 'nullable|required_with:monday_available_from',
                'tuesday_available_from' => 'nullable|required_with:tuesday_available_to',
                'tuesday_available_to' => 'nullable||required_with:tuesday_available_from',
                'wednesday_available_from' => 'nullable|required_with:wednesday_available_to',
                'wednesday_available_to' => 'nullable|required_with:wednesday_available_from',
                'thursday_available_from' => 'nullable|required_with:thursday_available_to',
                'thursday_available_to' => 'nullable|required_with:thursday_available_from',
                'friday_available_from' => 'nullable|required_with:friday_available_to',
                'friday_available_to' => 'nullable|required_with:friday_available_from',
                'saturday_available_from' => 'nullable|required_with:saturday_available_to',
                'saturday_available_to' => 'nullable|required_with:saturday_available_from',
                'sunday_available_from' => 'nullable|required_with:sunday_available_to',
                'sunday_available_to' => 'nullable|required_with:sunday_available_from',

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

            // Loop through the schedules and create or update them
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $request->doctor,
                    ],
                    [
                        'per_patient_time' => $request->per_patient_time,
                        
                        'monday_available_from' => $request->monday_available_from, 
                        'monday_available_to' => $request->monday_available_to, 
                        'tuesday_available_from' => $request->tuesday_available_from,
                        'tuesday_available_to' => $request->tuesday_available_to,
                        'wednesday_available_from' => $request->wednesday_available_from,
                        'wednesday_available_to' => $request->wednesday_available_to,
                        'thursday_available_from' => $request->thursday_available_from,
                        'thursday_available_to' => $request->thursday_available_to,
                        'friday_available_from' => $request->friday_available_from,
                        'friday_available_to' => $request->friday_available_to,
                        'saturday_available_from' => $request->saturday_available_from,
                        'saturday_available_to' => $request->saturday_available_to,
                        'sunday_available_from' => $request->sunday_available_from,
                        'sunday_available_to' => $request->sunday_available_to,
                    ]
                );

            // Commit the transaction
            DB::commit();

            return response()->json([
                'msg' => 'Doctor schedule saved successfully!',
                'status' => 200
            ]);
        } catch (\Throwable $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json([
                'msg' => 'An error occurred while saving the doctor schedule!',
                'errors' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function get_doctor_schedule(Request $request,$id){
            $doctor_schedule=DoctorSchedule::where('doctor_id',$id)->first();
            return response()->json([
                'msg' => 'Doctor schedule fetched successfully',
                'data'=>$doctor_schedule,       
                'status' => 200
            ]);
    }
}
