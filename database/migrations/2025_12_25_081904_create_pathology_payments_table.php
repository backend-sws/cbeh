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
        Schema::create('pathology_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('pathology_bills')->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Jo paisa abhi diya
            $table->string('payment_mode'); // Cash, UPI, etc.
            $table->string('transaction_id')->nullable(); // Optional: UPI Ref No
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_payments');
    }
};
