<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'country' => $this->country,
            'state' => $this->state,
            'picture' => $this->picture,
            'gender' => $this->gender,
            // 'age' => carbon::createFromFormat('Y-m-d', $this->age),
            'age' => date('Y-m-d', strtotime($this->age)),
            'package_start_date' => $this->start_date,
            'package_end_date' => $this->end_date,
            'status' => $this->status,

        ];
    }
}
