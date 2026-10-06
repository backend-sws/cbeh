<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalCharge extends Model
{
    protected $fillable=['charge_type', 'charge_category', 'code', 'standard_charge', 'description'];

    public function chargeCategory()
{
    return $this->belongsTo(ChargeCategory::class, 'charge_category');
}
public function chargetype()
{
    return $this->belongsTo(ChargeType::class, 'charge_type');
}

}
