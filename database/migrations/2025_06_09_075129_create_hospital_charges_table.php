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
    Schema::create('hospital_charges', function (Blueprint $table) {
        $table->id();
        $table->string('charge_type')->nullable();
        $table->string('charge_category')->nullable();
        $table->string('code')->nullable();
        $table->decimal('standard_charge', 10, 2)->nullable(); // Suitable for currency
        $table->text('description')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_carges');
    }
};
