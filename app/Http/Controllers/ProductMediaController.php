<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Product;
use App\Models\ProductImage;

use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use App\Http\Resources\ProductMediaResoure;
use App\Http\Requests\StoreProductImageRequest;

class ProductMediaController extends Controller
{

    public function attach(StoreProductImageRequest $request)
    {
        // 1. Upload file
        $file = $request->file('file');

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/media', $fileName, 'public');

        // 2. Save media
        $media = Media::create([
            'display_name' => $file->getClientOriginalName(),
            'name' => $fileName,
            'path' => 'storage/' . $path,
            'type' => $file->getClientOriginalExtension(),
        ]);

        // 3. Attach media to product
        $productImage = ProductImage::create([
            'product_id' => $request->product_id,
            'media_id' => $media->id,
        ]);

        return response()->json([
            'message' => 'Image attached successfully',
            'data' => [
                'product_id' => $productImage->product->id,
                'product_name' => $productImage->product->name,
                'category_name' => $productImage->product->category->name,
                'media_id' => $media->id,
              

            ]
        ]);
    }
}
