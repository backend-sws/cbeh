<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpdDiagnosisTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'opd_patient_id',
        'patient_id',
        'doctor_id',
        'opd_no',
      'group_test_no',
      'test_count',
      'test_name',
      'test_code',
      'test_type',
      'urgency',
      'charge_category',
      'charge_type',
      'price',
      'test_date',
      'result',

      
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }

    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category');
    }

    public function chargeType()
    {
        return $this->belongsTo(ChargeType::class, 'charge_type');
    }
}
