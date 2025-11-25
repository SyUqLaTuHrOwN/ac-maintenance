<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_acs', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_acs', 'units_count')) {
                $table->unsignedInteger('units_count')->default(1)->after('capacity_btu');
            }

            if (!Schema::hasColumn('unit_acs', 'service_period_months')) {
                $table->unsignedInteger('service_period_months')->nullable()->after('units_count');
            }

            if (!Schema::hasColumn('unit_acs', 'services_per_year')) {
                $table->unsignedInteger('services_per_year')->nullable()->after('service_period_months');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_acs', function (Blueprint $table) {
            $table->dropColumn([
                'units_count',
                'service_period_months',
                'services_per_year'
            ]);
        });
    }
};
