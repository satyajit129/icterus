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
            $table->string('facebook_pixel_id')->nullable();
            $table->boolean('facebook_pixel_enabled')->default(false);
            $table->text('facebook_pixel_events')->nullable(); // JSON array of enabled events
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_pixel_id',
                'facebook_pixel_enabled',
                'facebook_pixel_events'
            ]);
        });
    }
};
