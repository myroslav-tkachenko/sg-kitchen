<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'id' => 1,
            'name' => 'new',
            'display_name' => 'Новий',
        ]);
        DB::table('statuses')->insert([
            'id' => 2,
            'name' => 'passed',
            'display_name' => 'Передано',
        ]);
        DB::table('statuses')->insert([
            'id' => 3,
            'name' => 'in_process',
            'display_name' => 'Готується',
        ]);
        DB::table('statuses')->insert([
            'id' => 4,
            'name' => 'done',
            'display_name' => 'Готово',
        ]);
    }
}
