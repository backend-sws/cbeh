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
        Schema::create('opd_progress_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_patient_id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('staff_name')->nullable();
            $table->dateTime('date_time');
            $table->string('title');
            $table->longText('note');
            $table->timestamps();

            $table->foreign('opd_patient_id')->references('id')->on('opd_patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_progress_notes');
    }
};
