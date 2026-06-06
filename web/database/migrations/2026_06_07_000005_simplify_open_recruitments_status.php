<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('open_recruitments')
            ->where('status', 'active')
            ->update(['status' => 'open']);

        DB::table('open_recruitments')
            ->where('status', 'draft')
            ->update(['status' => 'closed']);

        Schema::table('open_recruitments', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });

        DB::statement("ALTER TABLE open_recruitments MODIFY status ENUM('open', 'closed') NOT NULL DEFAULT 'closed'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE open_recruitments MODIFY status ENUM('draft', 'active', 'closed') NOT NULL DEFAULT 'draft'");

        Schema::table('open_recruitments', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('status');
        });

        DB::table('open_recruitments')
            ->where('status', 'open')
            ->update([
                'status' => 'active',
                'is_public' => true,
            ]);
    }
};
