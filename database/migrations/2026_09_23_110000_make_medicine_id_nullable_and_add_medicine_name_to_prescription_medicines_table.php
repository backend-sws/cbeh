<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make medicine_id nullable so custom typed medicines can be stored without foreign key requirement
        try {
            DB::statement('ALTER TABLE `prescription_medicines` MODIFY `medicine_id` BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if already nullable
        }

        // Add medicine_name column if it does not already exist
        if (!Schema::hasColumn('prescription_medicines', 'medicine_name')) {
            Schema::table('prescription_medicines', function (Blueprint $table) {
                $table->string('medicine_name')->nullable()->after('medicine_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('prescription_medicines', 'medicine_name')) {
            Schema::table('prescription_medicines', function (Blueprint $table) {
                $table->dropColumn('medicine_name');
            });
        }
    }
};
