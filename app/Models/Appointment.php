<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DoctorDepartment;
class Appointment extends Model
{
    protected $table = 'appointments'; // Table name

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'doctor_department_id',
        'date',
        'description',
        'date_of_birth',
        'status',
        'can_you_extend',
    ];

    // Relationship with User (as Patient)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relationship with User (as Doctor)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function department()
    {
        return $this->belongsTo(DoctorDepartment::class,'doctor_department_id');
    }

}
