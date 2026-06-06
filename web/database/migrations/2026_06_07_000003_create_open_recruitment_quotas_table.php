<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_recruitment_quotas', function (Blueprint $table) {
            $table->id();
            $table->enum('candidate_type', ['staff', 'bph']);
            $table->foreignId('department_id')->constrained('departmentsbiro')->cascadeOnDelete();
            $table->unsignedInteger('quota')->default(0);
            $table->timestamps();

            $table->unique(['candidate_type', 'department_id'], 'oprec_quota_type_department_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitment_quotas');
    }
};
