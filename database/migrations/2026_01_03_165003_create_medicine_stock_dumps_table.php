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
        Schema::create('medicine_stock_dumps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->integer('quantity'); // Kitna peace dump kiya
            $table->date('dump_date');
            $table->string('reason'); // Expired, Damaged, Lost etc.
            $table->decimal('loss_amount', 10, 2)->default(0); // Purchase Price * Qty (Total Nuksan)
            $table->unsignedBigInteger('dumped_by'); // Admin ID
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_stock_dumps');
    }
};
