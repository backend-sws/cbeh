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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('Bill_No')->unique();
            $table->unsignedBigInteger('admission_id');
            $table->date('bill_date');
            $table->unsignedBigInteger('patient_id');
            $table->string('patient_email')->unique();
            $table->date('patient_dob');
            $table->unsignedBigInteger('doctor_id');
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('package_name');
            $table->unsignedBigInteger('insurance_id');
            $table->bigInteger('total_days');
            $table->string('policy_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
