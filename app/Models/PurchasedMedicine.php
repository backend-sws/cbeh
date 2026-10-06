<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PurchasedMedicine extends Model
{
    use Auditable;

    protected $auditModule = 'Pharmacy Purchase Item';

    protected $fillable =
        [
            'purchase_medicines_id',
            'medicine_id',
            'lot_no',
            'expiry_date',
            'quantity',
            'amount',
            'tax',
            'available_quantity',
            'purchase_price',  // ✅ Added - batch-level purchase price
            'sale_price'       // ✅ Added - batch-level selling price
        ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
   
}
