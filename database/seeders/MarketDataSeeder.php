<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('market_data')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $userData[0] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "51096",
            "name" => "CME NP L1 Bundled",
            "original_price" => 3,
            "buffer_price" => 5,
            "price" => 8,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[1] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "51062",
            "name" => "CME Non Pro Level 2 Bundled",
            "original_price" => 35,
            "buffer_price" => 5,
            "price" => 40,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[2] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "50579",
            "name" => "EUREX Ultra Non Pro",
            "original_price" => 18,
            "buffer_price" => 5,
            "price" => 23,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        DB::table('market_data')->insert($userData);
    }
}
