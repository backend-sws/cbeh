<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create ipd_vitals table for IPD per-day/per-shift vitals tracking.
     * During IPD admission, vitals are recorded multiple times daily (ward rounds).
     * Supports Morning / Evening / Night shifts and can be printed as a Vitals Chart.
     */
    public function up(): void
    {
        Schema::create('ipd_vitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');              // users.id
            $table->unsignedBigInteger('ipd_patient_id');          // ipd_patients.id (admission)

            $table->datetime('recorded_at');                        // Actual date+time of recording
            $table->string('recorded_by')->nullable();              // Staff/Doctor/Nurse name
            $table->string('shift')->nullable();                    // Morning / Evening / Night / Other

            // Vital Signs
            $table->string('blood_pressure')->nullable();           // e.g. 120/80 mm/Hg
            $table->string('pulse')->nullable();                    // e.g. 72 /min
            $table->string('temperature')->nullable();              // e.g. 98.6 °F
            $table->string('spo2')->nullable();                     // e.g. 99 %
            $table->string('respiratory_rate')->nullable();         // e.g. 18 /min
            $table->string('rbs')->nullable();                      // e.g. 110 mg/dL
            $table->string('height')->nullable();                   // e.g. 165 cm (usually static after admission)
            $table->string('weight')->nullable();                   // e.g. 60 kg

            // Physical Examination Findings (recorded each ward round)
            $table->string('general_appearance')->nullable();
            $table->string('cvs')->nullable();
            $table->string('rs')->nullable();
            $table->string('pa')->nullable();
            $table->string('cns')->nullable();
            $table->string('other_systems')->nullable();

            $table->text('notes')->nullable();                      // Extra clinical remarks

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ipd_patient_id')->references('id')->on('ipd_patients')->onDelete('cascade');

            $table->index(['ipd_patient_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipd_vitals');
    }
};
