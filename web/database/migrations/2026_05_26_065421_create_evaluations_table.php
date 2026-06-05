<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departmentsbiro')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('evaluation_criteria')->onDelete('cascade');
            $table->integer('score');
            $table->text('notes')->nullable();

            $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();

            $table->unique(['candidate_id', 'department_id', 'criteria_id'], 'candidate_dept_crit_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
