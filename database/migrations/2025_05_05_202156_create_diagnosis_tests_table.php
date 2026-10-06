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
        Schema::create('diagnosis_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('diagnosis_category_id');
            $table->string('report_number');

            $table->integer('age');
            $table->string('height');
            $table->string('weight');
            $table->string('average_glucose');
            $table->string('fasting_blood_sugar');
            $table->string('urine_sugar');
            $table->string('blood_pressure');
            $table->string('diabetes');
            $table->string('cholesterol');

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('diagnosis_category_id')->references('id')->on('diagnosis_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_tests');
    }
};
