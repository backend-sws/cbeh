<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
     protected $fillable = [
        'reference_id',
        'patient_id',
        'category_id',
        'operation_id',
        'operation_date',
        'doctor_id',
        'assistant_consultant1',
        'assistant_consultant2',
        'anesthetist',
        'anesthesia_type',
        'ot_technician',
        'ot_assistant',
        'remark',
        'result',
    ];

    public function operation_name(){
        return $this->belongsTo(OperationName::class, 'operation_id');
    }
    public function operation_category(){
        return $this->belongsTo(OperationCategory::class, 'category_id');
    }
    
}
