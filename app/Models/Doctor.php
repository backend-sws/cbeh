<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model 
{
    // use InteractsWithMedia;

    public $table = 'doctors';

    public $fillable = [
        'user_id',
        'doctor_department_id',
        'specialist',
        'description',
        'appointment_charge',
        'google_json_file_path',
    ];

    public const GOOGLE_JSON_FILE_PATH = 'google_json_file';

    const STATUS_ALL = 2;

    const ACTIVE = 0;

    const INACTIVE = 1;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'doctor_department_id' => 'integer',
        'specialist' => 'string',
        'description' => 'string',
        'appointment_charge' => 'double'
    ];

    public static $rules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|email:filter|unique:users,email',
        'password' => 'required|same:password_confirmation|min:6',
        'designation' => 'required|string',
        'gender' => 'required',
        'qualification' => 'required|string',
        'dob' => 'nullable|date',
        'specialist' => 'required|string',
        'address1' => 'nullable|string',
        'address2' => 'nullable|string',
        'city' => 'nullable|string',
        'zip' => 'nullable|integer',
        'description' => 'nullable|string',
    ];

    public function prepareDoctorData()
    {
        return [
            'id' => $this->id,
            'title' => $this->user->full_name,
        ];
    }

    public function doctorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'owner');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_department_id');
    }

    public function cases(): HasMany
    {
        return $this->hasMany(PatientCase::class, 'doctor_id');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_cases', 'doctor_id', 'patient_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ScheduleDay::class, 'doctor_id');
    }

    public function payrolls(): MorphMany
    {
        return $this->morphMany(EmployeePayroll::class, 'owner');
    }

    public function prepareDoctor()
    {
        return [
            'id' => $this->id,
            'doctor_name' => $this->doctorUser->full_name ?? __('messages.common.n/a'),
            'doctor_department' => $this->department->title,
            'doctor_image' => $this->doctorUser->getApiImageUrlAttribute(),
        ];
    }

    public function prepareDoctorDetail()
    {
        return [
            'id' => $this->id,
            'doctor_name' => $this->doctorUser->full_name ?? __('messages.common.n/a'),
            'email' => $this->doctorUser->email ?? __('messages.common.n/a'),
            'phone' => $this->doctorUser->phone ?? __('messages.common.n/a'),
            'designation' => $this->doctorUser->designation ?? __('messages.common.n/a'),
            'doctor_department' => $this->department->title ?? __('messages.common.n/a'),
            'qualification' => $this->doctorUser->qualification ?? __('messages.common.n/a'),
            'blood_group' => $this->doctorUser->blood_group ?? __('messages.common.n/a'),
            'date_of_birth' => $this->doctorUser->dob ?? __('messages.common.n/a'),
            'gender' => $this->doctorUser->getGenderStringAttribute() ?? __('messages.common.n/a'),
            'specialist' => $this->specialist ?? __('messages.common.n/a'),
            'address1' => $this->address->address1 ?? __('messages.common.n/a'),
            'address2' => $this->address->address2 ?? __('messages.common.n/a'),
            'city' => $this->address->city ?? __('messages.common.n/a'),
            'zip' => $this->address->zip ?? __('messages.common.n/a'),
            'description' => $this->description ?? __('messages.common.n/a'),
        ];
    }
}
