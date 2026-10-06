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
        Schema::create('pathology_tests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('pathology_categories')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('pathology_units')->nullOnDelete();

            $table->string('test_name');
            $table->string('short_name')->nullable();
            $table->string('test_type')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('method')->nullable();
            $table->string('report_days')->nullable();
            $table->string('charge_category')->nullable();
            $table->string('standard_charge')->nullable();
            $table->json('parameter')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_tests');
    }
};
