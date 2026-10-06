<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('death_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ipd_patient_id')->nullable(); // Optional link to IPD
            $table->string('reg_no')->unique(); // Unique Certificate No

            $table->string('patient_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);

            $table->date('death_date');
            $table->time('death_time');
            $table->string('cause_of_death'); // Cause

            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('ipd_patient_id')->references('id')->on('ipd_patients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('death_reports');
    }
};
