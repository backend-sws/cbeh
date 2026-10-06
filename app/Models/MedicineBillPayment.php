<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class MedicineBillPayment extends Model
{
    use HasFactory, Auditable;

    protected $auditModule = 'Pharmacy Payment';
    protected $guarded = [];

    public function bill()
    {
        return $this->belongsTo(MedicineBill::class, 'medicine_bill_id');
    }

    public function ipdPatient()
    {
        return $this->belongsTo(IpdPatient::class, 'ipd_patient_id');
    }
}
