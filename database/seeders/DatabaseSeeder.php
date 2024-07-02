<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;

use App\Models\Card;
use App\Models\Service;
use App\Models\CardHeadTitle;
use App\Models\CardSubHeadTitle;
use App\Models\CardChallenge;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\User::factory(10)->hasUserDetail(1)->create();
        Service::factory(2)->has(CardChallenge::factory()->count(5))->create();
        CardHeadTitle::factory(3)->has(CardSubHeadTitle::factory()->count(3))->create();
        // Card::factory(5)->has(CardChallenge::factory()->count(1))->create();

        $this->call([
            UserSeeder::class,
            CountrySeeder::class,
            UserDetailSeeder::class,
            CardChallengeStripSeeder::class,
            AdminSeeder::class,
            CardChallengeStripSeeder::class,
            // RiskManagementSeeder::class,
            AllTradeSeeder::class,
            ProfitTradeSeeder::class,
            LosingTradeSeeder::class,
            TradeRecordSeeder::class,
            WebSettingSeeder::class,
            GraphHistorySeeder::class,
            TradingDetailSeeder::class,
            StateSeeder::class,
            MarketDataSeeder::class,
            TradingPlatformSeeder::class,
            MtFiveGroupSeeder::class,
            PackagePurchaseAccountDetailSeeder::class,
            BlogCategorySeeder::class,
            BlogDetailSeeder::class,
            MarketDataStripeSeeder::class,
            SoftSettingSeeder::class,
        ]);
    }
}
