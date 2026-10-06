<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyPayment extends Model
{
    protected $table = 'pathology_payments';
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(PathologyBill::class, 'bill_id');
    }
}
