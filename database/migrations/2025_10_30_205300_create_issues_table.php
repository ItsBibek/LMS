<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('Accession_Number'); // references books.Accession_Number (string)
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->decimal('fine', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['Accession_Number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
