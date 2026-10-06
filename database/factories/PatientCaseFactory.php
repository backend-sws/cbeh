<?php

namespace Database\Factories;


use App\Models\PatientCase;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PatientCaseFactory extends Factory
{
    protected $model = PatientCase::class;

    public function definition()
    {
        return [
            'case_id' => Str::random(8),
            'patient_id' => Patient::factory(),
            'phone' => $this->faker->phoneNumber,
            'doctor_id' => Doctor::factory(),
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement([0, 1]),
            'description' => $this->faker->sentence,
            'fee' => $this->faker->randomFloat(2, 100, 1000),
            'currency_symbol' => '₹',
        ];
    }
}

