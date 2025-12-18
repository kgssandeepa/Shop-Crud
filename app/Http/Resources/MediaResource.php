<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id' => $this->media->id,
            'display_name' => $this->media->display_name,
            'type' => $this->media->type,
            'paths' => $this->media->getPaths()
            
        ];
    }
}
