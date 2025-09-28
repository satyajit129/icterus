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
        Schema::table('facebook_leads', function (Blueprint $table) {
            $table->foreignId('expression_id')->nullable()->after('is_processed')->constrained('lead_expressions')->onDelete('set null');
            $table->index(['expression_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebook_leads', function (Blueprint $table) {
            $table->dropForeign(['expression_id']);
            $table->dropIndex(['expression_id']);
            $table->dropColumn('expression_id');
        });
    }
};
