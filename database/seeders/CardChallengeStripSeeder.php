<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardChallengeStripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('challenge_stripes')->truncate();
        $faker = Faker::create();
        $userData[0] = array(

            'uuid' => $faker->uuid(),
            'card_challenge_id' => 1,
            'stripe_product_id' => "prod_N1jiwxbs6EJckP",
            'stripe_product_price_id' => "price_1MHgEgG8eX1XeOGisY5TQ70n",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        
        $userData[1] = array(

            'uuid' => $faker->uuid(),
            'card_challenge_id' => 2,
            'stripe_product_id' => "prod_N1l6yAg1l5SL0L",
            'stripe_product_price_id' => "price_1MHha5G8eX1XeOGiGoqlXfXA",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[2] = array(

            'uuid' => $faker->uuid(),
            'card_challenge_id' => 3,
            'stripe_product_id' => "prod_N1lFCl9FRlxepq",
            'stripe_product_price_id' => "price_1MHhisG8eX1XeOGifOuWvppm",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[3] = array(

            'uuid' => $faker->uuid(),
            'card_challenge_id' => 6,
            'stripe_product_id' => "prod_N1lHPDpAMbllFk",
            'stripe_product_price_id' => "price_1MHhkzG8eX1XeOGiqypOb2yv",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );
        $userData[4] = array(

            'uuid' => $faker->uuid(),
            'card_challenge_id' => 7,
            'stripe_product_id' => "prod_N1lVXQYo7v0WDx",
            'stripe_product_price_id' => "price_1MHhyhG8eX1XeOGiYIZxJyc2",
            'created_at' => $faker->date('Y-m-d H:m:s'),
            'updated_at' => $faker->date('Y-m-d H:m:s'),

        );

        DB::table('challenge_stripes')->insert($userData);
    }
}
