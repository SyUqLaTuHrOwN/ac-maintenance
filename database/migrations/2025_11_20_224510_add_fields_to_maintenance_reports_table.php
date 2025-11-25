<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenance_reports','report_date')) {
                $table->date('report_date')->nullable()->after('schedule_id');
            }
            if (!Schema::hasColumn('maintenance_reports','units_done')) {
                $table->unsignedInteger('units_done')->default(0)->after('report_date');
            }
            if (!Schema::hasColumn('maintenance_reports','photos_start')) {
                $table->json('photos_start')->nullable()->after('units_done');
            }
            if (!Schema::hasColumn('maintenance_reports','photos_finish')) {
                $table->json('photos_finish')->nullable()->after('photos_start');
            }
            if (!Schema::hasColumn('maintenance_reports','photos_extra')) {
                $table->json('photos_extra')->nullable()->after('photos_finish');
            }
            if (!Schema::hasColumn('maintenance_reports','invoice_path')) {
                $table->string('invoice_path')->nullable()->after('photos_extra');
            }
            if (!Schema::hasColumn('maintenance_reports','notes')) {
                $table->text('notes')->nullable()->after('invoice_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_reports', function (Blueprint $table) {
            $table->dropColumn([
                'report_date',
                'units_done',
                'photos_start',
                'photos_finish',
                'photos_extra',
                'invoice_path',
                'notes',
            ]);
        });
    }
};
