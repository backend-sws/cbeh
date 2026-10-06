<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PatientVital extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'OPD Vitals';
    protected $table = 'patient_vitals';

    protected $fillable = [
        'patient_id',
        'opd_patient_id',
        'recorded_at',
        'recorded_by',
        'shift',
        'blood_pressure',
        'pulse',
        'temperature',
        'spo2',
        'respiratory_rate',
        'rbs',
        'height',
        'weight',
        'general_appearance',
        'cvs',
        'rs',
        'pa',
        'cns',
        'other_systems',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * The OPD visit (opd_patients row) this vital belongs to.
     */
    public function opdPatient()
    {
        return $this->belongsTo(OpdPatient::class, 'opd_patient_id');
    }

    /**
     * The patient user this vital belongs to.
     */
    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    /**
     * Calculate BMI from height and weight.
     */
    public function getBmiAttribute(): ?float
    {
        if (empty($this->weight) || empty($this->height)) {
            return null;
        }

        $h = floatval(preg_replace('/[^0-9.]/', '', $this->height));
        $w = floatval(preg_replace('/[^0-9.]/', '', $this->weight));

        if ($h <= 0 || $w <= 0) return null;

        // Convert height to meters
        if ($h < 3) {
            $hm = $h; // already meters
        } elseif ($h <= 8.5) {
            $hm = $h * 0.3048; // feet to meters
        } else {
            $hm = $h / 100; // cm to meters
        }

        return $hm > 0 ? round($w / ($hm * $hm), 1) : null;
    }

    /**
     * Get the closest vitals record to a given datetime for an OPD visit.
     *
     * @param int $opdPatientId
     * @param \Carbon\Carbon|string $datetime
     * @return PatientVital|null
     */
    public static function closestForOpd(int $opdPatientId, $datetime): ?PatientVital
    {
        return static::where('opd_patient_id', $opdPatientId)
            ->where('recorded_at', '<=', $datetime)
            ->orderByDesc('recorded_at')
            ->first();
    }
}
