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
       Schema::create('tickets', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->enum('type', ['complaint', 'suggestion']);
    $table->string('title');
    $table->text('description');
    $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
    $table->enum('status', ['open', 'in_progress', 'waiting_user', 'resolved', 'closed'])->default('open');
    $table->integer('version')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
