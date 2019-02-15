<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Page extends JsonResource
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
            'body' => $this->body,
            'description' => $this->description,
            'slug' => $this->slug,
            'is_public' => $this->is_public,
            'created_at' => $this->created_at->format('Y-m-d\TH:i'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i'),
        ];
    }
}
