<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitacionController;

Route::get('/habitaciones', [HabitacionController::class, 'index']);
Route::post('/habitaciones', [HabitacionController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
