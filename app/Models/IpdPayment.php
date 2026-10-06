<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdPayment extends Model
{
    protected $fillable = ['amount', 'patient_id', 'ipd_no', 'payment_date', 'payment_mode_id', 'document', 'document', 'notes'];

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function ipdPatient()
    {
        // Note: Apne database column ka naam check karein. 
        // Agar column 'ipd_patient_id' hai:
        // return $this->belongsTo(IpdPatient::class, 'ipd_patient_id', 'id');

        // Agar column 'ipd_id' hai:
        // return $this->belongsTo(IpdPatient::class, 'ipd_id', 'id');

        // Agar aap 'ipd_no' se link kar rahe hain (Jaise Charges mein kiya tha):
        return $this->belongsTo(IpdPatient::class, 'ipd_no', 'ipd_no');
    }
  
}
