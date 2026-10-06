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
        Schema::table('pathology_bills', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('amount'); // Kitna paisa diya
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount'); // Kitna baki hai
            $table->string('payment_status')->default('unpaid')->after('due_amount'); // paid, partial, unpaid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology_bills', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'due_amount', 'payment_status']);
        });
    }
};
