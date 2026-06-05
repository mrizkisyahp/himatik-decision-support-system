<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_departmentsbiro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->cascadeOnDelete();
            $table->foreignId('departmentsbiro_id')->constrained('departmentsbiro')->cascadeOnDelete();
            $table->unsignedTinyInteger('choice_order');
            $table->timestamps();

            $table->unique(['candidate_id', 'choice_order']);
            $table->unique(['candidate_id', 'departmentsbiro_id'], 'candidate_department_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_departmentsbiro');
    }
};
