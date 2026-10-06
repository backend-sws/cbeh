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
        Schema::create('ipd_discharges', function (Blueprint $table) {
            $table->id();
            // Link to IPD Patient Table
            $table->foreignId('ipd_patient_id')->constrained('ipd_patients')->onDelete('cascade');

            $table->dateTime('discharge_date');
            $table->string('discharge_status'); // Normal, Referred, Death, LAMA

            // Medical Details
            $table->text('diagnosis')->nullable();
            $table->text('history')->nullable();
            $table->text('treatment_given')->nullable();
            $table->text('investigations')->nullable();
            $table->text('advice')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_discharges');
    }
};
