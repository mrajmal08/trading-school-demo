<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChallengePostRequest;
use App\Models\CardChallenge;
use App\Models\CardHead;
// use App\Models\CardHead;
use App\Models\CardHeadSubTitle;
use App\Models\CardHeadTitle;
use App\Models\CardSubHeadTitle;
use App\Models\ChallengeMarket;
use App\Models\ChallengeStripe;
use App\Models\MarketData;
use App\Models\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $challenges = CardChallenge::orderBy('id', 'desc')
        ->with(['service', 'cardHead', 'challengeStripe'])
        ->when($search, function($query) use ($search) {
            return $query->where('title', $search);
        })
        ->when($search, function($query) use ($search) {
            return $query->orWhereHas('service', function($query) use ($search){
                $query->orWhere('name', $search);
            });
        })
        ->paginate(10);
        return view('pages.teacher.challenges.challenges', ['challenges' => $challenges]);
    }

    public function create()
    {
        $services = Service::all();
        $card_head_titles = CardHeadTitle::all();
        $card_sub_titles = CardSubHeadTitle::all();
        $marketData = MarketData::all();
        return view('pages.teacher.challenges.challenge_create', ['services' => $services, 'head_titles' => $card_head_titles, 'sub_titles' => $card_sub_titles, 'market_data' => $marketData]);
    }

    public function store(ChallengePostRequest $request)
    {

        DB::beginTransaction();
        try {

            // Card Challenge
            $card_challenge_data = [];
            $card_challenge_data["service_id"] = $request->get('service_id');
            $card_challenge_data["title"] = $request->get('challenge_title');
            $card_challenge_data["buying_power"] = $request->get('buying_power');
            $card_challenge_data["price"] = $request->get('challenge_price');
            $card_challenge_data["market_data_id"] = $request->get('market_data_id');
            $card_challenge_data["clone_id"] = $request->get('clone_id');
            $card_challenge_data["is_feature"] = $request->get('is_feature') === '1' ? 1 : 0;
            $card_challenge = CardChallenge::create($card_challenge_data);

            // Stripe Instance
            $stripeConfigKye = config('app.stripe_secret_kye');
            $stripe = new StripeClient($stripeConfigKye);

            $product_detail = $stripe->products->create([
                'name' => $request->get('challenge_title'),
                ['metadata' => ['challenge_uuid' => $card_challenge->uuid]],
            ]);

            // create prices for the product
            $productPrice = $stripe->prices->create([
                'unit_amount' => $request->get('challenge_price') * 100,
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
                'product' => $product_detail->id,
            ]);

            // default prices for the product
            $stripeUpdatePrice = $stripe->products->update(
                $product_detail->id,
                ['default_price' => $productPrice->id]
            );

            // Challenge Stripe
            $challenge_stripes_data = [];
            $challenge_stripes_data['stripe_product_id'] = $product_detail->id;
            $challenge_stripes_data['stripe_product_price_id'] = $productPrice->id;
            $challenge_stripes_data['card_challenge_id'] = $card_challenge->id;
            $challenge_stripes = ChallengeStripe::create($challenge_stripes_data);

            // $card_heads_relations_with_[card_challenge]_and_[card_head_titles]
            $card_head_data = [];
            foreach ($request->get('card_head_title_id') as $key => $value) {
                $card_head_data[$key]['card_head_title_id'] = $value;
                $card_head_data[$key]['uuid'] = (string) Str::uuid();
            }
            $card_head = $card_challenge->cardHead()->createMany($card_head_data);

            $card_head_sub_titles_data = [];
            $i = 0;
            foreach ($request->get('card_sub_head_title_id') as $key => $sub_titles) {
                foreach ($sub_titles as $row => $sub_title) {
                    if (\array_key_exists($row, $card_head_sub_titles_data)) {
                        $ind = array_key_last($card_head_sub_titles_data);
                        $index = $ind + 1;
                        $card_head_sub_titles_data[$index]['card_head_id'] = $card_head[$key]->id;
                        $card_head_sub_titles_data[$index]['card_sub_head_title_id'] = $sub_title;
                        $card_head_sub_titles_data[$index]['uuid'] = (string) Str::uuid();
                    } else {
                        $card_head_sub_titles_data[$row]['card_head_id'] = $card_head[$key]->id;
                        $card_head_sub_titles_data[$row]['card_sub_head_title_id'] = $sub_title;
                        $card_head_sub_titles_data[$row]['uuid'] = (string) Str::uuid();
                    }
                }
            }

            $card_head_sub_titles = CardHeadSubTitle::insert($card_head_sub_titles_data);

            DB::commit();
            return \redirect()->route('teacher.challenges')->with(['status' => 'success', 'message' => 'successfully! created challenge record']);
        } catch (\Throwable$th) {
            DB::rollBack();
            // throw new Exception($th->getMessage());
            return redirect()->back()->withErrors($th->getMessage(), 'errors')->withInput();
        }
    }

    public function show($id)
    {
        $challenge = CardChallenge::findOrFail($id);
        // \dd($challenge);
        return view('pages.teacher.challenges.challenge_detail', ['challenge' => $challenge]);
    }

    public function edit($id)
    {
        $services = Service::all();
        $card_head_titles = CardHeadTitle::all();
        $challenge = CardChallenge::findOrFail($id);
        $challengMarketData = ChallengeMarket::where('card_challenge_id', $challenge->id)->get();
        $marketDataCompara = array();
        if (count($challengMarketData) > 0) {
            foreach ($challengMarketData as $key => $chvalue) {
                array_push($marketDataCompara, $chvalue->market_data_id);
            }
        }

        $marketData = MarketData::all();
        return view('pages.teacher.challenges.challenge_edit', ['challenge' => $challenge, 'compareMarket' => $marketDataCompara, 'services' => $services, 'head_titles' => $card_head_titles, 'market_data' => $marketData]);
    }

    // public function update($id, ChallengePostRequest $request)
    public function update($id, Request $request)
    {

        $stripeConfigKye = config('app.stripe_secret_kye');
        $stripe = new StripeClient($stripeConfigKye);

        $challengeStripe = ChallengeStripe::where('card_challenge_id', $id)->first();
        $card_challenge = CardChallenge::findOrFail($id);

        if (!empty($challengeStripe) && $card_challenge->price !== $request->get('challenge_price')) {
            $stripeCreatePrice = $stripe->prices->create([
                'unit_amount' => $request->get('challenge_price') * 100,
                'currency' => 'usd',
                'product' => $challengeStripe->stripe_product_id,
                'recurring' => ['interval' => 'month'],
            ]);

            $stripeUpdatePrice = $stripe->products->update(
                $challengeStripe->stripe_product_id,
                ['default_price' => $stripeCreatePrice->id]
            );
        }

        DB::transaction(function () use ($card_challenge, $request, $stripeUpdatePrice) {
            $getMarketData = $request->market_data_id;
            $multiMarket = array();
            if (count($getMarketData) > 1) {
                $insertSingelMarketData = array_shift($getMarketData);
                $card_challenge->market_data_id = $insertSingelMarketData;
                ChallengeMarket::where('card_challenge_id', $card_challenge->id)->delete();
                foreach ($getMarketData as $marketkey => $marketvalue) {
                    $multiMarket[$marketkey] = array(
                        "uuid" => (string) Str::uuid(),
                        "card_challenge_id" => $card_challenge->id,
                        "market_data_id" => $marketvalue,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    );
                }

            } else {

                $card_challenge->market_data_id = $getMarketData[0];
                ChallengeMarket::where('card_challenge_id', $card_challenge->id)->delete();
            }

            $card_challenge->price = $request->get('challenge_price');
            $card_challenge->is_feature = $request->get('is_feature') === '1' ? 1 : 0;
            $card_challenge->update();
            ChallengeMarket::insert($multiMarket);
            $challenge_stripes_data['stripe_product_price_id'] = $stripeUpdatePrice->default_price;
            $card_challenge->challengeStripe->update($challenge_stripes_data);
        });

        // $card_challenge->market_data_id = $request->get('market_data_id');

        // New Code for Requirement

        // New Code for Requirement

        // $challenge_stripes_data['stripe_product_price_id'] = $stripeUpdatePrice->default_price;
        // $card_challenge->challengeStripe->update($challenge_stripes_data);
        return Redirect::route('teacher.challenges')->with(['status' => 'success', 'message' => 'Challenge record has updated successfully!']);

        // DB::beginTransaction();
        // try {
        //     // Stripe Instance
        //     $stripeConfigKye = config('app.stripe_secret_kye');
        //     $stripe = new StripeClient($stripeConfigKye);

        //     $challengeStripe = ChallengeStripe::where('card_challenge_id', $id)->first();
        //     $card_challenge = CardChallenge::findOrFail($id);

        //     if (!empty($challengeStripe) && $card_challenge->price !== $request->get('challenge_price')) {
        //         $stripeCreatePrice = $stripe->prices->create([
        //             'unit_amount' => $request->get('challenge_price') * 100,
        //             'currency' => 'usd',
        //             'product' => $challengeStripe->stripe_product_id,
        //             'recurring' => ['interval' => 'month'],
        //         ]);

        //         $stripeUpdatePrice = $stripe->products->update(
        //             $challengeStripe->stripe_product_id,
        //             ['default_price' => $stripeCreatePrice->id]
        //         );
        //     }

        //     $card_challenge->service_id = $request->get('service_id');
        //     $card_challenge->title = $request->get('challenge_title');
        //     $card_challenge->buying_power = $request->get('buying_power');
        //     $card_challenge->price = $request->get('challenge_price');
        //     $card_challenge->is_feature = $request->get('is_feature') === '1' ? 1 : 0;
        //     $card_challenge->market_data_id = $request->get('market_data_id');
        //     $card_challenge->clone_id = $request->get('clone_id');
        //     $card_challenge->update();

        //     $challenge_stripes_data = [];
        //     $challenge_stripes_data['stripe_product_id'] = $request->get('stripe_product_id');
        //     $challenge_stripes_data['stripe_product_price_id'] = $stripeUpdatePrice->default_price;
        //     $card_challenge->challengeStripe->update($challenge_stripes_data);

        //     $old_card_head = CardHead::where("card_challenge_id", $card_challenge->id)->get();
        //     if ($old_card_head->isNotEmpty()) {
        //         foreach ($old_card_head as $card) {
        //             $card->cardHeadSubTitle()->forceDelete();
        //         }
        //     }
        //     $card_challenge->cardHead()->forceDelete();

        //     $card_head_data = [];
        //     foreach ($request->get('card_head_title_id') as $key => $value) {
        //         $card_head_data[$key]['card_head_title_id'] = $value;
        //         $card_head_data[$key]['uuid'] = (string) Str::uuid();
        //     }
        //     $card_head = $card_challenge->cardHead()->createMany($card_head_data);

        //     $card_head_sub_titles_data = [];
        //     foreach ($request->get('card_sub_head_title_id') as $key => $sub_titles) {
        //         foreach ($sub_titles as $row => $sub_title) {
        //             if (\array_key_exists($row, $card_head_sub_titles_data)) {
        //                 $ind = array_key_last($card_head_sub_titles_data);
        //                 $index = $ind + 1;
        //                 $card_head_sub_titles_data[$index]['card_head_id'] = $card_head[$key]->id;
        //                 $card_head_sub_titles_data[$index]['card_sub_head_title_id'] = $sub_title;
        //                 $card_head_sub_titles_data[$index]['uuid'] = (string) Str::uuid();
        //             } else {
        //                 $card_head_sub_titles_data[$row]['card_head_id'] = $card_head[$key]->id;
        //                 $card_head_sub_titles_data[$row]['card_sub_head_title_id'] = $sub_title;
        //                 $card_head_sub_titles_data[$row]['uuid'] = (string) Str::uuid();
        //             }
        //         }
        //     }

        //     $card_head_sub_titles = CardHeadSubTitle::insert($card_head_sub_titles_data);

        //     DB::commit();
        //     return \redirect()->route('admin.challenges')->with(['status' => 'success', 'message' => 'Challenge record has updated successfully!']);
        // } catch (\Throwable$th) {
        //     DB::rollBack();
        //     throw new Exception($th->getMessage());
        //     // return redirect()->back()->withErrors($th->getMessage(), 'errors')->withInput();
        // }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $card_challenge = CardChallenge::findOrFail($id);
            $card_head = CardHead::where("card_challenge_id", $card_challenge->id)->get();

            if ($card_head->isNotEmpty()) {
                foreach ($card_head as $card) {
                    $card->cardHeadSubTitle()->delete();
                }
            }

            $card_challenge->cardHead()->delete();
            $card_challenge->challengeStripe()->delete();
            $card_challenge->delete();
            DB::commit();
            return \redirect()->route('teacher.challenges')->with(['status' => 'success', 'message' => 'successfully! deleted challenge record']);
        } catch (\Throwable$th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // return redirect()->back()->withErrors($th->getMessage(), 'errors')->withInput();
        }
    }

    public function getChallenge()
    {

        $go_lang_url = config('app.go_lang_url');
        $go_lang_url = $go_lang_url . "futures/user/challenge/details";

        $response = Http::post($go_lang_url, [
            "cqgSalesSeries" => "29160",
        ]);

        $challengeData = $response->object()->challenges;

        //

        foreach ($challengeData as $key => $cvalue) {
            $buingPower = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $cvalue->account_size);
            $cardChallenge = CardChallenge::where('buying_power', $buingPower)->first();

            $rule1Des = $cvalue->rule1_description;
            $rule1Val = $cvalue->rule1_maximum;

            $minimumday = CardHeadTitle::firstOrCreate(
                ['title' => "Minimum Challenge Days"],
            );

            $miniMumCardHead = CardHead::firstOrCreate(
                ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $minimumday->id],
            );

            $minimumdayValue = CardSubHeadTitle::firstOrCreate(
                ['card_head_title_id' => $minimumday->id, 'title' => $cvalue->minimum_days],
            );

            CardHeadSubTitle::firstOrCreate(
                ['card_head_id' => $miniMumCardHead->id, 'card_sub_head_title_id' => $minimumdayValue->id],
            );

            $profitTarget = CardHeadTitle::firstOrCreate(
                ['title' => "Profit Target"],
            );

            $profitTargetValue = CardSubHeadTitle::firstOrCreate(
                ['card_head_title_id' => $profitTarget->id, 'title' => $cvalue->profit_target],
            );

            $profitCardHead = CardHead::firstOrCreate(
                ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $profitTarget->id],
            );

            CardHeadSubTitle::firstOrCreate(
                ['card_head_id' => $profitCardHead->id, 'card_sub_head_title_id' => $profitTargetValue->id],
            );

            $cdTId = CardHeadTitle::firstOrCreate(
                ['title' => $rule1Des],
            );

            $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
                ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
            );

            $cardHeadId = CardHead::firstOrCreate(
                ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
            );

            CardHeadSubTitle::firstOrCreate(
                ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
            );

            $rule1Des = $cvalue->rule2_description;
            $rule1Val = $cvalue->rule2_maximum;

            $cdTId = CardHeadTitle::firstOrCreate(
                ['title' => $rule1Des],
            );
            $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
                ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
            );

            $cardHeadId = CardHead::firstOrCreate(
                ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
            );

            CardHeadSubTitle::firstOrCreate(
                ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
            );

            $rule1Des = $cvalue->rule3_description;
            $rule1Val = $cvalue->rule3_maximum;

            $cdTId = CardHeadTitle::firstOrCreate(
                ['title' => $rule1Des],
            );
            $cdSubHeadId = CardSubHeadTitle::firstOrCreate(
                ['card_head_title_id' => $cdTId->id, 'title' => $rule1Val],
            );

            $cardHeadId = CardHead::firstOrCreate(
                ['card_challenge_id' => $cardChallenge->id, 'card_head_title_id' => $cdTId->id],
            );

            CardHeadSubTitle::firstOrCreate(
                ['card_head_id' => $cardHeadId->id, 'card_sub_head_title_id' => $cdSubHeadId->id],
            );
        }

        return Redirect::back()->with(['message' => 'Challenge Update Successful']);

    }
}
