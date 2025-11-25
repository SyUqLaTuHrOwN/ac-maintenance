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
    Schema::table('maintenance_schedule_units', function (Blueprint $table) {
        $table->integer('requested_units')->default(1);
    });
}

public function down()
{
    Schema::table('maintenance_schedule_units', function (Blueprint $table) {
        $table->dropColumn('requested_units');
    });
}
};
