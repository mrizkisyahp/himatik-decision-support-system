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
            $table->string('nim')->unique();
            $table->enum('prodi', ['Teknik Informatika', 'Teknik Multimedia dan Jaringan', 'Teknik Multimedia dan Digital']);
            $table->string('kelas');
            $table->string('phone');

            $table->foreignId('first_choice_id')->constrained('departmentsbiro')->onDelete('cascade');
            $table->foreignId('second_choice_id')->constrained('departmentsbiro')->onDelete('cascade');

            $table->string('recruitment_form_path');
            $table->string('photo_path');
            $table->string('statement_letter_path');
            $table->string('social_media_proof_path');

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
