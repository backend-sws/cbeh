<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDepartment extends Model
{
    protected $table = 'doctor_department'; // Table name

    protected $fillable = [
        'department_name',
    ];
}
