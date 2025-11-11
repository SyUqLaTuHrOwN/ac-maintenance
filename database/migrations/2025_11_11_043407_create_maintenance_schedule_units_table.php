<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_schedule_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')
                  ->constrained('maintenance_schedules')
                  ->cascadeOnDelete();
            $table->foreignId('unit_ac_id')
                  ->constrained('unit_acs')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['schedule_id', 'unit_ac_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedule_units');
    }
};
