<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 5; $i++) {
            $userData[$i] = array(

                'uuid' => $faker->uuid(),
                'user_id' => $i,
                'first_name' => $faker->name(),
                'last_name' => $faker->name(),
                'age' => $faker->randomDigit(),
                'country' => $i,
                'gender' => 1,

            );
        }

        DB::table('user_details')->insert($userData);

    }
}
