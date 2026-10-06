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
        Schema::create('patient_cases',function (Blueprint $table) {
            $table->id();
            $table->string('case_id')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->date('date');
            $table->integer('status')->default(1);
            $table->text('description')->nullable();
            $table->double('fee');
            $table->string('currency_symbol')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_cases');
    }
};
