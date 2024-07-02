<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradingDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trading_details')->truncate();

        $faker = Faker::create();
        $getUser = User::all();

        foreach ($getUser as $key => $value) {

            $insertData[$key] = array(
                'uuid' => $faker->uuid(),
                'user_id' => $value->id,
                'account_id_number' => $value->account_id_number,
                'api_id' => $faker->randomNumber(3, false),
                'is_active' => 1,
                'is_primary' => 1,
                'account_size' => $faker->randomNumber(7, true),
                'account_name' => $value->account_name,
                'is_locked' => 0,
                'is_empty' => 1,
                'is_maybe_locked' => 0,
                'balance' => $faker->randomNumber(5, true),
                'sodbalance' => $faker->randomNumber(3, true),
                'current_daily_pl' => $faker->randomNumber(2, true),
                'open_contracts' => $faker->randomNumber(3, false),
                'daily_loss_limit' => $faker->randomNumber(2, true),
                'net_liq_value' => $faker->randomNumber(4, true),
                'current_drawdown' => $faker->randomNumber(3, false),
                'drawdown_limit' => $faker->randomNumber(2, true),
                'drawdown_type' => $faker->words(1, true),
                'trading_day' => $faker->numberBetween(1, 28),
                'rule_one_enabled' => 0,
                'rule_one_value' => 0,
                'rule_one_key' => 0,
                'rule_one_maximum' => 0,
                'rule_two_enabled' => 0,
                'rule_two_value' => 0,
                'rule_two_key' => 0,
                'rule_two_maximum' => 0,
                'rule_three_enabled' => 0,
                'rule_three_value' => 0,
                'rule_three_key' => 0,
                'rule_three_maximum' => 0,
                'created_at' => $faker->date('Y-m-d H:m:s'),
                'updated_at' => $faker->date('Y-m-d H:m:s'),

            );

        }
        DB::table('trading_details')->insert($insertData);
    }
}
