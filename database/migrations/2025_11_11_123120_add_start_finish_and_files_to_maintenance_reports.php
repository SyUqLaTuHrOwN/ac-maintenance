<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_reports', function (Blueprint $table) {
            // waktu mulai & selesai
            $table->timestamp('started_at')->nullable()->after('technician_id');
            $table->timestamp('finished_at')->nullable()->after('started_at');

            // file bukti
            $table->string('start_photo_path')->nullable()->after('finished_at');
            $table->string('end_photo_path')->nullable()->after('start_photo_path');
            $table->string('receipt_path')->nullable()->after('end_photo_path'); // nota/bukti servis
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_reports', function (Blueprint $table) {
            $table->dropColumn([
                'started_at','finished_at',
                'start_photo_path','end_photo_path','receipt_path',
            ]);
        });
    }
};
