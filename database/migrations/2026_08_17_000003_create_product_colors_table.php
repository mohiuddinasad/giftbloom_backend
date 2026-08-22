<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')->cascadeOnDelete();

            // nullable: many products on this site won't have colors at all,
            // this row can just act as a plain image group when color_name is empty
            $table->string('color_name')->nullable();
            $table->string('color_code', 20)->nullable();
            $table->integer('qty')->default(0); // optional: per-color stock
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
