<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class MedicationAdministration extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'MAR Medication Administration';

    protected $table = 'medication_administrations';

    protected $fillable = [
        'patient_medication_id',
        'ipd_patient_id',
        'patient_id',
        'scheduled_at',
        'shift',
        'status',
        'actual_administered_at',
        'given_quantity',
        'route_administered',
        'administered_by_user_id',
        'staff_id',
        'staff_name',
        'recorded_at',
        'remarks',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'actual_administered_at' => 'datetime',
        'recorded_at' => 'datetime',
    ];

    public function medication()
    {
        return $this->belongsTo(PatientMedication::class, 'patient_medication_id');
    }

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by_user_id');
    }

    public function scopeDue($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeGiven($query)
    {
        return $query->where('status', 'given');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('scheduled_at', $date);
    }
}
