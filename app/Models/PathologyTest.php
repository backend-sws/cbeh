<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyTest extends Model
{
    protected $table = 'pathology_tests'; 
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'category_id',
        'unit_id',
        'parameter_id',
        'test_name',
        'short_name',
        'test_type',
        'sub_category',
        'method',
        'report_days',
        'charge_category',
        'standard_charge',
        'tax',
        'parameter',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PathologyCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(PathologyUnit::class);
    }
    public function parameter()
    {
        return $this->belongsTo(PathologyParameter::class);
    }
}
