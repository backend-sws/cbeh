<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $table = 'blood_banks'; 
    protected $fillable = [
        'blood_group',
        'remained_bags',
    ];
}
