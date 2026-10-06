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
        Schema::table('ipd_discharges', function (Blueprint $table) {
            // Patient details overrides
            if (!Schema::hasColumn('ipd_discharges', 'father_spouse')) {
                $table->string('father_spouse')->nullable()->after('discharge_status');
            }

            // Clinical Examination on Admission
            if (!Schema::hasColumn('ipd_discharges', 'bp')) {
                $table->string('bp')->nullable()->after('condition_at_admission');
            }
            if (!Schema::hasColumn('ipd_discharges', 'pulse')) {
                $table->string('pulse')->nullable()->after('bp');
            }
            if (!Schema::hasColumn('ipd_discharges', 'rr')) {
                $table->string('rr')->nullable()->after('pulse');
            }
            if (!Schema::hasColumn('ipd_discharges', 'spo2')) {
                $table->string('spo2')->nullable()->after('rr');
            }
            if (!Schema::hasColumn('ipd_discharges', 'temperature')) {
                $table->string('temperature')->nullable()->after('spo2');
            }
            if (!Schema::hasColumn('ipd_discharges', 'gcs')) {
                $table->string('gcs')->nullable()->after('temperature');
            }
            if (!Schema::hasColumn('ipd_discharges', 'systemic_exam')) {
                $table->text('systemic_exam')->nullable()->after('gcs');
            }

            // Procedures & Discharge Medications
            if (!Schema::hasColumn('ipd_discharges', 'procedures')) {
                $table->longText('procedures')->nullable()->after('investigations');
            }
            if (!Schema::hasColumn('ipd_discharges', 'discharge_medications')) {
                $table->longText('discharge_medications')->nullable()->after('procedures');
            }

            // Condition at Discharge breakdowns
            if (!Schema::hasColumn('ipd_discharges', 'general_condition')) {
                $table->string('general_condition')->nullable()->after('condition_at_discharge');
            }
            if (!Schema::hasColumn('ipd_discharges', 'consciousness')) {
                $table->string('consciousness')->nullable()->after('general_condition');
            }
            if (!Schema::hasColumn('ipd_discharges', 'mobility')) {
                $table->string('mobility')->nullable()->after('consciousness');
            }
            if (!Schema::hasColumn('ipd_discharges', 'diet')) {
                $table->string('diet')->nullable()->after('mobility');
            }

            // Follow-up & Doctor
            if (!Schema::hasColumn('ipd_discharges', 'followup_date')) {
                $table->string('followup_date')->nullable()->after('precautions_followup');
            }
            if (!Schema::hasColumn('ipd_discharges', 'followup_doctor')) {
                $table->string('followup_doctor')->nullable()->after('followup_date');
            }
            if (!Schema::hasColumn('ipd_discharges', 'special_instructions')) {
                $table->text('special_instructions')->nullable()->after('followup_doctor');
            }
            if (!Schema::hasColumn('ipd_discharges', 'treating_physician')) {
                $table->string('treating_physician')->nullable()->after('special_instructions');
            }
            if (!Schema::hasColumn('ipd_discharges', 'standard_advice_flags')) {
                $table->text('standard_advice_flags')->nullable()->after('treating_physician');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_discharges', function (Blueprint $table) {
            $table->dropColumn([
                'father_spouse',
                'bp',
                'pulse',
                'rr',
                'spo2',
                'temperature',
                'gcs',
                'systemic_exam',
                'procedures',
                'discharge_medications',
                'general_condition',
                'consciousness',
                'mobility',
                'diet',
                'followup_date',
                'followup_doctor',
                'special_instructions',
                'treating_physician',
                'standard_advice_flags',
            ]);
        });
    }
};
