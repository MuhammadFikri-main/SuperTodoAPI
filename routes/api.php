<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/', function(){
// 	return 'API';
// });

// Route::controller(RegisterController::class)->group(function(){
//     Route::post('register', 'registration');
// });

// Route::controller(UserController::class)->group(function(){
//     Route::post('login', 'login')->name('login');
// });

// Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::controller(AuthController::class)->group( function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('task', TaskController::class); //->middleware('auth:sanctum');
// Route::middleware('auth:sanctum')->group( function () {

//     Route::apiResource('task', TaskController::class);

// });


