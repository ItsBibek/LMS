<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'reserved_at')) {
                $table->timestamp('reserved_at')->useCurrent()->after('Accession_Number');
            }
            if (!Schema::hasColumn('reservations', 'status')) {
                $table->string('status')->default('pending')->after('reserved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('reservations', 'reserved_at')) {
                $table->dropColumn('reserved_at');
            }
        });
    }
};
