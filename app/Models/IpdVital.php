<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class IpdVital extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'IPD Vitals';
    protected $table = 'ipd_vitals';

    protected $fillable = [
        'patient_id',
        'ipd_patient_id',
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
     * The IPD admission (ipd_patients row) this vital belongs to.
     */
    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
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

        if ($h < 3) {
            $hm = $h;
        } elseif ($h <= 8.5) {
            $hm = $h * 0.3048;
        } else {
            $hm = $h / 100;
        }

        return $hm > 0 ? round($w / ($hm * $hm), 1) : null;
    }

    /**
     * Get the closest vitals record to a given datetime for an IPD admission.
     *
     * @param int $ipdPatientId
     * @param \Carbon\Carbon|string $datetime
     * @return IpdVital|null
     */
    public static function closestForIpd(int $ipdPatientId, $datetime): ?IpdVital
    {
        return static::where('ipd_patient_id', $ipdPatientId)
            ->where('recorded_at', '<=', $datetime)
            ->orderByDesc('recorded_at')
            ->first();
    }
}
