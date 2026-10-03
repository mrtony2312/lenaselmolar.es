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
            $table->string('slug')->unique();
            $table->string('label');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            // Keep the historical catalog ids as primary keys so every existing
            // reference (cart, wishlist, feed "lv-{id}", quick view) stays valid.
            $table->unsignedBigInteger('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('ref')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->boolean('in_stock')->default(true);
            $table->string('color')->nullable();
            $table->string('hover_image')->nullable();
            $table->json('images')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
