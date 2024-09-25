<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'super admin')->where('guard_name', 'web')->first();
        
        if(!$role){
            // If the role does not exist, create it
            $role = Role::create(['name' => 'super admin', 'guard_name' => 'web', 'status' => 'active']);
        }
        
        // Fetch all permissions
        $permissions = Permission::where('guard_name', 'web')->pluck('name')->toArray();
        
        // Assign all permissions to the 'super admin' role
        $role->givePermissionTo($permissions);
    }
}
