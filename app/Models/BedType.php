<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
    

    protected $fillable = [
        'name',
        'description',
    ];

    public function beds()
    {
    return $this->hasMany(Bed::class, 'bed_type_id');
    }
}
