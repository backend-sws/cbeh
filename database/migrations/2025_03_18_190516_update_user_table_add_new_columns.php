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
       if (Schema::hasTable('users')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('uhid')->unique()->nullable();
            $table->string('role')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('address')->nullable();
            $table->text('alternate_address')->nullable();
            $table->text('remarks')->nullable();
            $table->text('allergies')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('image')->nullable();
            $table->string('adhar_number')->nullable();

        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn([
                    'first_name', 'last_name', 'role', 
                    'phone', 'dob','gender', 'facebook_url', 
                    'instagram_url', 'twitter_url', 'linkedin_url', 
                    'image'
                ]);
            });
        }
    }
};
