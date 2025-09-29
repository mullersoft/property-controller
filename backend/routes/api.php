<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
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
Route::put('/category/{id}', [CategoryController::class, 'update']);
// property
Route::get('/property', [PropertyController::class, 'index']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::post('/property', [PropertyController::class, 'store']);
Route::delete('/property/{id}', [PropertyController::class, 'destroy']);
Route::put('/property/{id}', [PropertyController::class, 'update']);
//employee
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::post('/employee', [EmployeeController::class, 'store']);
Route::put('/employee/{id}', [EmployeeController::class, 'update']);
Route::delete('/employee/{id}',[EmployeeController::class,'destroy']);
//assignments
Route::get('/assignment',[AssignmentController::class,'index']);
Route::get('/assignment/{id}',[AssignmentController::class,'show']);
Route::post('/assignment',[AssignmentController::class,'store']);
Route::put('/assignment{id}',[AssignmentController::class,'update']);
Route::delete('/assignment{id}',[AssignmentController::class,'destroy']);
//auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);
