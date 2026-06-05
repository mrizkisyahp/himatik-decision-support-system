<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departmentsbiro')->cascadeOnDelete();
            $table->decimal('final_score', 8, 4)->default(0);
            $table->decimal('personal_core_score', 8, 4)->default(0);
            $table->decimal('personal_secondary_score', 8, 4)->default(0);
            $table->decimal('personal_score', 8, 4)->default(0);
            $table->decimal('organizational_core_score', 8, 4)->default(0);
            $table->decimal('organizational_secondary_score', 8, 4)->default(0);
            $table->decimal('organizational_score', 8, 4)->default(0);
            $table->unsignedInteger('rank_position')->nullable();
            $table->json('calculation_details');
            $table->foreignId('calculated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->unique(['candidate_id', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_results');
    }
};
