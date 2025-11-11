<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('feedback', function (Blueprint $t) {
            $t->id();
            $t->foreignId('report_id')->constrained('maintenance_reports')->cascadeOnDelete();
            $t->foreignId('client_user_id')->constrained('users')->cascadeOnDelete(); // user client pemberi rating
            $t->unsignedTinyInteger('rating'); // 1..5
            $t->text('comment')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('feedback'); }
};
