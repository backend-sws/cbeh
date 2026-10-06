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
        Schema::table('opd_patients', function (Blueprint $table) {
            $table->string('general_appearance')->nullable()->after('respiratory_rate');
            $table->string('cvs')->nullable()->after('general_appearance');
            $table->string('rs')->nullable()->after('cvs');
            $table->string('pa')->nullable()->after('rs');
            $table->string('cns')->nullable()->after('pa');
            $table->string('other_systems')->nullable()->after('cns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_patients', function (Blueprint $table) {
            $table->dropColumn([
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
