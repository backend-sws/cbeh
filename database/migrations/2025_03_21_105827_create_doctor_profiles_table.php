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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to users table
            $table->string('degree', 100)->nullable();
            $table->string('specialization', 100)->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('hospital_name', 200)->nullable();
            $table->string('consulting_fee', 100,)->nullable();
            $table->string('rating', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('doctor_profiles');
    }
};
