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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine');
            $table->unsignedBigInteger('brand');
            $table->unsignedBigInteger('category');
            $table->string('salt_composition')->nullable();
            $table->decimal('buying_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('quantity')->nullable();
            $table->integer('available_quantity')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('brand')->references('id')->on('medicine_brands')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('medicine_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('medicines', function (Blueprint $table) {
            $table->dropForeign(['brand']);
            $table->dropForeign(['category']);
        });
        Schema::dropIfExists('medicines');
    }
};
