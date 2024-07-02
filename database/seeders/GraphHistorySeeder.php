<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraphHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('graph_histories')->truncate();

        $faker = Faker::create();
        $getUser = User::all();

        foreach ($getUser as $key => $value) {

            for ($i = 1; $i < 15; $i++) {

                $insertData[$i] = array(
                    'uuid' => $faker->uuid(),
                    'user_id' => $value->id,
                    'account_id_number' => $value->account_id_number,
                    'risk_management_id' => 1,
                    'day_index' => $i,
                    'eod_net_liq' => $faker->randomNumber(3, true),
                    'eod_drawdown' => $faker->randomNumber(2, true),
                    'eod_profit_target' => $faker->randomNumber(2, true),
                    'created_at' => $faker->date('Y-m-d H:m:s'),
                    'updated_at' => $faker->date('Y-m-d H:m:s'),

                );
            }
            DB::table('graph_histories')->insert($insertData);
            $insertData = array();
        }
    }
}
