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
        Schema::create('patient_medications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ipd_patient_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedBigInteger('prescription_id')->nullable()->index();
            $table->unsignedBigInteger('doctor_id')->nullable()->index();
            $table->unsignedBigInteger('medicine_id')->nullable()->index();
            $table->string('medicine_name');
            $table->string('dose')->nullable();
            $table->string('dose_type')->nullable(); // Tablet, Capsule, Syrup, Injection, IV Infusion, Drops, Inhaler, Ointment
            $table->string('route')->nullable(); // Oral, Intravenous (IV), Intramuscular (IM), Subcutaneous (SC), Topical, Inhalation, Sublingual, PR
            $table->string('frequency')->nullable(); // OD, BD, TDS, QID, Q4H, Q6H, Q8H, STAT, SOS, HS
            $table->string('duration')->nullable(); // 3 Days, 5 Days, 7 Days, Ongoing, etc.
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->text('instructions')->nullable();
            $table->string('status')->default('active')->index(); // active, completed, discontinued
            $table->dateTime('discontinued_at')->nullable();
            $table->unsignedBigInteger('discontinued_by')->nullable();
            $table->text('discontinue_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_medications');
    }
};
