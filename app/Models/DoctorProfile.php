<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $table='doctor_profiles';

    protected $fillable = [
        'user_id', 
        'degree', 
        'specialization', 
        'experience_years', 
        'hospital_name', 
        'consulting_fee',
        'rating'
    ];

    // Define the inverse one-to-one relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
