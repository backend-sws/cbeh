<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class MedicineBill extends Model
{
    use Auditable;

    protected $auditModule = 'Pharmacy Billing';
    protected $auditTitleAttribute = 'bill_number';

    protected $fillable = [
        'bill_number',
        'patient_name',
        'patient_id',
        'doctor_id',
        'model_type',
        'model_id',
        'case_id',
        'admission_id',
        'discount',
        'net_amount',
        'payment_status',
        'payment_type',
        'note',
        'tax_amount',
        'total',
        'bill_date',
        'paid_cash',
        'paid_online',
        'online_payment_type',
        'ipd_patient_id'
    ];



    const MEDICINE_BILL_CASH = 0;

    const MEDICINE_BILL_CHEQUE = 1;

    const MEDICINE_BILL_RAZORPAY = 2;

    const MEDICINE_BILL_PAYSTACK = 3;

    const MEDICINE_BILL_PHONEPE = 4;

    const MEDICINE_BILL_STRIPE = 5;

    const MEDICINE_BILL_FLUTTERWAVE = 6;

    const UNPAID = 0;

    const FULLPAID = 1;

    const PARTIALY_PAID = 2;

    const PAYMENT_STATUS_ARRAY =
        [
            self::UNPAID => 'Unpaid',
            self::FULLPAID => 'Full Paid',
            self::PARTIALY_PAID => 'Partially Paid',
        ];

    const PAYMENT_TYPE = [
        self::MEDICINE_BILL_CASH => 'Cash',
        self::MEDICINE_BILL_CHEQUE => 'Cheque',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function saleMedicine()
    {
        return $this->hasMany(SaleMedicine::class, 'medicine_bill_id');
    }

    public function getPaymentModeTextAttribute()
    {
        return self::PAYMENT_TYPE[$this->payment_type] ?? 'Cash';
    }

    public function payments()
    {
        return $this->hasMany(MedicineBillPayment::class, 'medicine_bill_id');
    }

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id', 'id');
    }

    // ✅ Alias for payments relationship (for better readability)
    public function medicinePayments()
    {
        return $this->hasMany(MedicineBillPayment::class, 'medicine_bill_id');
    }

}
