<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Traits\Auditable;

class OpdPatient extends Model
{
    use HasFactory, Auditable;

    protected $table = 'opd_patients'; // Table name

    protected $fillable = [
        'patient_id',
        'case',
        'opd_no',
        'height',
        'weight',
        'blood_pressure',
        'symptoms',
        'notes',
        'appointment_date',
        'doctor_id',
        'standard_charge',
        'payment_mode',
        'is_old_patient',
        'bp',
        'SpO2',
        'casuality',
        'reference',
        'discount',
        'tax',
        'final_amount',
        'invoice_no',
        'age',
        'patient_unique_id',
        'pulse',
        'temperature',
        'rbs',
        'respiratory_rate',
        'general_appearance',
        'cvs',
        'rs',
        'pa',
        'cns',
        'other_systems',
    ];

    // Relationship with User (Patient)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relationship with User (Doctor)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function charges()
    {
        return $this->hasMany(\App\Models\AddCharge::class, 'ipd_no', 'opd_no');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\OpdPayment::class, 'opd_no', 'opd_no');
    }

    /**
     * Per-visit vitals recorded for this OPD visit.
     */
    public function vitals()
    {
        return $this->hasMany(\App\Models\PatientVital::class, 'opd_patient_id');
    }
}