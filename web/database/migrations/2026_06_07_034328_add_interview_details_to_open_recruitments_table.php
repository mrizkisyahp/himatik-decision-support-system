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
        Schema::table('open_recruitments', function (Blueprint $table) {
            $table->string('interview_location')->nullable()->after('status');
            $table->text('interview_requirements')->nullable()->after('interview_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('open_recruitments', function (Blueprint $table) {
            $table->dropColumn(['interview_location', 'interview_requirements']);
        });
    }
};
