<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Student Advisor role if it doesn't exist
        DB::table('roles')->insertOrIgnore([
            'name' => 'Student Advisor',
            'slug' => 'student-advisor',
            'description' => 'Role for users who can be assigned leads',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove Student Advisor role
        DB::table('roles')->where('slug', 'student-advisor')->delete();
    }
};
