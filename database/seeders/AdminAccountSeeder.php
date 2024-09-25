<?php

namespace Database\Seeders;

use App\Laravel\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $cred = User::where('name', 'Super Admin')->where('email', 'admin@gmail.com')->first();

        if(!$cred){
            $user = new User;
            $user->name = "Super Admin";
            $user->username = "master_admin";
            $user->email = "admin@gmail.com";
            $user->status = "active";
            $user->password = bcrypt("admin");
            $user->save();
    
            $role = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web', 'status' => 'active']);
            $user->assignRole($role);
        }
    }
}
