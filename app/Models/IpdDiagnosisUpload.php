<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdDiagnosisUpload extends Model
{
    use HasFactory;

    protected $table = 'ipd_diagnosis_upload';

    protected $fillable = [
        'ipd_patient_id',
        'report_type',
        'report_date',
        'document',
        'description'
    ];

    protected $casts = [
        'report_date' => 'date'
    ];

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
    }
}