<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathologyBill extends Model
{
    protected $fillable = [
        'bill_no',
        'patient_id',
        'doctor_id',
        'payment_mode_id',
        'amount',
        'tax',
        'discount',
        'notes',
        'previous_report_value',
        'paid_amount',
        'due_amount',
        'payment_status',
        'ipd_patient_id',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function tests()
    {
        return $this->hasMany(CollectionOfPathologyTest::class, 'bill_id');
    }

    public function report()
    {
        return $this->hasOne(PathologyReport::class, 'bill_id');
    }
    public function payments()
    {
        return $this->hasMany(PathologyPayment::class, 'bill_id');
    }
}
