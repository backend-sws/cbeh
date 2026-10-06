<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'medicine_name',
        'dosage',
        'route',
        'doseInterval',
        'duration',
        'time',
        'instructions',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'id');
    }
public function medicine()
{
    return $this->belongsTo(Medicine::class);
}

    // public function medicine()
    // {
    //     return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    // }
}
