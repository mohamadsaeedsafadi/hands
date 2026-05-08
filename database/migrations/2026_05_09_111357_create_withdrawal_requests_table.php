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
       Schema::create('withdrawal_requests', function (Blueprint $table) {

    $table->id();

    $table->foreignId('provider_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('cashier_id')
    ->nullable()
    ->constrained('cashiers')
    ->nullOnDelete();

    $table->decimal('amount', 15, 2);

    $table->decimal('commission', 15, 2);

    $table->decimal('final_amount', 15, 2);

    $table->string('shamcash_account');

    $table->enum('status', [
        'pending',
        'approved',
        'rejected'
    ])->default('pending');

    $table->text('note')->nullable();

    $table->timestamp('processed_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
