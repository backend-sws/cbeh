<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $table='doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'per_patient_time',
        'monday_available_from',
        'monday_available_to',
        'tuesday_available_from',
        'tuesday_available_to',
        'wednesday_available_from',
        'wednesday_available_to',
        'thursday_available_from',
        'thursday_available_to',
        'friday_available_from',
        'friday_available_to',
        'saturday_available_from',
        'saturday_available_to',
        'sunday_available_from',
        'sunday_available_to',

    ];

    // Define the relationship with the doctor (User model)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
