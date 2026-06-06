<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_recruitments', function (Blueprint $table) {
            $table->id();
            $table->enum('candidate_type', ['staff', 'bph'])->unique();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('closed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitments');
    }
};
