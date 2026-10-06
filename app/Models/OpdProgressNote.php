<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdProgressNote extends Model
{
    use HasFactory;

    protected $table = 'opd_progress_notes';

    protected $fillable = [
        'opd_patient_id',
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

    public function opdPatient()
    {
        return $this->belongsTo(OpdPatient::class, 'opd_patient_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medicines()
    {
        return $this->hasMany(OpdProgressNoteMedicine::class, 'opd_progress_note_id');
    }

    public function addendums()
    {
        return $this->hasMany(OpdProgressNoteAddendum::class, 'opd_progress_note_id')->orderBy('date_time', 'asc')->orderBy('id', 'asc');
    }
}
