<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {

            if (!Schema::hasColumn('maintenance_schedules', 'client_approved_at')) {
                $table->timestamp('client_approved_at')->nullable()->after('updated_at');
            }

            if (!Schema::hasColumn('maintenance_schedules', 'client_approved_by')) {
                $table->unsignedBigInteger('client_approved_by')->nullable()->after('client_approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->dropColumn(['client_approved_at', 'client_approved_by']);
        });
    }
};
