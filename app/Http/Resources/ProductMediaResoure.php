<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMediaResoure extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'product_id' => $this->product_id,
      'media_id' => $this->media_id,
      'product_name' => $this->product_name,
      'category_name' => $this->category_name,
    ];
  }
}
