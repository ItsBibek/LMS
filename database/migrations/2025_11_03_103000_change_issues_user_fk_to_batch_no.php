<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add new column user_batch_no
        Schema::table('issues', function (Blueprint $table) {
            if (!Schema::hasColumn('issues', 'user_batch_no')) {
                $table->string('user_batch_no')->nullable()->after('id');
            }
        });

        // 2) Backfill from existing user_id -> users.id mapping
        DB::statement('UPDATE issues i JOIN users u ON i.user_id = u.id SET i.user_batch_no = u.batch_no');

        // 3) Make the new column NOT NULL, add index and FK
        Schema::table('issues', function (Blueprint $table) {
            $table->string('user_batch_no')->nullable(false)->change();
            $table->index('user_batch_no');
            $table->foreign('user_batch_no')->references('batch_no')->on('users')->onDelete('cascade');
        });

        // 4) Drop old FK and column user_id
        Schema::table('issues', function (Blueprint $table) {
            if (Schema::hasColumn('issues', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }

    public function down(): void
    {
        // 1) Recreate user_id and backfill from users by batch_no
        Schema::table('issues', function (Blueprint $table) {
            if (!Schema::hasColumn('issues', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        DB::statement('UPDATE issues i JOIN users u ON i.user_batch_no = u.batch_no SET i.user_id = u.id');

        // 2) Drop FK and index on user_batch_no, then drop the column
        Schema::table('issues', function (Blueprint $table) {
            if (Schema::hasColumn('issues', 'user_batch_no')) {
                $table->dropForeign(['user_batch_no']);
                $table->dropIndex(['user_batch_no']);
                $table->dropColumn('user_batch_no');
            }
        });

        // 3) Reinstate FK to users.id
        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
