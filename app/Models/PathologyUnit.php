<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyUnit extends Model
{
    protected $table = 'pathology_units'; 
    protected $fillable = ['name'];
}
