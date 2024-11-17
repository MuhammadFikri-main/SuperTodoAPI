<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/task', [TaskController::class, 'index'])->name('task.index');
// Route::get('/task/create', [TaskController::class, 'create'])->name('task.create'); //Not Exist in apiResources
// Route::post('/task', [TaskController::class, 'store'])->name('task.store');
// Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');
// Route::get('/task/{id}/edit', [TaskController::class, 'edit'])->name('task.edit'); //Not Exist in apiResources
// Route::put('/task/{id}', [TaskController::class, 'update'])->name('task.update');
// Route::delete('/task/{id}', [TaskController::class, 'delete'])->name('task.destroy');
// Route::resource('task', TaskController::class);