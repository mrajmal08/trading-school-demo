<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailResource extends JsonResource
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

            // 'blog_category' => new BlogCategoryResource($this->blogCategory),
            'uuid' => $this->uuid,
            'user_name' => $this->user_name,
            'user_image' => $this->user_image,
            'title' => $this->title,
            'detail' => $this->detail,
            'picture' => $this->picture,
            'date' => $this->date,
            'tags' => $this->tags,
            'publish' => $this->publish,

        ];
    }
}
