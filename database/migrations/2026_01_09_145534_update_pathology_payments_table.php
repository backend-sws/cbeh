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
        Schema::table('pathology_payments', function (Blueprint $table) {
            // Note: Iske liye 'doctrine/dbal' install hona chahiye, agar error aaye to batana
            // $table->unsignedBigInteger('bill_id')->nullable()->change();

            // 2. New Columns Add karein
            // $table->integer('ipd_patient_id')->nullable()->after('bill_id'); // Link IPD Patient
           
            $table->string('note')->nullable()->after('payment_date'); // Notes/Remarks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_payments', function (Blueprint $table) {
            // $table->unsignedBigInteger('bill_id')->nullable(false)->change();
            $table->dropColumn([  'note']);
        });
    }
};
