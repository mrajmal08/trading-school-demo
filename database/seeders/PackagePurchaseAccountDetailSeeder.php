<?php

namespace Database\Seeders;

use App\Models\CardChallenge;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagePurchaseAccountDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('package_purchase_account_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $getUser = User::all();

        foreach ($getUser as $key => $value) {

            for ($i = 1; $i < 15; $i++) {

                $challanges = CardChallenge::all();
                $challanges = $challanges->random();
                $status = collect(["In-Progress", "Successed", "Failed"]);

                $insertData[$i] = array(
                    'uuid' => $faker->uuid(),
                    'user_id' => $value->id,
                    'card_challenge_id' => $challanges->id,
                    'request_id' => $faker->randomDigitNotNull(),
                    'amp_id' => $faker->randomNumber(7, true),
                    'trader_id' => "G" . $faker->randomNumber(7, true),
                    'trader_name' => $faker->numerify('trade-####'),
                    'account_id' => $faker->randomNumber(7, true),
                    'account_name' => $faker->words(1, true),
                    'new_customer_id' => $faker->randomNumber(6, true),
                    'package_price' => $challanges->price,
                    'account_status' => $status->random(),
                    'created_at' => $faker->date('Y-m-d H:m:s'),
                    'updated_at' => $faker->date('Y-m-d H:m:s'),

                );
            }
            DB::table('package_purchase_account_details')->insert($insertData);
            $insertData = array();
        }

    }
}
