<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::truncate(); 
           $users = [ 
            [ 
              'name' => 'Admin',
              'email' => 'admin@aiims.com',
              'password' => 'Aiims@123',
              'user_type' => 'admin',
            ]
          ];

          foreach($users as $user)
          {
            \App\Models\User::create([
               'name' => $user['name'],
               'email' => $user['email'],
               'user_type' => 'admin',
               'password' => Hash::make($user['password'])
             ]);
           }
    }
}
