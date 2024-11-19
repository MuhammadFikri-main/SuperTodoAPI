<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserTemplateController;


Route::controller(AuthController::class)->group( function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('task', TaskController::class);
Route::apiResource('template', TemplateController::class);

#region Templates
Route::get('getListOfTemplates', [TemplateController::class, 'getListOfTemplates']);
Route::post('useTemplate', [TemplateController::class, 'useTemplate']);
#endregion

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/', function(){
// 	return 'API';
// });

// Route::middleware('auth:sanctum')->group( function () {

//     Route::apiResource('task', TaskController::class);

// });


