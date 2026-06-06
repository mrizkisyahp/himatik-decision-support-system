<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_recruitment_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('open_recruitment_id')->constrained('open_recruitments')->cascadeOnDelete();
            $table->dateTime('old_starts_at')->nullable();
            $table->dateTime('old_ends_at')->nullable();
            $table->dateTime('new_starts_at')->nullable();
            $table->dateTime('new_ends_at')->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('extended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitment_extensions');
    }
};
