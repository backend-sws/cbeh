<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Patient;
use App\Models\IpdPatient;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function user_list(Request $request, $name)
    {
        $currentUser = auth()->user();

        // If current user is not Admin, then only allow 'Patient' role
        if (!$currentUser->hasRole('Admin') && $name !== 'Patient') {
            abort(403, 'Unauthorized access.');
        }

        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $role = Role::where('name', $name)->first();
        // dd($role);
        $users = $role
            ? $role->users()->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate($perPage)
            : collect();
        return view('user-list', compact('users', 'name'));
    }
    public function add_user($name)
    {
        return view('admin.add-user', compact('name'));
    }

    public function add_user_save(Request $request, $name)
    {
        try {
            // ✅ Validation
            $validator = Validator::make($request->all(), [
                'email' => 'nullable|email|unique:users,email',

                'name' => 'required|string|max:255',
                // 'last_name' => 'required|string|max:255',
                'password' => 'nullable|min:8|confirmed', // now optional
                'gender' => 'required|string',
                'phone' => 'nullable|digits:10',
                'alternate_phone' => 'nullable|digits:10',
                'dob' => 'nullable|date',
                'age' => 'nullable|integer|min:0|max:150',
                'age_month' => 'nullable|integer|min:0|max:11',
                'guardian_name' => 'nullable|string|max:255',
                'blood_group' => 'nullable|string|max:10',
                'marital_status' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:1000',
                'alternate_address' => 'nullable|string|max:1000',
                'remarks' => 'nullable|string|max:1000',
                'allergies' => 'nullable|string|max:1000',
                'facebook_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'adhar_number' => 'nullable|string|max:12|min:12',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // ✅ Check valid role
            $role = Role::where(['name' => $name])->exists();
            if (!$role) {
                return redirect()->back()->with('error', 'Role is undefined, please change role !');
            }

            DB::beginTransaction();

            // ✅ Handle image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('avatar'), $imageName);
            }

            // ✅ Decide password
            $password = $request->password;
            if (empty($password)) {
                if (!empty($request->dob)) {
                    // Format DOB as ddmmyyyy
                    $password = date('dmY', strtotime($request->dob));
                } else {
                    // fallback if no DOB entered
                    $password = Str::random(10);
                }
            }

            if ($request->email) {
                $emailExists = User::where('email', $request->email)->exists();
                // dd($emailExists);
                if ($emailExists) {
                    // dd($emailExists);
                    return redirect()->back()->with('error', 'Email already exists, please use a different email !');
                }
            }

            // ✅ Create user
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                // 'first_name' => $request->first_name,
                // 'last_name' => $request->last_name,
                'role' => $name,
                'phone' => $request->phone,
                'alternate_phone' => $request->alternate_phone,
                'dob' => $request->dob,
                'age' => $request->age,
                'age_month' => $request->age_month,
                'guardian_name' => $request->guardian_name,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,
                'address' => $request->address,
                'alternate_address' => $request->alternate_address,
                'remarks' => $request->remarks,
                'allergies' => $request->allergies,
                'password' => bcrypt($password), // ✅ secure password
                'facebook_url' => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'twitter_url' => $request->twitter_url,
                'linkedin_url' => $request->linkedin_url,
                'image' => $imageName,
                'adhar_number' => $request->adhar_number
            ]);



            //  Assign role
            $user->assignRole($name);

            //  Special logic based on role
            if ($user->hasRole('Doctor')) {
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'specialization' => 'Cardiologist',
                        'degree' => 'MBBS, MD',
                        'experience_years' => 42,
                        'hospital_name' => 'Apollo Hospital, West Ham',
                        'consulting_fee' => '500',
                        'rating' => '5',
                    ]
                );
            } elseif ($user->hasRole('Patient')) {
                Patient::create([
                    'user_id' => $user->id,
                    'patient_unique_id' => strtoupper(Patient::generateUniquePatientId())
                ]);
            }

            DB::commit();

            // ✅ Redirect back
            $redirect_to = route('admin.users.user_list', $name);
            return redirect()->to($redirect_to)->with('success', 'New User Added Successfully !');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Some error Occurred ! ' . $e->getMessage());
        }
    }




    public function edit_user(Request $request, $name, $id)
    {
        $user = User::find($id);
        if (!$user) {
            session()->flash('message', 'User Not Found!');
            return redirect()->back();
        }
        return view('admin.edit-user', compact('name', 'id', 'user'));
    }

    public function edit_user_save(Request $request, $name, $id)
    {
        try {
            // Fetch user from the database
            $user = User::find($id);
            if (!$user) {
                session()->flash('message', 'User Not Found!');
                return redirect()->back();
            }

            // Define validation rules for updating user
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                // 'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'password' => 'nullable|min:8|confirmed',
                'gender' => 'required|string',
                'phone' => 'nullable|digits:10',
                'alternate_phone' => 'nullable|digits:10',
                'dob' => 'nullable|date',
                'age' => 'nullable|integer|min:0|max:150',
                'age_month' => 'nullable|integer|min:0|max:11',
                'guardian_name' => 'nullable|string|max:255',
                'blood_group' => 'nullable|string|max:10',
                'marital_status' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:1000',
                'alternate_address' => 'nullable|string|max:1000',
                'remarks' => 'nullable|string|max:1000',
                'allergies' => 'nullable|string|max:1000',
                'facebook_url' => 'nullable|url',
                'instagram_url' => 'nullable|url',
                'twitter_url' => 'nullable|url',
                'linkedin_url' => 'nullable|url',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'adhar_number' => 'nullable|digits:12'
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation errors occurred!', 'errors' => $validator->errors(), 'status' => 422]);
            }

            // check role is valid
            $role = Role::where(['name' => $name])->exists();
            if (!$role) {
                return response()->json(['errors' => 'Role is undefined,please change role !', 'status' => 500]);
            }

            DB::beginTransaction();

            // Handle image upload if a new file is provided
            $imageName = $user->image; // Keep the existing image by default
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($user->image && file_exists(public_path('avatar/' . $user->image))) {
                    unlink(public_path('avatar/' . $user->image));
                }
                // Upload the new image
                $file = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('avatar'), $imageName);
            }

            // dd($request->all());
            // Update user details in the database
            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                // 'first_name' => $request->first_name,
                // 'last_name' => $request->last_name,
                'role' => $name,
                'phone' => $request->phone,
                'alternate_phone' => $request->alternate_phone,
                'dob' => $request->dob,
                'age' => $request->age,
                'age_month' => $request->age_month,
                'guardian_name' => $request->guardian_name,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'marital_status' => $request->marital_status,
                'address' => $request->address,
                'alternate_address' => $request->alternate_address,
                'remarks' => $request->remarks,
                'allergies' => $request->allergies,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'facebook_url' => $request->facebook_url,
                'instagram_url' => $request->instagram_url,
                'twitter_url' => $request->twitter_url,
                'linkedin_url' => $request->linkedin_url,
                'image' => $imageName,
                'adhar_number' => $request->adhar_number
            ]);

            $user->assignRole($name);

            // create Doctor profile
            if ($user->hasRole('Doctor')) {
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'specialization' => 'Cardiologist',
                        'degree' => 'MBBS, MD',
                        'experience_years' => 42,
                        'hospital_name' => 'Apollo Hospital, West Ham',
                        'consulting_fee' => '500',
                        'rating' => '5',
                    ]
                );
            }

            DB::commit();
            $redirect_to = route('admin.users.user_list', $name);
            return response()->json(['msg' => 'User updated successfully!', 'redirect_to' => $redirect_to, 'status' => 200]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['msg' => 'An error occurred while updating the user.', 'errors' => $e->getMessage(), 'status' => 500]);
        }


    }

    public function delete_user($id)
    {
        try {
            $user = User::findOrFail($id);

            // Check if user has a doctor role
            if ($user->hasRole('Doctor')) {
                // Check for linked IPD patients
                $ipdPatientCount = IpdPatient::where('doctor_id', $user->id)->count();
                
                // Check for linked prescriptions (OPD/IPD)
                $prescriptionCount = Prescription::where('doctor_id', $user->id)->count();
                
                // Check for linked appointments
                $appointmentCount = Appointment::where('doctor_id', $user->id)->count();
                
                // If any linked records exist, prevent deletion
                if ($ipdPatientCount > 0 || $prescriptionCount > 0 || $appointmentCount > 0) {
                    return redirect()->back()->with('error', 
                        "Cannot delete! This doctor has {$ipdPatientCount} IPD patients, {$prescriptionCount} prescriptions, and {$appointmentCount} appointments."
                    );
                }
            }

            // Check if user has patient role and has linked records
            if ($user->hasRole('Patient')) {
                // Check for linked IPD admissions
                $ipdCount = IpdPatient::where('patient_id', $user->id)->count();
                
                // Check for linked appointments
                $appointmentCount = Appointment::where('patient_id', $user->id)->count();
                
                if ($ipdCount > 0 || $appointmentCount > 0) {
                    return redirect()->back()->with('error', 
                        "Cannot delete! This patient has {$ipdCount} IPD admissions and {$appointmentCount} appointments."
                    );
                }
            }

            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Delete failed! ' . $e->getMessage());
        }
    }
}