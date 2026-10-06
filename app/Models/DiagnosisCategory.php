<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisCategory extends Model
{
    protected $table = 'diagnosis_categories';
    protected $fillable = [
        'diagnosis_category',
        'description',
    ];
}
