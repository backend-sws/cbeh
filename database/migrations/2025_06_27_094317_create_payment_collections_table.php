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
        Schema::create('payment_collections', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('Payment_type')->nullable();
            $table->string('patient_id')->nullable();

            $table->integer('department_type'); // Optional field for department type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_collections');
    }
};
