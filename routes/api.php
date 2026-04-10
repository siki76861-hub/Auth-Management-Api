<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

// Auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function(){

Route::post('/logout',[AuthController::class,'logout']);

// CRUD (manual)
Route::get('/posts',[PostController::class,'index']);
Route::post('/posts',[PostController::class,'store']);
Route::get('/posts/{id}',[PostController::class,'show']);
Route::put('/posts/{id}',[PostController::class,'update']);
Route::delete('/posts/{id}',[PostController::class,'destroy']);
});
