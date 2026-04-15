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
       Schema::create('service_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
    $table->text('question');
    $table->enum('type', ['text', 'number', 'select', 'multi_select', 'image']);
    $table->json('options')->nullable(); 
    $table->boolean('is_required')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_questions');
    }
};
