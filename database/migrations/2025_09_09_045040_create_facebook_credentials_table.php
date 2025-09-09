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
        Schema::create('facebook_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->nullable();
            $table->text('app_secret')->nullable();
            $table->string('agency_id')->nullable();
            $table->text('user_access_token')->nullable();
            $table->string('api_url')->nullable();
            $table->string('version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_credentials');
    }
};
