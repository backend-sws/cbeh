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
        Schema::table('medicine_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_patient_id')->nullable()->after('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_bills', function (Blueprint $table) {
            $table->dropColumn('ipd_patient_id');
        });
    }
};
