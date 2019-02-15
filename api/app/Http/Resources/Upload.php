<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Upload extends JsonResource
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
            'name' => $this->name,
            'filename' => $this->filename,
            'filetype' => $this->filetype,
            'public_path' => $this->public_path,
            'public_thumb_path' => $this->public_thumb_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
