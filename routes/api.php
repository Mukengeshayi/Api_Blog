<?php

use App\Http\Controllers\Admin\CategroryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/admin')->group(function(){
    //categories routes
    Route::apiResource('/categories', CategroryController::class);
    Route::get('/categories/{search}', [CategroryController::class, 'search']);

    //posts routes
    Route::apiResource('/posts', PostController::class);
});
