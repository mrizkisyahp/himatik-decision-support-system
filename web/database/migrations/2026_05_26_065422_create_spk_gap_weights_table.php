<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_gap_weights', function (Blueprint $table) {
            $table->id();
            $table->integer('gap')->unique();
            $table->decimal('weight', 8, 4);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_gap_weights');
    }
};
