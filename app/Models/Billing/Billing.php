<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $table = 'billings'; // Table name

    protected $fillable = [
        'admission_id',
        'bill_date',
        'patient_id',
        'patient_email',
        'patient_dob',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'package_name',
        'insurance_id',
        'total_days',
        'policy_no',
    ];
    
}
