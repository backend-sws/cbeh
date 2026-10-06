<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyCategory extends Model
{
    protected $table = 'pathology_categories'; 
    protected $fillable = ['name'];
}
