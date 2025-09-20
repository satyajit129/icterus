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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_name')->nullable();
            $table->string('website_email')->nullable();
            $table->text('copy_right_text')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->text('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('featured_products_title')->nullable();
            $table->text('featured_products_subtitle')->nullable();
            $table->text('fast_delivery_title')->nullable();
            $table->text('fast_delivery_description')->nullable();
            $table->text('quality_guarantee_title')->nullable();
            $table->text('quality_guarantee_description')->nullable();
            $table->text('support_title')->nullable();
            $table->text('support_description')->nullable();
            $table->string('bkash_merchant_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
