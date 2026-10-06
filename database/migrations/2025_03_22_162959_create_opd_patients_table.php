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
        Schema::create('opd_patients', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('patient_id'); // Foreign key to users table
            $table->string('case')->nullable;
            $table->string('patient_unique_id'); // Unique identifier for the patient
            $table->string('opd_no')->unique();

            $table->string('invoice_no'); // Invoice number can be nullable
            $table->string('height');
            $table->string('weight');
            $table->string('blood_pressure')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('appointment_date');
            $table->unsignedBigInteger('doctor_id'); // foreign key 
            $table->string('standard_charge');
            $table->string('payment_mode');
            $table->boolean('is_old_patient')->default(0);
            // $table->string('bp')->nullable();
            $table->string('SpO2')->nullable();

            $table->string('casuality')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('final_amount', 12, 2)->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_patients', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });
        Schema::dropIfExists('opd_patients');
    }
};
