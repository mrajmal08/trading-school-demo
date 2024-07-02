<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('web_settings')->truncate();
        DB::table('web_settings')->insert([
            'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'logo' => 'demo.png',
            'footer_logo_description' => 'Your best trading learning platform',
            'term_of_service' => 'Term of service',
            'privacy_policy' => 'Privacy policy',
            'site_footer_copyright' => 'Trading School ©. All rights reserved.',
            'subscribe_title' => 'Submit for updates.',
            'subscribe_description' => 'Subscribe to get update and notify our exchange and products',
            'dark_logo' => 'demo.png',
            'linkedin' => 'www.linkedin.com',
            'facebook' => 'www.facebook.com',
            'instagram' => 'www.instagram.com',
        ]);
    }
}
