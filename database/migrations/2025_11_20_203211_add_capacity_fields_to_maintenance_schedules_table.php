<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->unsignedInteger('total_units')
                  ->nullable()
                  ->after('location_id');

            $table->unsignedInteger('daily_capacity')
                  ->nullable()
                  ->after('total_units');

            $table->unsignedInteger('estimated_days')
                  ->nullable()
                  ->after('daily_capacity');

            $table->unsignedInteger('progress_units')
                  ->default(0)
                  ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->dropColumn(['total_units','daily_capacity','estimated_days','progress_units']);
        });
    }
};
