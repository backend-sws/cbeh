<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    /**
     * The table associated with the model.
     *
     *
     * @var string
     */
    protected $table = 'diagnoses';
    /**
     * The attributes that are mass assignable.
     *
     *
     * @var array
     */
     protected $fillable = [
        'opd_patient_id', 'report_type', 'report_date', 'document', 'description'
    ];

    public function opdPatient()
    {
        return $this->belongsTo(OpdPatient::class, 'opd_patient_id');
    }
}
