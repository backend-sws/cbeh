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
    Schema::create('add_charges', function (Blueprint $table) {
        $table->id();
        $table->date('date')->nullable();
        $table->unsignedBigInteger('patient_id')->nullable();
        $table->string('charge_type')->nullable();
        $table->string('charge_category')->nullable();
        $table->string('ipd_no')->nullable();
        $table->string('code')->nullable();
        $table->decimal('standard_charge', 10, 2)->nullable();
        $table->decimal('applied_charge', 10, 2)->nullable();
        $table->integer('quantity')->default(1);
        $table->decimal('total', 10, 2)->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_charges');
    }
};
