<?php

namespace App\Models;

use App\Enums\MediaTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    // public function getPaths()
    // {
    //         $imageSizes = config('common.imagesSizes');
    //         $paths = [];
    //         foreach ($imageSizes as $imageSize) {
    //             if($this->type == MediaTypeEnum::IMAGE){
    //                 $path = asset('storage/uploads/'.$this->type.'/'. $imageSize['name'] . '/' . $this->name);
    //                 $paths[$imageSize['name']] = $path;
    //             }
    //             else{
    //                 $path = asset('storage/uploads/'.$this->type. '/' . $this->name);
    //                 $paths[$imageSize['name']] = $path;
    //             }
    //         }
    //     return $paths;
    // }

    public function getPaths()
{
    return $this->path;
}
}
