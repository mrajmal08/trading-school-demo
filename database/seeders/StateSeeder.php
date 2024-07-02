<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usState = [
            "Alabama",
            "Alaska",
            "Arizona",
            "Arkansas",
            "California",
            "Colorado",
            "Connecticut",
            "Delaware",
            "District of Columbia",
            "Florida",
            "Georgia",
            "Hawaii",
            "Idaho",
            "Illinois",
            "Indiana",
            "Iowa",
            "Kansas",
            "Kentucky",
            "Louisiana",
            "Maine",
            "Maryland",
            "Massachusetts",
            "Michigan",
            "Minnesota",
            "Mississippi",
            "Missouri",
            "Montana",
            "Nebraska",
            "Nevada",
            "New Hampshire",
            "New Jersey",
            "New Mexico",
            "New York",
            "North Carolina",
            "North Dakota",
            "Ohio",
            "Oklahoma",
            "Oregon",
            "Pennsylvania",
            "Rhode Island",
            "South Carolina",
            "South Dakota",
            "Tennessee",
            "Texas",
            "Utah",
            "Vermont",
            "Virginia",
            "Washington",
            "West Virginia",
            "Wisconsin",
            "Wyoming",
        ];

        $canadaState = [
            "Alberta",
            "British Columbia",
            "Manitoba",
            "New Brunswick",
            "Newfoundland and Labrador",
            "Nova Scotia",
            "Northwest Territories",
            "Nunavut",
            "Ontario",
            "Prince Edward Island",
            "Quebec",
            "Saskatchewan",
            "Yukon",
        ];
        DB::table('states')->truncate();
        $faker = Faker::create();
        $allData = $usState;

        foreach ($allData as $key => $value) {
            $insertData[$key] = array(
                'uuid' => $faker->uuid(),
                'country_id' => 149,
                'state_name' => $value,
                'created_at' => $faker->date('Y-m-d H:m:s'),
                'updated_at' => $faker->date('Y-m-d H:m:s'),

            );
        }
        DB::table('states')->insert($insertData);

        $state = $canadaState;
        foreach ($state as $key => $cvalue) {
            $cinsertData[$key] = array(
                'uuid' => $faker->uuid(),
                'country_id' => 26,
                'state_name' => $cvalue,
                'created_at' => $faker->date('Y-m-d H:m:s'),
                'updated_at' => $faker->date('Y-m-d H:m:s'),

            );
        }

        DB::table('states')->insert($cinsertData);
    }
}
