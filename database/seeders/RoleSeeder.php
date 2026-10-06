<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            1 => 'Admin',
            2 => 'Doctor',
            3 => 'Patient',
            4 => 'Nurse',
            5 => 'Receptionist',
            6 => 'Pharmacist',
            7 => 'Accountant',
            8 => 'Case Manager',
            9 => 'Lab Technician',
        ];

        foreach ($roles as $id => $name) {
            Role::updateOrCreate(['id' => $id], ['name' => $name]);
        }
    }
}
