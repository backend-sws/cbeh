<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdPayment extends Model
{
    protected $fillable = [
        'amount',
        'patient_id',
        'opd_no',
        'payment_date',
        'payment_mode_id',
        'document',
        'notes',
    ];

    public function payment_mode()
    {
        return $this->belongsTo(\App\Models\PaymentMode::class, 'payment_mode_id');
    }

    public function opdPatient()
    {
        return $this->belongsTo(OpdPatient::class, 'opd_no', 'opd_no');
    }
}
