<?php

use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/get/tasks', [TasksController::class, 'getTasks']);
Route::post('/store/task', [TasksController::class, 'storeTask']);
Route::post('/complete/task', [TasksController::class, 'completeTask']);
Route::post('/update/task', [TasksController::class, 'updateTask']);
