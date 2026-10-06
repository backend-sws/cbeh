<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStockDump extends Model
{
    protected $guarded = [];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
