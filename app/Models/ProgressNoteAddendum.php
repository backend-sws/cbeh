<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressNoteAddendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipd_progress_note_id',
        'user_id',
        'staff_name',
        'date_time',
        'note',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function progressNote()
    {
        return $this->belongsTo(IpdProgressNote::class, 'ipd_progress_note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
