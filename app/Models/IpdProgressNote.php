<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdProgressNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipd_patient_id',
        'patient_id',
        'user_id',
        'staff_name',
        'date_time',
        'title',
        'note',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medicines()
    {
        return $this->hasMany(ProgressNoteMedicine::class, 'ipd_progress_note_id');
    }

    public function addendums()
    {
        return $this->hasMany(ProgressNoteAddendum::class, 'ipd_progress_note_id')->orderBy('date_time', 'asc');
    }
}
