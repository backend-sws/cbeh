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
        Schema::create('addmissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_no')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->date('admission_date');
            $table->date('discharge_date')->nullable();
            $table->string('insurance')->nullable();
            $table->string('policy_no')->nullable();
            $table->string('package_name')->nullable();
            $table->string('ipd_no')->nullable();
            $table->string('bed_no')->nullable();
            $table->string('agent')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addmissions');
    }
};
