<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Prescription extends Model
{
    use Auditable;

    protected $auditModule = 'Prescriptions';
    protected $table = 'prescriptions';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'ipd_no',
        'headernotes',
        'footernotes',
        'prescription_date',
        'case',
        'notes',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }
    

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'prescription_id', 'id');
    }
    public function prescriptionMedicines()
{
    return $this->hasMany(PrescriptionMedicine::class);
}

    public function PrescriptionMedicine()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'prescription_id', 'id');
    }
    public function getStatusAttribute($value)
    {
        return $value ? 'Active' : 'Inactive';
    }
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ? 1 : 0;
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getIpdNoAttribute($value)
    {
        return strtoupper($value);
    }
    public function setIpdNoAttribute($value)
    {
        $this->attributes['ipd_no'] = strtoupper($value);
    }
    public function getCaseAttribute($value)
    {
        return ucfirst($value);
    }
    public function setCaseAttribute($value)
    {
        $this->attributes['case'] = ucfirst($value);
    }
    public function getNotesAttribute($value)
    {
        return ucfirst($value);
    }
    public function setNotesAttribute($value)
    {
        $this->attributes['notes'] = ucfirst($value);
    }
    public function getPatientNameAttribute()
    {
        return $this->patient ? $this->patient->name : 'N/A';
    }
    public function getDoctorNameAttribute()
    {
        return $this->doctor ? $this->doctor->name : 'N/A';
    }
   
}
