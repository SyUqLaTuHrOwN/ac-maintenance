<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // user client
            $t->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $t->json('unit_ids')->nullable(); // array unit terkait
            $t->dateTime('requested_at')->nullable();
            $t->dateTime('preferred_date')->nullable();
            $t->text('note')->nullable();
            $t->string('status')->default('submitted'); // submitted|approved|scheduled|rejected|cancelled
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('service_requests'); }
};
