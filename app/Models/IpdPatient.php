<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\Auditable;

class IpdPatient extends Model
{
    use Auditable;

    protected $table = 'ipd_patients';

    protected $fillable = [
        'patient_id',
        'case',
        'ipd_no',
        'height',
        'weight',
        'admission_date',
        'discharge_date',
        'blood_pressure',
        'bp',
        'pulse',
        'temperature',
        'SpO2',
        'respiratory_rate',
        'rbs',
        'general_appearance',
        'cvs',
        'rs',
        'pa',
        'cns',
        'other_systems',
        'doctor_id',
        'bed_type',
        'bed',
        'status',
        'symptoms',
        'notes',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function bed_assigns()
    {
        return $this->hasMany(Bed_assign::class, 'ipd_no', 'ipd_no');
    }
    public function beds()
    {
        return $this->belongsTo(Bed::class, 'bed');
    }
    //  ADD THIS: Relationship for Charges
    public function charges()
    {
        // param 1: Related Model (AddCharge)
        // param 2: Foreign Key on AddCharge table ('ipd_no')
        // param 3: Local Key on IpdPatient table ('ipd_no')
        return $this->hasMany(AddCharge::class, 'ipd_no', 'ipd_no');
    }

    //  PAYMENTS RELATIONSHIP (Via IPD NO)
    public function payments()
    {
        // Assuming payments bhi IPD No se link hain
        return $this->hasMany(IpdPayment::class, 'ipd_no', 'ipd_no');
    }

    // Add this function
    public function discharge()
    {
        return $this->hasOne(IpdDischarge::class, 'ipd_patient_id');
    }

    // Ek IPD admission me multiple medicine bills ho sakte hain
    public function medicineBills()
    {
        return $this->hasMany(MedicineBill::class, 'ipd_patient_id', 'id');
    }

    public function pharmacyPayments()
    {
        return $this->hasMany(MedicineBillPayment::class, 'ipd_patient_id');
    }

    /**
     * Per-shift/per-day vitals recorded during this IPD admission.
     */
    public function vitals()
    {
        return $this->hasMany(\App\Models\IpdVital::class, 'ipd_patient_id')->orderByDesc('recorded_at');
    }

}
