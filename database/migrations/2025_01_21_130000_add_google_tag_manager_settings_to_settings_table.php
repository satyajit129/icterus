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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('gtm_id')->nullable();
            $table->boolean('gtm_enabled')->default(false);
            $table->text('gtm_events')->nullable(); // JSON array of enabled events
            $table->text('gtm_custom_dimensions')->nullable(); // JSON object for custom dimensions
            $table->text('gtm_ecommerce_settings')->nullable(); // JSON object for ecommerce settings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'gtm_id',
                'gtm_enabled',
                'gtm_events',
                'gtm_custom_dimensions',
                'gtm_ecommerce_settings'
            ]);
        });
    }
};
