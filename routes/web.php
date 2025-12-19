<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-config', function () {
    return response()->json(config('common.images'));
});
