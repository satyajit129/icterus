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
        Schema::create('facebook_leadgen_forms', function (Blueprint $table) {
            $table->id();
            $table->string('form_id')->unique();
            $table->string('page_id');
            $table->string('name');
            $table->string('locale', 10);
            $table->enum('status', ['ACTIVE', 'ARCHIVED', 'DELETED'])->default('ACTIVE');
            $table->text('description')->nullable();
            $table->json('form_data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('page_id')->references('page_id')->on('facebook_pages')->onDelete('cascade');
            $table->index(['page_id', 'status']);
            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_leadgen_forms');
    }
};
