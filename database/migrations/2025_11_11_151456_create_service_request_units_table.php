<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_request_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('unit_ac_id')->constrained('unit_acs')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['request_id','unit_ac_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('service_request_units');
    }
};