<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('user_batch_no');
            $table->string('Accession_Number');
            $table->timestamp('reserved_at')->useCurrent();
            // pending, issued, declined, expired
            $table->string('status')->default('pending');

            $table->index(['Accession_Number']);
            $table->index(['user_batch_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
