<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;



class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)

    {
        //dd('log');
        $validated = $request->validated();
        
        //dd('log');
        $category = Category::create($validated);
    //dd('log');
        return response()->json([
            'message' => 'Category Create Succussfully',
            'data' => new CategoryResource($category)
        ],201);
    }

    public function index()
    {
        $category = Category::all();

        return response()->json([
            'data' => CategoryResource::collection($category),
        ]);
    }
}
