<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdDoctorCharge extends Model
{
    protected $fillable = ['doctor_id', 'charge'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
