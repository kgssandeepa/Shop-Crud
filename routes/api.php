<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductMediaController;

Route::post('/category', [CategoryController::class, 'store']);
Route::get('/category', [CategoryController::class, 'index']);

Route::post('/product',  [ProductController::class, 'store']);
Route::get('/product', [ProductController::class, 'index']);
Route::put('/product/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);


Route::post('/media/upload', [MediaController::class, 'upload']);
Route::post('/media/upload-multiple', [MediaController::class, 'uploadMultiple']);

Route::post('/Product-media-attach', [ProductMediaController::class, 'attach']);
//Route::get('/Product-media-attach',[ProductController::class,'index']);
