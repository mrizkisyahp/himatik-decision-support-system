<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            // Add registration type: staff or bph
            $table->enum('candidate_type', ['staff', 'bph'])->default('staff')->after('user_id');

            // BPH doesn't need statement_letter or social_media_proof, so make them nullable
            $table->string('statement_letter_path')->nullable()->change();
            $table->string('social_media_proof_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('candidate_type');
            $table->string('statement_letter_path')->nullable(false)->change();
            $table->string('social_media_proof_path')->nullable(false)->change();
        });
    }
};
