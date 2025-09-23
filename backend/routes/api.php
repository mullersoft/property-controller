<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/Category', function () {
//     return response()->json(Category::all());
// });
// category
Route::get('/Category', [CategoryController::class, 'index']);
Route::get('/Category/{id}', [CategoryController::class, 'show']);
Route::get('/Category/create', [CategoryController::class, 'store']);
// property
Route::get('/property', [PropertyController::class, 'index']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
