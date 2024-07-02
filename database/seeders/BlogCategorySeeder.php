<?php

namespace Database\Seeders;

// use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('blog_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = Faker::create();
        for ($i = 1; $i <= 10; $i++) {
            $userData[$i] = array(

                'uuid' => $faker->uuid(),
                'name' => $faker->words(1, true),

            );
        }

        DB::table('blog_categories')->insert($userData);

    }
}
