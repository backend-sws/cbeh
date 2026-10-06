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
        Schema::create('medicine_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_bill_id')->constrained('medicine_bills')->onDelete('cascade');
            $table->string('payment_date'); // Date & Time
            $table->decimal('amount', 10, 2);
            $table->string('payment_mode'); // Cash, UPI, Card
            $table->string('note')->nullable();
            $table->string('transaction_id')->nullable(); // For Online ref
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_bill_payments');
    }
};
