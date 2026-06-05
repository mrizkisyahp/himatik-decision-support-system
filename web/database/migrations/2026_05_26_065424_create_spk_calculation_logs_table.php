<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_calculation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departmentsbiro')->nullOnDelete();
            $table->enum('trigger_type', ['manual', 'auto', 'reset'])->default('manual');
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['success', 'partial', 'failed'])->default('success');
            $table->unsignedInteger('candidates_count')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_calculation_logs');
    }
};
