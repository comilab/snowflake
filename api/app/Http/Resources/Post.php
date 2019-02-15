<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body !== null ? $this->body : '',
            'description' => $this->description,
            'is_public' => $this->is_public,
            'likes' => $this->likes,
            'created_at' => $this->created_at->format('Y-m-d\TH:i'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i'),
            'tags' => $this->whenLoaded('tagged', function() {
                return $this->tags;
            }),
            'tagged' => $this->whenLoaded('tagged'),
            'comments_count' => $this->when(isset($this->comments_count), function() {
                return $this->comments_count;
            }),
        ];
    }
}
