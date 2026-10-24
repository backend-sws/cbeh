<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('opd_investigations')) {
            Schema::create('opd_investigations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('opd_patient_id');
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

                $table->foreign('opd_patient_id')->references('id')->on('opd_patients')->onDelete('cascade');
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('ordered_by_id')->references('id')->on('users')->onDelete('set null');
            });

            // Migrate any existing records from opd_diagnosis_tests so historical data is preserved
            if (Schema::hasTable('opd_diagnosis_tests')) {
                try {
                    $existing = DB::table('opd_diagnosis_tests')->get();
                    foreach ($existing as $row) {
                        DB::table('opd_investigations')->insert([
                            'opd_patient_id'     => $row->opd_patient_id,
                            'patient_id'         => $row->patient_id,
                            'doctor_id'          => $row->doctor_id,
                            'ordered_by_name'    => 'Doctor',
                            'diagnosis'          => $row->clinical_indication,
                            'investigation_name' => $row->test_type ?: ($row->test_name ?: 'Investigation'),
                            'category'           => trim($row->test_name) ?: 'Pathology',
                            'urgency'            => $row->urgency ?: 'Routine',
                            'instruction'        => $row->special_instructions,
                            'status'             => $row->result ? 'Completed' : 'Pending',
                            'result_summary'     => $row->result,
                            'created_at'         => $row->created_at ?: now(),
                            'updated_at'         => $row->updated_at ?: now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    // Ignore migration data copy errors if schema differs
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_investigations');
    }
};
