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
        Schema::table('ipd_patients', function (Blueprint $table) {
            if (!Schema::hasColumn('ipd_patients', 'weight')) {
                $table->string('weight')->nullable()->after('height');
            }
            if (!Schema::hasColumn('ipd_patients', 'bp')) {
                $table->string('bp')->nullable()->after('blood_pressure');
            }
            if (!Schema::hasColumn('ipd_patients', 'pulse')) {
                $table->string('pulse')->nullable()->after('bp');
            }
            if (!Schema::hasColumn('ipd_patients', 'temperature')) {
                $table->string('temperature')->nullable()->after('pulse');
            }
            if (!Schema::hasColumn('ipd_patients', 'SpO2')) {
                $table->string('SpO2')->nullable()->after('temperature');
            }
            if (!Schema::hasColumn('ipd_patients', 'respiratory_rate')) {
                $table->string('respiratory_rate')->nullable()->after('SpO2');
            }
            if (!Schema::hasColumn('ipd_patients', 'rbs')) {
                $table->string('rbs')->nullable()->after('respiratory_rate');
            }
            if (!Schema::hasColumn('ipd_patients', 'general_appearance')) {
                $table->string('general_appearance')->nullable()->after('rbs');
            }
            if (!Schema::hasColumn('ipd_patients', 'cvs')) {
                $table->string('cvs')->nullable()->after('general_appearance');
            }
            if (!Schema::hasColumn('ipd_patients', 'rs')) {
                $table->string('rs')->nullable()->after('cvs');
            }
            if (!Schema::hasColumn('ipd_patients', 'pa')) {
                $table->string('pa')->nullable()->after('rs');
            }
            if (!Schema::hasColumn('ipd_patients', 'cns')) {
                $table->string('cns')->nullable()->after('pa');
            }
            if (!Schema::hasColumn('ipd_patients', 'other_systems')) {
                $table->string('other_systems')->nullable()->after('cns');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_patients', function (Blueprint $table) {
            $table->dropColumn([
                'weight',
                'bp',
                'pulse',
                'temperature',
                'SpO2',
                'respiratory_rate',
                'rbs',
                'general_appearance',
                'cvs',
                'rs',
                'pa',
                'cns',
                'other_systems'
            ]);
        });
    }
};
