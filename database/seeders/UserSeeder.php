<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 5; $i++) {
            $userData[$i] = array(
                'uuid' => $faker->uuid(),
                'email' => $faker->unique()->safeEmail(),
                'otp' => $faker->randomDigit(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'account_id_number' => $faker->randomDigit(),
                'account_name' => $faker->name(),
                // 'timezone' => $faker->name(),
                'email_verified_at' => $faker->dateTimeBetween('-30 days', '+30 days'),
            );
        }

        DB::table('users')->insert($userData);

    }
}
