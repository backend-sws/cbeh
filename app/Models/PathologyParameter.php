<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyParameter extends Model
{
    protected $table = 'pathology_parameters'; 
    protected $fillable = [
        'name', 'reference_range', 'unit_id', 'description',
    ];

    public function unit()
    {
        return $this->belongsTo(PathologyUnit::class, 'unit_id');
    }

}
