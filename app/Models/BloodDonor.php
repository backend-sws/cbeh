<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonor extends Model
{
       protected $fillable = [
        'name', 'age', 'gender', 'blood_group',
        'phone', 'address', 'last_donation_date', 'notes',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
    ];

    // 🔗 A donor can have many donations
    public function donations()
    {
        return $this->hasMany(BloodDonation::class, 'donor_id');
    }
}
