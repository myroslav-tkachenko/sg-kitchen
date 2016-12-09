<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'myroslav.tkachenko@gmail.com',
            'password' => bcrypt('secret'),
            'role_id' => 1,
        ]);
    }
}
