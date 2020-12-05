<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = Role::where('name', 'user')->first();
        if(!$userRole){
            Role::create([
                'name' => 'user'
            ]);
        }
        $userRole = Role::where('name', 'editor')->first();
        if(!$userRole){
            Role::create([
                'name' => 'editor'
            ]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if(!$adminRole){
            Role::create([
                'name' => 'admin'
            ]);
        }
    }
}
