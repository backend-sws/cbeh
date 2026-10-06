<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    

    protected $fillable = [
        'code',
        'name',
        'description',
        'bed_type_id',
        'status',
    ];
public function bed_assigns()
{ 
return $this->hasMany(BedAssign::class, 'bed_id');
}

    public function bedType()
    {
        return $this->belongsTo(BedType::class, 'bed_type_id');
    }

    
        

    
   

    
}
