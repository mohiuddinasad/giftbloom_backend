<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // self-reference -> null = parent category, filled = sub-category
            $table->foreignId('parent_id')->nullable()
                ->constrained('categories')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique(); // used for both SEO url AND route binding
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->boolean('status')->default(true); // active/inactive
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
