<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketDataStripeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('market_data_stripes')->truncate();
        $faker = Faker::create();
        $userData[0] = array(

            'uuid' => $faker->uuid(),
            'market_data_id' => 1,
            'stripe_product_id' => "prod_NBLrpKqxDgphoK",
            'stripe_product_price_id' => "price_1MQz9sG8eX1XeOGiQbhUSUKx",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[1] = array(

            'uuid' => $faker->uuid(),
            'market_data_id' => 2,
            'stripe_product_id' => "prod_NBLtbQu9dYyRIb",
            'stripe_product_price_id' => "price_1MQzBGG8eX1XeOGi1WJpJSYw",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[2] = array(

            'uuid' => $faker->uuid(),
            'market_data_id' => 3,
            'stripe_product_id' => "prod_NBLtXULyrfstRq",
            'stripe_product_price_id' => "price_1MQzCAG8eX1XeOGi3fegNhGt",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        DB::table('market_data_stripes')->insert($userData);
    }
}
