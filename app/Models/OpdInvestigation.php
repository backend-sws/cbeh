<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdInvestigation extends Model
{
    use HasFactory;

    protected $table = 'opd_investigations';

    protected $fillable = [
        'opd_patient_id',
        'patient_id',
        'doctor_id',
        'ordered_by_id',
        'ordered_by_name',
        'diagnosis',
        'investigation_name',
        'category',
        'urgency',
        'instruction',
        'status',
        'sample_type',
        'sample_collected_by',
        'sample_collected_at',
        'sample_accepted_by',
        'sample_accepted_at',
        'process_notes',
        'result_summary'
    ];

    protected $casts = [
        'sample_collected_at' => 'datetime',
        'sample_accepted_at'  => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    public function opdPatient()
    {
        return $this->belongsTo(OpdPatient::class, 'opd_patient_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by_id');
    }
}
