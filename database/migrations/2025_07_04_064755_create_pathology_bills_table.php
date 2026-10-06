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
        Schema::create('pathology_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->date('bill_date');
            $table->integer('patient_id');
            $table->integer('doctor_id')->nullable();
            $table->string('payment_mode_id')->default('cash');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 5, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->string('notes')->nullable();
            $table->string('previous_report_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_bills');
    }
};
