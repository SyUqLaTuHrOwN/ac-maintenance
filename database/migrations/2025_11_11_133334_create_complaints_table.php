<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('complaints', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete(); // user client pembuat
            $t->foreignId('schedule_id')->nullable()->constrained('maintenance_schedules')->nullOnDelete();
            $t->string('subject');
            $t->text('message');
            $t->string('priority')->default('normal'); // low|normal|high
            $t->string('status')->default('open');     // open|in_progress|resolved|closed
            $t->json('attachments')->nullable();
            $t->timestamp('responded_at')->nullable();
            $t->timestamp('closed_at')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('complaints'); }
};
