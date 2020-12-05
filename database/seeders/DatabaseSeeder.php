<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        Post::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(CategorySeeder::class);
        \App\Models\Category::factory(10)->create();
    }
}
