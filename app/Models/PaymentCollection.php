<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCollection extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'Payment_type',
        'patient_id',
        'department_type'
    ];

    /**
     * Get the user that owns the payment collection.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }


    public function patient()
    {
        return $this->belongsTo('App\Models\User', 'patient_id');
    }
}
