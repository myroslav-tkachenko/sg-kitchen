<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'admin',
            'display_name' => 'Адміністратор',
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'waiter',
            'display_name' => 'Офіціант',
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'cook',
            'display_name' => 'Повар',
        ]);
    }
}
