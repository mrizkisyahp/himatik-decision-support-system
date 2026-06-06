<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('open_recruitment_quotas', 'open_recruitment_id')) {
            Schema::table('open_recruitment_quotas', function (Blueprint $table) {
                if (!Schema::hasColumn('open_recruitment_quotas', 'candidate_type')) {
                    $table->enum('candidate_type', ['staff', 'bph'])->nullable()->after('id');
                }
            });

            DB::statement("
                UPDATE open_recruitment_quotas oq
                JOIN open_recruitments ore ON ore.id = oq.open_recruitment_id
                SET oq.candidate_type = ore.candidate_type
            ");

            DB::table('open_recruitment_quotas')
                ->whereNull('candidate_type')
                ->update(['candidate_type' => 'staff']);

            Schema::table('open_recruitment_quotas', function (Blueprint $table) {
                $table->dropForeign(['open_recruitment_id']);
                $table->dropUnique('oprec_quota_unique');
                $table->dropColumn('open_recruitment_id');
            });

            DB::statement("ALTER TABLE open_recruitment_quotas MODIFY candidate_type ENUM('staff', 'bph') NOT NULL");

            Schema::table('open_recruitment_quotas', function (Blueprint $table) {
                $table->unique(['candidate_type', 'department_id'], 'oprec_quota_type_department_unique');
            });
        }

        if (Schema::hasColumn('open_recruitment_quota_logs', 'open_recruitment_id')) {
            Schema::table('open_recruitment_quota_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('open_recruitment_quota_logs', 'candidate_type')) {
                    $table->enum('candidate_type', ['staff', 'bph'])->nullable()->after('id');
                }
            });

            DB::statement("
                UPDATE open_recruitment_quota_logs oql
                JOIN open_recruitments ore ON ore.id = oql.open_recruitment_id
                SET oql.candidate_type = ore.candidate_type
            ");

            DB::table('open_recruitment_quota_logs')
                ->whereNull('candidate_type')
                ->update(['candidate_type' => 'staff']);

            Schema::table('open_recruitment_quota_logs', function (Blueprint $table) {
                $table->dropForeign(['open_recruitment_id']);
                $table->dropColumn('open_recruitment_id');
            });

            DB::statement("ALTER TABLE open_recruitment_quota_logs MODIFY candidate_type ENUM('staff', 'bph') NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('open_recruitment_quotas', function (Blueprint $table) {
            $table->dropUnique('oprec_quota_type_department_unique');
            $table->foreignId('open_recruitment_id')->nullable()->after('id')->constrained('open_recruitments')->cascadeOnDelete();
        });

        DB::statement("
            UPDATE open_recruitment_quotas oq
            JOIN open_recruitments ore ON ore.candidate_type = oq.candidate_type
            SET oq.open_recruitment_id = ore.id
        ");

        Schema::table('open_recruitment_quotas', function (Blueprint $table) {
            $table->dropColumn('candidate_type');
            $table->unique(['open_recruitment_id', 'department_id'], 'oprec_quota_unique');
        });

        Schema::table('open_recruitment_quota_logs', function (Blueprint $table) {
            $table->foreignId('open_recruitment_id')->nullable()->after('id')->constrained('open_recruitments')->cascadeOnDelete();
        });

        DB::statement("
            UPDATE open_recruitment_quota_logs oql
            JOIN open_recruitments ore ON ore.candidate_type = oql.candidate_type
            SET oql.open_recruitment_id = ore.id
        ");

        Schema::table('open_recruitment_quota_logs', function (Blueprint $table) {
            $table->dropColumn('candidate_type');
        });
    }
};
