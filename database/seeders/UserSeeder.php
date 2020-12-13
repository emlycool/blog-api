<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user1 = User::where('email', 'joshua.moshood@gmail.com')->first();
        if(!$user1){
            $adminRole = Role::where('name','admin')->first();
            User::create([
                'name' => "joshua moshood",
                'email' =>  "joshua.moshood@gmail.com",
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'role_id' => $adminRole->id,
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
