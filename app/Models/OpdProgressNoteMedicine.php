<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdProgressNoteMedicine extends Model
{
    use HasFactory;

    protected $table = 'opd_progress_note_medicines';

    protected $fillable = [
        'opd_progress_note_id',
        'category_id',
        'medicine_id',
        'medicine_name',
        'dosage',
        'route',
        'dose_interval',
        'duration',
        'instructions',
    ];

    public function progressNote()
    {
        return $this->belongsTo(OpdProgressNote::class, 'opd_progress_note_id');
    }

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
