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
        Schema::create('lead_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('facebook_leads')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('assignment_type', ['bulk', 'individual'])->default('individual');
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            // Ensure one lead can only be assigned to one user at a time
            $table->unique('lead_id');

            // Indexes for better performance
            $table->index(['assigned_to', 'assigned_at']);
            $table->index(['assigned_by', 'assigned_at']);
            $table->index(['assignment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_assignments');
    }
};
