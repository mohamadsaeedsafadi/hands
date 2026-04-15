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
       Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    $table->string('action'); // create, update, delete, login...
    $table->string('model_type')->nullable(); // App\Models\ServiceOffer
    $table->unsignedBigInteger('model_id')->nullable();
$table->string('event_type')->nullable(); // offer, payment, auth, chat
$table->string('severity')->default('info'); // info, warning, critical
$table->string('url')->nullable();
$table->string('method')->nullable();
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();

    $table->ipAddress('ip')->nullable();
    $table->text('user_agent')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
