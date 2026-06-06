<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First drop the old pivot table
        Schema::dropIfExists('interviewer_schedule');

        // Since the table is populated possibly with old data that doesn't match new constraints,
        // it's safer to truncate it before altering to avoid not-null constraint errors.
        // We temporarily disable foreign key checks to truncate.
        Schema::disableForeignKeyConstraints();
        DB::table('candidate_interview_schedules')->truncate();
        DB::table('interview_schedules')->truncate();
        Schema::enableForeignKeyConstraints();

        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->dropColumn(['session_name', 'scheduled_at', 'location', 'is_active']);
            
            $table->date('date')->after('department_id');
            $table->time('start_time')->after('date');
            $table->time('end_time')->after('start_time');
            $table->boolean('is_blocked')->default(false)->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->dropColumn(['date', 'start_time', 'end_time', 'is_blocked']);
            
            $table->string('session_name');
            $table->dateTime('scheduled_at');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('interviewer_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('interview_schedule_id')->constrained('interview_schedules')->onDelete('cascade');
            $table->timestamps();
        });
    }
};
