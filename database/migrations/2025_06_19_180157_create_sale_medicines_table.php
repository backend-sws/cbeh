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
        Schema::create('sale_medicines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('medicine_bill_id');
            $table->bigInteger('medicine_id');
            $table->date('expiry_date');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('tax', 5, 2)->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0)->after('price'); // Per medicine discount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_medicines');
    }
};
