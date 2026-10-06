<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyReport extends Model
{
    protected $fillable = [
        'bill_id',
        'report_no',
        'pathology_center',
        'test_id',
        'Approved_by',
        'approved_date',
        'result',
        'upload_result'
    ];
    
    public function bill()
    {
        return $this->belongsTo(PathologyBill::class, 'bill_id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'Approved_by');
    }
}
