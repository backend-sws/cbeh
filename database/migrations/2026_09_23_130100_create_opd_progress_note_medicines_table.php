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
        Schema::create('opd_progress_note_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_progress_note_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('medicine_id')->nullable();
            $table->string('medicine_name');
            $table->string('dosage')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->foreign('opd_progress_note_id')->references('id')->on('opd_progress_notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_progress_note_medicines');
    }
};
