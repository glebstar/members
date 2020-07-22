<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            'name' => 'test',
            'date' => '2020-07-22',
            'city_id' => 1,
        ]);

        DB::table('events')->insert([
            'name' => 'test2',
            'date' => '2020-07-22',
            'city_id' => 1,
        ]);

        DB::table('events')->insert([
            'name' => 'test3',
            'date' => '2020-07-22',
            'city_id' => 2,
        ]);
    }
}
