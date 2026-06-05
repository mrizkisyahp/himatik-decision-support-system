<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->enum('candidate_type', ['staff', 'bph'])->default('staff');
            $table->string('nim')->unique();
            $table->string('nickname')->nullable();
            $table->enum('prodi', ['Teknik Informatika', 'Teknik Multimedia dan Jaringan', 'Teknik Multimedia dan Digital']);
            $table->string('kelas');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('photo_path');
            $table->text('department_choice_reason')->nullable();
            $table->text('weakness_description')->nullable();
            $table->text('contribution_plan')->nullable();
            $table->string('instagram_proof_path')->nullable();
            $table->string('youtube_proof_path')->nullable();
            $table->string('political_statement_path')->nullable();
            $table->string('candidate_signature_path')->nullable();
            $table->string('parent_signature_path')->nullable();

            $table->enum('status', ['registered', 'scheduled', 'evaluated', 'completed'])->default('registered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
