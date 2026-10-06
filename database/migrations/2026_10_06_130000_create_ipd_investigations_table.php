<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ipd_investigations')) {
            Schema::create('ipd_investigations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ipd_patient_id');
                $table->unsignedBigInteger('patient_id')->nullable();
                $table->unsignedBigInteger('doctor_id')->nullable();
                $table->unsignedBigInteger('ordered_by_id')->nullable();
                $table->string('ordered_by_name')->nullable();
                $table->text('diagnosis')->nullable();
                $table->string('investigation_name');
                $table->string('category')->nullable(); // Pathology, Biochemistry, Microbiology, Radiology, ECG, etc.
                $table->string('urgency')->default('Routine'); // Routine, Urgent, STAT
                $table->text('instruction')->nullable();
                $table->string('status')->default('Pending'); // Pending, Sample Collected, Sample Accepted, In Process, Completed, Cancelled
                $table->string('sample_type')->nullable(); // Blood (EDTA), Serum, Urine, etc.
                $table->string('sample_collected_by')->nullable();
                $table->dateTime('sample_collected_at')->nullable();
                $table->string('sample_accepted_by')->nullable();
                $table->dateTime('sample_accepted_at')->nullable();
                $table->text('process_notes')->nullable();
                $table->text('result_summary')->nullable();
                $table->timestamps();

                $table->foreign('ipd_patient_id')->references('id')->on('ipd_patients')->onDelete('cascade');
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('ordered_by_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_investigations');
    }
};
