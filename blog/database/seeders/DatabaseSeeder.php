<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'moderator']);
        \App\Models\User::factory(1)->create(['email'=>'ahmed@gmail.com']);

        $user = \App\Models\User::find(1);
        $user->assignRole('admin');

        \App\Models\Profile::factory(10)->create();
        \App\Models\User::factory(10)->create();
        \App\Models\Item::factory(10)->create();
        \App\Models\Product::factory(10)->create();

        
    }
}
