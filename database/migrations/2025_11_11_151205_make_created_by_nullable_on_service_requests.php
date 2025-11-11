<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // jika belum ada kolom, buat sebagai nullable
            if (!Schema::hasColumn('service_requests', 'created_by')) {
                $table->foreignId('created_by')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete()
                      ->after('status');
            } else {
                // ubah jadi nullable (butuh doctrine/dbal jika change())
                // composer require doctrine/dbal
                $table->foreignId('created_by')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // balikkan kalau perlu (opsional)
            // $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
