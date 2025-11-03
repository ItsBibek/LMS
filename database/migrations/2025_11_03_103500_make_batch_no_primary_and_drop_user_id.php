<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure batch_no column exists and is string (DBAL handles change if needed)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'batch_no')) {
                $table->string('batch_no')->change();
            }
        });

        // MySQL requires removing AUTO_INCREMENT before dropping the primary key
        DB::statement('ALTER TABLE `users` MODIFY `id` BIGINT UNSIGNED NOT NULL');

        // Drop existing primary key on `id`
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
        });

        // Drop the id column and promote batch_no to primary key
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id')) {
                $table->dropColumn('id');
            }
            $table->primary('batch_no');
        });
    }

    public function down(): void
    {
        // Drop PK on batch_no and restore id as auto-increment primary
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->id();
        });
    }
};
