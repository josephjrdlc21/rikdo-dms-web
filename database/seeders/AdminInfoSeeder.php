<?php

namespace Database\Seeders;

use App\Laravel\Models\{User,UserInfo};

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon;

class AdminInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cred = User::where('name', 'Super Admin')->where('email', 'admin@gmail.com')->first();

        if($cred){
            $user_info = new UserInfo;
            $user_info->id_number = "1010101010";
            $user_info->role = "super admin";
            $user_info->firstname = "Super";
            $user_info->lastname = "Admin";
            $user_info->contact_number = "0999999999";
            $user_info->email = "admin@gmail.com";
            $user_info->address = "1 1st Street New Asinan , Olongapo, Philippines";
            $user_info->birthdate = Carbon::now()->toDateString();
            $user_info->save();

            $cred->user_info_id =  $user_info->id;
            $cred->save();
        }
    }
}
