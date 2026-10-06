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
        Schema::create('opd_progress_note_addendums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opd_progress_note_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('staff_name')->nullable();
            $table->dateTime('date_time');
            $table->text('note');
            $table->timestamps();

            $table->foreign('opd_progress_note_id')->references('id')->on('opd_progress_notes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_progress_note_addendums');
    }
};
