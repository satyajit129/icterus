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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('sku')->unique()->nullable();
            $table->string('slug')->unique()->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('google_drive_link', 500)->nullable();
            $table->string('youtube_video_link', 500)->nullable();
            $table->string('banner_image')->nullable();
            $table->json('product_images')->nullable(); // Store multiple images as JSON
            $table->integer('stock')->nullable()->default(0);
            $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
            $table->timestamps();

            // Indexes for better performance
            $table->index(['category', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
