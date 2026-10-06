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
            if (!Schema::hasColumn('ipd_discharges', 'condition_at_admission')) {
                $table->text('condition_at_admission')->nullable()->after('history');
            }
            if (!Schema::hasColumn('ipd_discharges', 'condition_at_discharge')) {
                $table->text('condition_at_discharge')->nullable()->after('treatment_given');
            }
            if (!Schema::hasColumn('ipd_discharges', 'precautions_followup')) {
                $table->text('precautions_followup')->nullable()->after('advice');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_discharges', function (Blueprint $table) {
            $table->dropColumn(['condition_at_admission', 'condition_at_discharge', 'precautions_followup']);
        });
    }
};
