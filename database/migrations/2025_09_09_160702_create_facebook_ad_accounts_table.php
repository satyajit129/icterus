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
        Schema::create('facebook_ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('ad_account_id')->unique(); // account_id without act_
            $table->string('ad_account_gid')->unique(); // id with act_
            $table->string('name')->nullable();
            $table->string('currency', 8)->nullable();
            $table->string('timezone_name', 64)->nullable();
            $table->string('business_id')->nullable();
            $table->string('business_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_ad_accounts');
    }
};
