<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PatientCase extends Model
{
    use HasFactory;

    public $table = 'patient_cases';

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

    protected $fillable = [
        'case_id',
        'patient_id',
        'phone',
        'doctor_id',
        'date',
        'status',
        'description',
        'fee',
        'currency_symbol',
    ];

    protected $casts = [
        'id' => 'integer',
        'case_id' => 'string',
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'phone' => 'string',
        'date' => 'date',
        'status' => 'integer',
        'description' => 'string',
        'currency_symbol' => 'string',
        'fee' => 'double',
    ];

    public static $rules = [
        'patient_id' => 'required',
        'phone' => 'nullable|numeric',
        'doctor_id' => 'required',
        'date' => 'required',
        'description' => 'nullable',
        'fee' => 'required',
    ];

    public static function generateUniqueCaseId()
    {
        do {
            $caseId = Str::random(8);
        } while (self::where('case_id', $caseId)->exists());

        return $caseId;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function patients(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function doctors(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function bedAssign(): BelongsTo
    {
        return $this->belongsTo(BedAssign::class, 'case_id', 'case_id');
    }

    public function prepareData(): array
    {
        return [
            'patient_case' => $this->case_id . ' ' . $this->patient->patientUser->full_name,
        ];
    }

    public function preparePatientCaseDetailData(): array
    {
        return [
            'id' => $this->id,
            'case_id' => $this->case_id,
            'case_date' => isset($this->date) ? Carbon::parse($this->date)->translatedFormat('jS M, Y, g:i A') : __('messages.common.n/a'),
            'patient' => $this->patient->patientUser->full_name ?? __('messages.common.n/a'),
            'phone' => $this->phone ?? __('messages.common.n/a'),
            'fee' => $this->fee ?? __('messages.common.n/a'),
            'created_on' => $this->created_at->diffForHumans() ?? __('messages.common.n/a'),
            'description' => $this->description ?? __('messages.common.n/a'),
            'currency' => getCurrencySymbol(),
        ];
    }

    public function preparePatientCase(): array
    {
        return [
            'id' => $this->id,
            'doctor_name' => $this->doctor->doctorUser->full_name ?? __('messages.common.n/a'),
            'doctor_image' => $this->doctor->doctorUser->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
            'case_id' => $this->case_id ?? __('messages.common.n/a'),
            'status' => self::STATUS_ARR[$this->status] ?? __('messages.common.n/a'),
            'case_date' => isset($this->date) ? Carbon::parse($this->date)->format('jS M, Y') : __('messages.common.n/a'),
            'case_time' => isset($this->date) ? Carbon::parse($this->date)->format('h:i A') : __('messages.common.n/a'),
            'fee' => $this->fee ?? __('messages.common.n/a'),
            'created_on' => $this->created_at->diffForHumans() ?? __('messages.common.n/a'),
            'description' => $this->description ?? __('messages.common.n/a'),
            'currency' => getCurrencySymbol() ?? __('messages.common.n/a'),
        ];
    }
}
