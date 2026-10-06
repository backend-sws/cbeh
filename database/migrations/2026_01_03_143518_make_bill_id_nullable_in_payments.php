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
        Schema::table('medicine_bill_payments', function (Blueprint $table) {
            // 1. Bill ID ko nullable banayein (Direct payment ke liye)
            $table->unsignedBigInteger('medicine_bill_id')->nullable()->change();

            // 2. IPD Patient ID add karein (Agar pehle se nahi hai)
            if (!Schema::hasColumn('medicine_bill_payments', 'ipd_patient_id')) {
                $table->unsignedBigInteger('ipd_patient_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
