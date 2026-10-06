<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;
use Spatie\Permission\Models\Role;

class UserWithRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Admin' => 'admin@example.com',
            'Doctor' => 'doctor@example.com',
            'Patient' => 'patient@example.com',
            'Nurse' => 'nurse@example.com',
            'Receptionist' => 'receptionist@example.com',
            'Pharmacist' => 'pharmacist@example.com',
            'Accountant' => 'accountant@example.com',
            'Case Manager' => 'case_manager@example.com',
            'Lab Technician' => 'lab_technician@example.com',
        ];

        foreach ($roles as $roleName => $email) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $roleName . ' User',
                    'password' => bcrypt('password'), 
                ]
            );
            $user->assignRole($role);

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
                ]);
            }
        }
    }
}
