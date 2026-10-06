<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBrand extends Model
{
    use HasFactory;

    protected $table = 'medicine_brands';

    protected $fillable = [
        'brand',
        'email',
        'phone',
    ];
}
