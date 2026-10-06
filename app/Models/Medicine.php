<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Medicine extends Model
{
    use HasFactory, Auditable;

    protected $table = 'medicines';

    protected $fillable = [
        'medicine',
        'category',
        'brand',
        'unit',
        'salt_composition',
        'buying_price',      // ✅ Master purchase price
        'selling_price',     // ✅ Master sale price
        'quantity',          // Legacy field
        'total_quantity',    // ✅ Total pieces/units
        'available_quantity', // Total available strips
        'strip_quantity',    // ✅ Pieces per strip
        'side_effects',
        'description',
    ];

    public function brandRelation()
    {
        return $this->belongsTo(MedicineBrand::class, 'brand', 'id');
    }

    public function categoryRelation()
    {
        return $this->belongsTo(MedicineCategory::class, 'category', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category');
    }
}
