<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UpdateProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)

    {
        $validated = $request->validated();

        $product = Product::create($validated);

        return response()->json([
            'message' => 'product Create Succussfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function index()
    {
        $product = Product::all();

        return response()->json([
            'data' => ProductResource::collection($product),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => new UpdateProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' =>'Product Deleted Succuessfully'
        ],200);
    }
}
