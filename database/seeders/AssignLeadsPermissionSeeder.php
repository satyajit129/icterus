<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignLeadsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert assign_leads permission
        DB::table('permissions')->insertOrIgnore([
            'name' => 'assign_leads',
            'display_name' => 'Assign Leads',
            'description' => 'Permission to assign leads to Student Advisors',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get the permission ID
        $permissionId = DB::table('permissions')->where('name', 'assign_leads')->value('id');

        if ($permissionId) {
            // Assign this permission to all existing roles (you can modify this as needed)
            $roleIds = DB::table('roles')->pluck('id');

            foreach ($roleIds as $roleId) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
