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
        Schema::create('birth_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ipd_patient_id')->nullable(); // Link to IPD
            $table->string('reg_no')->unique(); // Certificate No (e.g. TH000044)

            $table->string('child_name')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('birth_date');
            $table->time('birth_time');
            $table->string('weight')->nullable(); // e.g. 3.2 Kg

            $table->string('mother_name');
            $table->string('father_name');
            $table->text('address')->nullable();

            $table->date('issue_date')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('ipd_patient_id')->references('id')->on('ipd_patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_reports');
    }
};
