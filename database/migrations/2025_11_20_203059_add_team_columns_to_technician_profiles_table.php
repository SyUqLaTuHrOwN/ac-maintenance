<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('technician_profiles', function (Blueprint $t) {
            $t->string('team_name')->nullable()->after('user_id');
            $t->string('member_1_name')->nullable()->after('team_name');
            $t->string('member_2_name')->nullable()->after('member_1_name');
            $t->json('extra_bio')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('technician_profiles', function (Blueprint $t) {
            $t->dropColumn(['team_name', 'member_1_name', 'member_2_name', 'extra_bio']);
        });
    }
};
