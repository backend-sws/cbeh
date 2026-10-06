<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddCharge extends Model
{
protected $fillable = [
    'date',
    'patient_id',
    'ipd_no',
    'charge_type',
    'charge_category',
    'code',
    'standard_charge',
    'applied_charge',
    'quantity',
    'total',
];
public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class,'charge_category');
    }
   public function chargeType()
{
    return $this->belongsTo(\App\Models\ChargeType::class, 'charge_type'); // charge_type is the foreign key
}
    
}
