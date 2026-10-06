<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportParameterValue extends Model
{
    protected $fillable = ['parameter_id', 'value', 'unit', 'reference_range', 'report_id', 'bill_id', 'test_id'];
    public function parameter()
    {
        return $this->belongsTo(PathologyParameter::class, 'parameter_id');
    }
}

