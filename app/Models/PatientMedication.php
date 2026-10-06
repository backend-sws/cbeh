<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PatientMedication extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'MAR Prescribed Medication';
    protected $auditTitleAttribute = 'medicine_name';

    protected $table = 'patient_medications';

    protected $fillable = [
        'ipd_patient_id',
        'patient_id',
        'prescription_id',
        'doctor_id',
        'medicine_id',
        'medicine_name',
        'dose',
        'dose_type',
        'route',
        'frequency',
        'duration',
        'start_date',
        'end_date',
        'instructions',
        'status',
        'discontinued_at',
        'discontinued_by',
        'discontinue_reason',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discontinued_at' => 'datetime',
    ];

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function discontinuedBy()
    {
        return $this->belongsTo(User::class, 'discontinued_by');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

    public function administrations()
    {
        return $this->hasMany(MedicationAdministration::class, 'patient_medication_id')->orderBy('scheduled_at', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
