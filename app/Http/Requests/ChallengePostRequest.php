<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json($validator->errors(), 400));
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // return [
        //     'service_id'              => ['required'],
        //     'challenge_title'         => ['required'],
        //     'buying_power'            => ['required',],
        //     'challenge_price'         => ['required'],
        //     'is_feature'              => ['nullable'],
        //     'market_data_id'          => ['required'],
        //     'clone_id'                => ['nullable'],
        //     'stripe_product_id'       => ['nullable'],
        //     'stripe_product_price_id' => ['nullable'],
        //     'card_head_title_id'      => ['required'],
        //     'card_sub_head_title_id'  => ['required'],
        // ];
        return [

            'challenge_price' => ['required'],

        ];
    }
}
