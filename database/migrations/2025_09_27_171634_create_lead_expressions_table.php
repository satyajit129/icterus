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
        Schema::create('lead_expressions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Expression name (e.g., 'Interested', 'Not Interested')
            $table->string('color_class'); // Bootstrap color class (e.g., 'bg-success', 'bg-danger')
            $table->string('text_color', 20)->default('text-white'); // Text color class
            $table->text('description')->nullable(); // Optional description
            $table->boolean('is_active')->default(true); // Enable/disable expression
            $table->integer('sort_order')->default(0); // For ordering expressions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_expressions');
    }
};
