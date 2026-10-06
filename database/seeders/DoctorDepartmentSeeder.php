<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('doctor_department')->truncate();

        $departments=[
            ['department_name'=>'Department 1'],
            ['department_name'=>'Department 2'],
            ['department_name'=>'Department 3'],
            ['department_name'=>'Department 4'],
            ['department_name'=>'Department 5'],
        ];

        DB::table('doctor_department')->insert($departments);
    }
}
