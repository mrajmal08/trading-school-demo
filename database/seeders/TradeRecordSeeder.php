<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trade_records')->truncate();

        $faker = Faker::create();
        $getUser = User::all();
        for ($i = 0; $i < 10; $i++) {

            foreach ($getUser as $key => $value) {
                $insertData[$key] = array(
                    'uuid' => $faker->uuid(),
                    'user_id' => $value->id,
                    'trade_time' => $faker->date('Y-m-d H:m:s'),
                    'risk_management_id' => 1,
                    'account' => $value->account_id_number,
                    'symbol' => $faker->words(1, true),
                    'buy' => $faker->randomNumber(3, true),
                    'sale' => $faker->randomNumber(4, true),
                    'quantity' => $faker->randomNumber(2, true),
                    'price' => $faker->randomNumber(6, true),
                    'created_at' => $faker->date('Y-m-d H:m:s'),
                    'updated_at' => $faker->date('Y-m-d H:m:s'),

                );

                DB::table('trade_records')->insert($insertData);
            }

        }

    }
}
