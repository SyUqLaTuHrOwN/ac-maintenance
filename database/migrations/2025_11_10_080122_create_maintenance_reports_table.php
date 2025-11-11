<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('maintenance_schedules')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->integer('units_serviced')->default(0);
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('status')->default('draft'); // draft|submitted|revisi|disetujui
            $table->foreignId('verified_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('maintenance_reports'); }
};
