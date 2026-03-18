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

    $table->foreignId('user_id')->nullable();

    $table->string('action'); 
    $table->string('model');

    $table->unsignedBigInteger('model_id')->nullable();

    $table->json('before')->nullable();
    $table->json('after')->nullable();

    $table->ipAddress('ip_address')->nullable();
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
