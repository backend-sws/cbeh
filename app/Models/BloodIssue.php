<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodIssue extends Model
{
   protected $fillable = [
        'patient_unique_id',
        'invoice_no',
        'issue_no',
        'patient_name',
        'doctor_name',
        'blood_group',
        'bags',
        'issue_date',
        'amount',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            // Next auto ID
            $lastId = self::max('id') + 1;

            // Patient unique ID: CB + YYYYMM + padded ID
            $issue->patient_unique_id = 'CB' . now()->format('Ym') . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // Invoice No: INV + timestamp
            $issue->invoice_no = 'INV' . now()->format('YmdHis');

            // Issue No: CB/OPD/XXXXX/YY
            $yearSuffix = now()->format('y'); // last 2 digits of year
            $opdSequence = str_pad($lastId, 5, '0', STR_PAD_LEFT);
            $issue->issue_no = 'CB/OPD/' . $opdSequence . '/' . $yearSuffix;
        });
    }

}
