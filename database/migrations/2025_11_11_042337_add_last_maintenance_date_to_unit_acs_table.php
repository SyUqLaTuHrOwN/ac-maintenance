<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_acs', function (Blueprint $table) {
            // kalau kolom install_date belum ada, hapus baris comment ini dan tambahkan:
            // $table->date('install_date')->nullable()->after('capacity_btu');

            $table->date('last_maintenance_date')->nullable()->after('install_date');
        });
    }

    public function down(): void
    {
        Schema::table('unit_acs', function (Blueprint $table) {
            $table->dropColumn('last_maintenance_date');
        });
    }
};
