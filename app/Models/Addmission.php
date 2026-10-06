<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addmission extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'admission_no', 
          'insurance', 'policy_no', 'package_name', 'ipd_no', 'bed_no', 'agent', 'guardian_name', 'guardian_phone', 'guardian_email', 
          'guardian_relationship', 'guardian_address', 'status'
    ];

    public function patient(){
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function getAdmissionDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
    public function setAdmissionDateAttribute($value)
    {
        $this->attributes['admission_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getCaseAttribute($value)
    {
        return ucfirst($value);
    }
    
}
