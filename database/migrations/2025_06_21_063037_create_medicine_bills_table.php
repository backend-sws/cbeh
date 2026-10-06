<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medicine_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->string('patient_name');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('model_type')->nullable(); // For morph relation
            $table->unsignedBigInteger('model_id')->nullable(); // For morph relation
            $table->unsignedBigInteger('case_id')->nullable();
            $table->unsignedBigInteger('admission_id')->nullable();
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2)->default(0.00);
            $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid');
            $table->string('payment_type')->nullable(); // e.g., cash, card, insurance
            $table->text('note')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->date('bill_date');
            // Payment split columns
            $table->decimal('paid_cash', 10, 2)->default(0)->after('net_amount');
            $table->decimal('paid_online', 10, 2)->default(0)->after('paid_cash');
            $table->string('online_payment_type')->nullable()->after('paid_online'); // UPI, Card, etc.


            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_bills');
    }
};
