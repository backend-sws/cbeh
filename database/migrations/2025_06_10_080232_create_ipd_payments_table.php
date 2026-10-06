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
        Schema::create('ipd_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2); // Amount of the payment
            $table->integer('patient_id');     // Patient ID
            $table->string('ipd_no');         // IPD Number
            $table->date('payment_date');      // Date of the payment
            $table->unsignedBigInteger('payment_mode_id'); // Foreign key to payment_modes
            $table->string('document')->nullable(); // File name or path (optional)
            $table->text('notes')->nullable(); // Optional notes
            $table->timestamps();

            // Optional: Foreign key constraint for payment mode
            $table->foreign('payment_mode_id')->references('id')->on('payment_modes')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_payments');
    }
};
