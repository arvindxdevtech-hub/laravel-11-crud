<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Authcontroller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/signup', [Authcontroller::class, 'signup']);
Route::post('/login', [Authcontroller::class, 'login']);


// Route::post('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum'); 
// Route::apiResource('posts', App\Http\Controllers\API\Postcontroller::class)->middleware('auth:sanctum');

// middleware using group
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
    Route::apiResource('posts', App\Http\Controllers\API\Postcontroller::class);
});
