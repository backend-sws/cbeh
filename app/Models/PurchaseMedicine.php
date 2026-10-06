<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class PurchaseMedicine extends Model
{
    use Auditable;

    protected $auditModule = 'Pharmacy Purchase';
    protected $auditTitleAttribute = 'purchase_no';

    protected $fillable =
        [
            'purchase_no',
            'total',
            'discount',
            'tax',
            'net_amount',
            'payment_type',
            'payment_status',
            'payment_note',
            'note',
            'supplier_name',
            'bill_number',
           
        ];


    public function purchasedMedicines()
    {
        return $this->hasMany(PurchasedMedicine::class, 'purchase_medicines_id');
    }
}
