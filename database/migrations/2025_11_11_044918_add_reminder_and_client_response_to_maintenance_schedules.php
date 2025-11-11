<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('notes');
            $table->string('client_response', 40)->nullable()->after('reminder_sent_at');
            // pending|confirmed|reschedule_requested|cancelled_by_client
            $table->timestamp('client_response_at')->nullable()->after('client_response');
            $table->timestamp('client_requested_date')->nullable()->after('client_response_at');
            $table->text('client_response_note')->nullable()->after('client_requested_date');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_sent_at','client_response','client_response_at',
                'client_requested_date','client_response_note'
            ]);
        });
    }
};
