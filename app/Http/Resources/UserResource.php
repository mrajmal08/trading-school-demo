<?php

namespace App\Http\Resources;

use App\Http\Resources\UserDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // dd($challengeData->card_challenge_id);
        return [

            'uuid' => $this->uuid,
            'email' => $this->email,
            'userDetail' => new UserDetailResource($this->userDetail),
        ];
    }
}
