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
        Schema::create('departmentsbiro', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('personal_aspect_weight', 5, 2)->default(60.00);
            $table->decimal('organizational_aspect_weight', 5, 2)->default(40.00);
            $table->decimal('core_factor_weight', 5, 2)->default(60.00);
            $table->decimal('secondary_factor_weight', 5, 2)->default(40.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departmentsbiro');
    }
};
