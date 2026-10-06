<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_patient_id'); // Foreign key to opd_patients table
            $table->string('report_type'); // Type of report (e.g., blood test, urine test)
            $table->date('report_date'); // Date of the report
            $table->string('document')->nullable(); // Path to the document or file
            $table->text('description')->nullable(); // Additional description or notes about the diagnosis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
