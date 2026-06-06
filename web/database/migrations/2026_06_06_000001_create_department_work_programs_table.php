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
        Schema::create('department_work_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departmentsbiro')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('period')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['department_id', 'is_active', 'sort_order'], 'dept_work_programs_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_work_programs');
    }
};
