<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllTradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('all_trades')->truncate();
        $faker = Faker::create();
        $getUser = User::all();

        foreach ($getUser as $key => $value) {
            $insertData[$key] = array(
                'uuid' => $faker->uuid(),
                'user_id' => $value->id,
                'risk_management_id' => 1,
                'account_id_number' => $value->account_id_number,
                'total_pl' => $faker->randomDigit(),
                'number_trades' => $faker->randomDigit(),
                'number_contracts' => $faker->randomDigit(),
                'avg_trading_time' => $faker->randomDigit(),
                'longest_trading_time' => $faker->randomDigit(),
                'percent_profitable' => $faker->randomDigit(),
                'expectancy' => $faker->randomDigit(),
                'created_at' => $faker->date('Y-m-d H:m:s'),
                'updated_at' => $faker->date('Y-m-d H:m:s'),

            );
        }
        DB::table('all_trades')->insert($insertData);
    }
}
