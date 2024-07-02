<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MtFiveGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mt_five_groups')->truncate();
        $faker = Faker::create();
        $userData[0] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-EUR-SpeedUpTrader-FX-100K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[1] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-EUR-SpeedUpTrader-FX-150K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[2] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-EUR-SpeedUpTrader-FX-300K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[3] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-EUR-SpeedUpTrader-FX-600K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[4] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-USD-SpeedUpTrader-FX-150K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[5] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-USD-SpeedUpTrader-FX-300K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[6] = array(

            'uuid' => $faker->uuid(),
            "name" => "demo\\EU\\demo-USD-SpeedUpTrader-FX-600K",
            "leverage" => 1,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        DB::table('mt_five_groups')->insert($userData);
    }
}
