<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SlotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::apiResource('slots', SlotController::class)->only(['store', 'index', 'update']);
    Route::apiResource('bookings', BookingController::class)->only(['store', 'index', 'destroy']);
    Route::get('logout', [AuthController::class, 'logout']);
});
