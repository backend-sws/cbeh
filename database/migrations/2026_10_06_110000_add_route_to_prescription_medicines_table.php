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
        if (Schema::hasTable('prescription_medicines') && !Schema::hasColumn('prescription_medicines', 'route')) {
            Schema::table('prescription_medicines', function (Blueprint $table) {
                $table->string('route')->nullable()->after('dosage');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('prescription_medicines') && Schema::hasColumn('prescription_medicines', 'route')) {
            Schema::table('prescription_medicines', function (Blueprint $table) {
                $table->dropColumn('route');
            });
        }
    }
};
