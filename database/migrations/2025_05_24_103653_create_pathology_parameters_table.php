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
        Schema::create('pathology_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference_range');
            $table->unsignedBigInteger('unit_id');
            $table->text('description')->nullable();
            $table->timestamps();
             $table->foreign('unit_id')->references('id')->on('pathology_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_parameters', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
        });
        Schema::dropIfExists('pathology_parameters');
    }
};
