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
    Schema::create('charge_types', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Name of the charge type (e.g., Consultation, Lab, Surgery)
        $table->text('description')->nullable(); // Optional description
        $table->boolean('status')->default(true); // To indicate active/inactive status
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_types');
    }
};
