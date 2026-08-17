<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')->cascadeOnDelete();

            // who made the change
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');       // always a positive number
            $table->integer('balance_after');  // product qty after this move
            $table->string('reason')->nullable(); // e.g. "Purchase", "Sold", "Damaged", "Return"
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
