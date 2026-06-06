<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_recruitment_quota_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('candidate_type', ['staff', 'bph']);
            $table->foreignId('department_id')->constrained('departmentsbiro')->cascadeOnDelete();
            $table->unsignedInteger('old_quota')->nullable();
            $table->unsignedInteger('new_quota');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitment_quota_logs');
    }
};
