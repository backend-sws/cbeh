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
        Schema::create('medication_administrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_medication_id')->index();
            $table->unsignedBigInteger('ipd_patient_id')->nullable()->index();
            $table->unsignedBigInteger('patient_id')->index();
            $table->dateTime('scheduled_at')->index();
            $table->string('shift')->nullable()->index(); // Morning, Evening, Night
            $table->string('status')->default('scheduled')->index(); // scheduled, given, not_given, held, refused
            $table->dateTime('actual_administered_at')->nullable();
            $table->string('given_quantity')->nullable(); // e.g. 1 Tablet, 5 ml, 1 Injection
            $table->string('route_administered')->nullable();
            $table->unsignedBigInteger('administered_by_user_id')->nullable()->index();
            $table->string('staff_id')->nullable(); // e.g. EMP-10
            $table->string('staff_name')->nullable(); // snapshot of staff name
            $table->dateTime('recorded_at')->nullable(); // legal server audit timestamp
            $table->text('remarks')->nullable(); // reason if not given/held/refused or clinical notes
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_medication_id')->references('id')->on('patient_medications')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('administered_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_administrations');
    }
};
