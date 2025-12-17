<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Product_id'  => $this->id,
            'name'        => $this->name,
            'price'       => $this->price,
            'category_id' => $this->category_id,
            'media'      => MediaResource::collection($this->media),

            // 'media' => new MediaResource($this->media),


            //'media' => ProductMediaResource::collection($this->taskMedia),

        ];
    }
}
