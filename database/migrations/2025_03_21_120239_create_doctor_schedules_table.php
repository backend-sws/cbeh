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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // Reference to the doctors (users)
            $table->integer('per_patient_time'); // Time allocated per patient in minutes
            
            $table->string('monday_available_from')->nullable();
            $table->string('monday_available_to')->nullable();
                
            $table->string('tuesday_available_from')->nullable();
            $table->string('tuesday_available_to')->nullable();
                
            $table->string('wednesday_available_from')->nullable();
            $table->string('wednesday_available_to')->nullable();
                
            $table->string('thursday_available_from')->nullable();
            $table->string('thursday_available_to')->nullable();
                
            $table->string('friday_available_from')->nullable();
            $table->string('friday_available_to')->nullable();
                
            $table->string('saturday_available_from')->nullable();
            $table->string('saturday_available_to')->nullable();
                
            $table->string('sunday_available_from')->nullable();
            $table->string('sunday_available_to')->nullable();
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']); // Drop the foreign key
        });

        Schema::dropIfExists('doctor_schedules');
    }
};
