<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class SaleMedicine extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'Pharmacy Sale Item';
    protected $fillable = [
        'medicine_bill_id',
        'medicine_id',
        'expiry_date',
           'lot_no',
        'price',
        'quantity',
        'tax',
        'amount',
        'discount_percent',
        'unit_type'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function bill()
    {
        return $this->belongsTo(MedicineBill::class, 'medicine_bill_id');
    }
}
