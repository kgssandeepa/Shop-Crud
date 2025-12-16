<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = [];

 public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
