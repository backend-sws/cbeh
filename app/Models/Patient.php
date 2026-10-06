<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Traits\Auditable;

class Patient extends Model
{
   use HasFactory, Auditable;
   public $table = 'patients';

    public $fillable = [
        'user_id',
        'patient_unique_id',
        'template_id',
        'custom_field'
    ];

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const FILTER_STATUS_ARR = [
        0 => 'All',
        1 => 'Active',
        2 => 'Deactive',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'custom_field' => 'array'
    ];

    public static $rules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email:filter|unique:users,email',
        'password' => 'required|same:password_confirmation|min:6',
        'gender' => 'required',
        'dob' => 'nullable|date',
        'phone' => 'nullable|numeric',
        'address1' => 'nullable|string',
        'address2' => 'nullable|string',
        'city' => 'nullable|string',
        'zip' => 'nullable|integer',
    ];

    public static function generateUniquePatientId()
    {
        $yearMonth = date('Ym');
        $prefix = 'CB' . $yearMonth; // e.g. CB202607
        $lastPatient = Patient::where('patient_unique_id', 'like', "$prefix%")
            ->orderBy('patient_unique_id', 'desc')
            ->first();

        if ($lastPatient && preg_match('/(\d{4})$/', $lastPatient->patient_unique_id, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public static function getActivePatientNames()
    {
        $patients = DB::table('users')
            ->where('status', '=', 1)
            ->join('patients', 'users.id', '=', 'patients.user_id')
            ->select(['users.first_name', 'users.last_name', 'patients.id'])
            ->orderBy('first_name')
            ->get();
        $patientsNames = collect();
        foreach ($patients as $patient) {
            $patientsNames[$patient->id] = ucfirst($patient->first_name).' '.ucfirst($patient->last_name);
        }

        return $patientsNames;
    }

    public function prepareData()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patientUser->full_name,
        ];
    }

    public function patientUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

//     public function address(): MorphOne
//     {
//         return $this->morphOne(Address::class, 'owner');
//     }

//     public function cases(): HasMany
//     {
//         return $this->hasMany(PatientCase::class, 'patient_id');
//     }

//     public function appointments(): HasMany
//     {
//         return $this->hasMany(Appointment::class, 'patient_id');
//     }

//     public function admissions(): HasMany
//     {
//         return $this->hasMany(PatientAdmission::class, 'patient_id');
//     }

//     public function bills(): HasMany
//     {
//         return $this->hasMany(Bill::class, 'patient_id');
//     }

//     public function invoices(): HasMany
//     {
//         return $this->hasMany(Invoice::class, 'patient_id');
//     }

//     public function advancedpayments(): HasMany
//     {
//         return $this->hasMany(AdvancedPayment::class, 'patient_id');
//     }

//     public function documents(): HasMany
//     {
//         return $this->hasMany(Document::class, 'patient_id');
//     }

//     public function vaccinations(): HasMany
//     {
//         return $this->hasMany(VaccinatedPatients::class, 'patient_id');
//     }

//     public function opd()
//     {
//         return $this->hasMany(OpdPatientDepartment::class, 'patient_id');
//     }

//     public function opdDepartment(): HasOne
//     {
//         return $this->hasOne(OpdPatientDepartment::class, 'patient_id');
//     }

//     public function idCardTemplate(): BelongsTo
//     {
//         return $this->belongsTo(PatientIdCardTemplate::class, 'template_id');
//     }
}
