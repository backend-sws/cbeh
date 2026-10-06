<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeCategory extends Model
{
    //
    protected $fillable = [
        'charge_category',
        'description',
        'charge_type',
    ];

    public function chargeType()
    {
        return $this->belongsTo(ChargeType::class, 'charge_type');
    }
}
