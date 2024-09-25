<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => "portal.users.index", 'description' => "List of Users", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.view", 'description' => "View User Details", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.create", 'description' => "Create New User", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.update", 'description' => "Update User Details", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.search", 'description' => "Search Record", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.edit_password", 'description' => "Reset User Password", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],
            ['name' => "portal.users.update_status", 'description' => "Activate or Deactivate User", 'module' => "users", 'module_name' => "Account Management", 'guard_name' => "web"],

            ['name' => "portal.cms.permissions.index", 'description' => "List of Permissions", 'module' => "cms.permissions", 'module_name' => "CMS - Permissions", 'guard_name' => "web"],
            ['name' => "portal.cms.permissions.search", 'description' => "Search Record", 'module' => "cms.permissions", 'module_name' => "CMS - Permissions", 'guard_name' => "web"],
        
            ['name' => "portal.cms.roles.index", 'description' => "List of Roles", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.create", 'description' => "Create New Role", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.update", 'description' => "Update Role Details", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
            ['name' => "portal.cms.roles.search", 'description' => "Search Record", 'module' => "cms.roles", 'module_name' => "CMS - Roles", 'guard_name' => "web"],
        ];

        foreach($permissions as $permission){
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']], $permission
            );
        }
    }
}
