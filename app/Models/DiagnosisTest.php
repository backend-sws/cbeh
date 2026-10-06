<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisTest extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis_category_id',
        'report_number',
        'age',
        'height',
        'weight',
        'average_glucose',
        'fasting_blood_sugar',
        'urine_sugar',
        'blood_pressure',
        'diabetes',
        'cholesterol',
    ];

    // Optional: Relationships
    public function patient() {
        return $this->belongsTo(User::class,'patient_id');
    }

    public function doctor() {
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function diagnosisCategory() {
        return $this->belongsTo(DiagnosisCategory::class,'diagnosis_category_id');
    }
}
