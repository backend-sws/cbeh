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
        Schema::table('purchased_medicines', function (Blueprint $table) {
            // ✅ Add price columns if they don't exist
            if (!Schema::hasColumn('purchased_medicines', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('purchased_medicines', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('purchase_price');
            }
            if (!Schema::hasColumn('purchased_medicines', 'available_quantity')) {
                $table->integer('available_quantity')->default(0)->after('sale_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_medicines', function (Blueprint $table) {
            if (Schema::hasColumn('purchased_medicines', 'purchase_price')) {
                $table->dropColumn('purchase_price');
            }
            if (Schema::hasColumn('purchased_medicines', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
            if (Schema::hasColumn('purchased_medicines', 'available_quantity')) {
                $table->dropColumn('available_quantity');
            }
        });
    }
};
