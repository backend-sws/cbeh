<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonation extends Model
{
    protected $fillable = [
        'donor_id',
        'blood_group',
        'bags',
        'donation_date',
        'notes',
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    // 🔗 Each donation belongs to one donor
    public function donor()
    {
        return $this->belongsTo(BloodDonor::class, 'donor_id');
    }
}
