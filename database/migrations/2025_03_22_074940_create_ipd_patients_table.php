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
        Schema::create('ipd_patients', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('patient_id')->unique(); // Foreign key
            $table->string('case')->nullable();
            $table->string('ipd_no')->unique();
            $table->string('height')->nullable();
            $table->datetime('admission_date');
             $table->datetime('discharge_date')->nullable();
            
            $table->string('blood_pressure')->nullable();
            $table->unsignedBigInteger('doctor_id'); // Foreign key
            $table->string('bed_type')->nullable();
            $table->string('bed');
            $table->boolean('status');
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // Adds created_at and updated_at

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_patients', function (Blueprint $table) {
            $table->dropForeign(['patient_id']); // Drop the foreign key
            $table->dropForeign(['doctor_id']); // Drop the foreign key
        });
        Schema::dropIfExists('ipd_patients');
    }
};
