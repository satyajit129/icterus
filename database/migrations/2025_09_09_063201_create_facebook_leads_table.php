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
        Schema::create('facebook_leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->unique();
            $table->string('form_id');
            $table->string('page_id');
            $table->datetime('created_time');
            $table->json('field_data'); // Store all form field data as JSON
            $table->json('extracted_fields'); // Store commonly used fields for easy filtering
            $table->boolean('is_processed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('form_id')->references('form_id')->on('facebook_leadgen_forms')->onDelete('cascade');
            $table->foreign('page_id')->references('page_id')->on('facebook_pages')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['form_id', 'created_time']);
            $table->index(['page_id', 'created_time']);
            $table->index(['created_time']);
            $table->index(['is_processed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_leads');
    }
};
