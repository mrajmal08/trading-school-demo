<?php

namespace App\Http\Resources\FrontEnd;

use Illuminate\Http\Resources\Json\JsonResource;

class WebsettingResource extends JsonResource
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
            'logo' => $this->logo,
            'footer_logo_description' => $this->footer_logo_description,
            'term_of_service' => $this->term_of_service,
            'privacy_policy' => $this->privacy_policy,
            'site_footer_copyright' => $this->site_footer_copyright,
            'subscribe_title' => $this->subscribe_title,
            'subscribe_description' => $this->subscribe_description,
            'dark_logo' => $this->dark_logo,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,

        ];
    }
}
