<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name', 
        'last_name',
        'role', 
        'phone',
        'gender',
        'dob',
        'facebook_url', 
        'instagram_url',
        'twitter_url',
        'linkedin_url', 
        'image',
        'alternate_phone',
        'guardian_name',
        'blood_group',
        'marital_status',
        'address',
        'alternate_address',    
        'remarks',
        'allergies',
        'status',
        'age',
        'age_month',
        'adhar_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return $this->first_name ? trim("{$this->first_name} {$this->last_name}") : $this?->name;
    }
    
    public function ipdPatients()
    {
        return $this->hasMany(IpdPatient::class, 'patient_id', 'id');
    }

    public function patientProfile()
{
    return $this->hasOne(\App\Models\Patient::class, 'user_id');
}
}
