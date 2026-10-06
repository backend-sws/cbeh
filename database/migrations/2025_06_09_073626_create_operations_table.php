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
    Schema::create('operations', function (Blueprint $table) {
        $table->id();
        $table->string('reference_id')->nullable();

        // Patient Foreign Key
        $table->unsignedBigInteger('patient_id');
         $table->string('category_id')->nullable();
         $table->string('operation_id')->nullable();
         
        

        // Operation Category Foreign Key
       $table->string('category_id');
       

        // // Operation Name Foreign Key
        $table->string('operation_id');
        //       ->constrained('operation_names')
        //       ->onDelete('cascade');

        $table->date('operation_date');

        // Doctor Foreign Key
        $table->string('doctor_id');

        // Optional Assistant and OT Staff
        $table->string('assistant_consultant1')->nullable();
        $table->string('assistant_consultant2')->nullable();
        $table->string('anesthetist')->nullable();
        $table->string('anesthesia_type')->nullable();
        $table->string('ot_technician')->nullable();
        $table->string('ot_assistant')->nullable();

        $table->text('remark')->nullable();
        $table->text('result')->nullable();
        $table->timestamps();

        // Optional: Add foreign key constraints if these IDs link to other tables
        // Example:
        // $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        // $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        // Repeat for other staff-related foreign keys if applicable
    });
}




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oprations');
    }
};
