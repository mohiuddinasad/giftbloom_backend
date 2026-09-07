<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique(); // used for both SEO url AND route binding
            $table->string('sku')->unique();
 
            // 1. product type: gift item / gift package / letter
            $table->enum('type', ['gift_item', 'gift_package', 'letter','gift_box','extra_gift','sweet','wraping'])
                ->default('gift_item');

            // 2. who the gift is for
            $table->enum('gift_for', ['man', 'women'])->nullable();

            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();

            // Quill.js rich text output stored as HTML
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();

            // qty is edited directly on the product edit form
            $table->integer('qty')->default(0);

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->boolean('status')->default(true); // publish/unpublish
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
