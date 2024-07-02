<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\UserDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blog_details')->truncate();

        $faker = Faker::create();
        $getData = BlogCategory::all();
        $users = UserDetail::all();
        for ($i = 0; $i < 10; $i++) {

            foreach ($users as $key => $value) {
                $insertData[$key] = array(
                    'uuid' => $faker->uuid(),
                    // 'blog_category_id' => $value->id,
                    'title' => $faker->sentence(),
                    'detail' => $faker->paragraph(15),
                    'picture' => $faker->imageUrl(640, 480, 'animals', true),
                    'date' => $faker->date('Y-m-d'),
                    'publish' => 1,
                    'user_name' => $value->first_name,
                    'created_at' => $faker->date('Y-m-d H:m:s'),
                    'updated_at' => $faker->date('Y-m-d H:m:s'),

                );

                DB::table('blog_details')->insert($insertData);
            }

        }
    }
}
