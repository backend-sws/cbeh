<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed_assign extends Model
{

    protected $fillable = [
        'patient_id',
        'bed_id',
        'case',
        'ipd_no',
        'assigned_date',
        'discharge_date',
        'status',
    ];

    public function patient()
    {
     return $this->belongsTo(User::class, 'patient_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
