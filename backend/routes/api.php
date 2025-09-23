<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/Category', function () {
//     return response()->json(Category::all());
// });
// category
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::post('/category', [CategoryController::class, 'store']);
Route::delete('/category/{id}', [CategoryController::class,'destroy']);
// property
Route::get('/property', [PropertyController::class, 'index']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::post('/property', [PropertyController::class, 'store']);
Route::delete('/property/{id}', [PropertyController::class, 'destroy']);

