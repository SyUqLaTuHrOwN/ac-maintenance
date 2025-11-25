<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('technician_profiles', function (Blueprint $table) {
        $table->string('member_1_name')->nullable();
        $table->string('member_2_name')->nullable();
    });
}

public function down()
{
    Schema::table('technician_profiles', function (Blueprint $table) {
        $table->dropColumn(['member_1_name', 'member_2_name']);
    });
}
};