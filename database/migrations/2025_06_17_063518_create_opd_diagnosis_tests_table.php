<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('opd_diagnosis_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_patient_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('opd_no')->nullable();
            $table->string('group_test_no');
            $table->integer('test_count')->default(0); // Number of tests in the group

            $table->string('test_name');
            $table->string('test_code')->nullable();
            $table->string('test_type')->nullable(); // Lab, Imaging, ECG, etc.
            $table->string('urgency')->nullable(); // Routine, Stat, Urgent
            $table->text('clinical_indication')->nullable();
            $table->text('special_instructions')->nullable();
            $table->date('test_date')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();

            $table->foreign('opd_patient_id')->references('id')->on('opd_patients')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_diagnosis_tests');
    }
};
