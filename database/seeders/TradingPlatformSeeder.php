<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradingPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trading_platforms')->truncate();
        $faker = Faker::create();
        $userData[0] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "51424",
            "name" => "CQG Mobile",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[1] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "51769",
            "name" => "Volumetrica - Volumetrica",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[2] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "52065",
            "name" => "AMPConnect - AMPConnect",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[3] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56004",
            "name" => "CQG - CQG Desktop",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[4] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56044",
            "name" => "SierraChartData",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[5] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56046",
            "name" => "Bluewater",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[6] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56048",
            "name" => "AgenaTrader",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[7] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56049",
            "name" => "CQGXL",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[8] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56062",
            "name" => "AdvancedTS",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[9] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56064",
            "name" => "MultichartsWAPI - MultichartsWAPI",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[10] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56065",
            "name" => "MotiveWaveWAPI",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[11] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56068",
            "name" => "TradingView - TradingView",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[12] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56072",
            "name" => "BookMap",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[13] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56075",
            "name" => "AMPMCWAPI",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[14] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56105",
            "name" => "Jigsaw",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[15] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56214",
            "name" => "Volfix",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        $userData[16] = array(

            'uuid' => $faker->uuid(),
            "api_id" => "56470",
            "name" => "LinnSoft",
            "price" => 0,
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        DB::table('trading_platforms')->insert($userData);
    }
}
