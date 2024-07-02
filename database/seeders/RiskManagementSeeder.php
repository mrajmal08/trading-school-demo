<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class RiskManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 20; $i++) {
            $userData[$i] = array(

                'uuid' => Str::uuid(),
                'user_id' => $i,
                'amp_id' => 'AMP-' . $i,
                'trader_id' => 'TRADER-' . $i,
                'trader_name' => 'Trader ' . $i,
                'account_id' => 'ACC-' . $i,
                'account_name' => 'Account ' . $i,
                'card_challenge_id' => $i,
                'package_price' => '1000',
                'account_status' => 'Active',
                'open_contracts' => '5',
                'current_daily_pl' => '200',
                'trading_day' => 'Day ' . $i,
                'net_liq_value' => '15000',
                'sodbalance' => '10000',
                'rule_1_value' => '50',
                'rule_1_maximum' => '100',
                'rule_2_value' => '75',
                'rule_2_maximum' => '150',
                'rule_3_value' => '100',
                'rule_3_maximum' => '200',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
             );
        }

        DB::table('risk_management')->insert($userData);
    }
}
