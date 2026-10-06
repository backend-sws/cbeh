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
        if (Schema::hasTable('progress_note_medicines')) {
            Schema::table('progress_note_medicines', function (Blueprint $table) {
                if (!Schema::hasColumn('progress_note_medicines', 'route')) {
                    $table->string('route')->nullable()->after('dosage');
                }
                if (!Schema::hasColumn('progress_note_medicines', 'dose_interval')) {
                    $table->string('dose_interval')->nullable()->after('route');
                }
                if (!Schema::hasColumn('progress_note_medicines', 'duration')) {
                    $table->string('duration')->nullable()->after('dose_interval');
                }
            });
        }

        if (Schema::hasTable('opd_progress_note_medicines')) {
            Schema::table('opd_progress_note_medicines', function (Blueprint $table) {
                if (!Schema::hasColumn('opd_progress_note_medicines', 'route')) {
                    $table->string('route')->nullable()->after('dosage');
                }
                if (!Schema::hasColumn('opd_progress_note_medicines', 'dose_interval')) {
                    $table->string('dose_interval')->nullable()->after('route');
                }
                if (!Schema::hasColumn('opd_progress_note_medicines', 'duration')) {
                    $table->string('duration')->nullable()->after('dose_interval');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('progress_note_medicines')) {
            Schema::table('progress_note_medicines', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('progress_note_medicines', 'route')) $columns[] = 'route';
                if (Schema::hasColumn('progress_note_medicines', 'dose_interval')) $columns[] = 'dose_interval';
                if (Schema::hasColumn('progress_note_medicines', 'duration')) $columns[] = 'duration';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('opd_progress_note_medicines')) {
            Schema::table('opd_progress_note_medicines', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('opd_progress_note_medicines', 'route')) $columns[] = 'route';
                if (Schema::hasColumn('opd_progress_note_medicines', 'dose_interval')) $columns[] = 'dose_interval';
                if (Schema::hasColumn('opd_progress_note_medicines', 'duration')) $columns[] = 'duration';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
