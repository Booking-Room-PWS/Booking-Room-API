<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

//* testing ping
Route::get('ping', function(){ return ['pong' => true]; });

/*
| Public auth
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

/*
| Protected routes (require Bearer token)
*/
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    // Rooms (all authenticated users can read)
    Route::get('rooms', [RoomController::class, 'index']);
    Route::get('rooms/{room}', [RoomController::class, 'show']);

    // Rooms (admin only)
    Route::middleware('is_admin')->group(function () {
        Route::post('rooms', [RoomController::class, 'store']);
        Route::put('rooms/{room}', [RoomController::class, 'update']);
        Route::patch('rooms/{room}', [RoomController::class, 'patch']);
        Route::delete('rooms/{room}', [RoomController::class, 'destroy']);
    });

    // Bookings (user)
    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Bookings (admin)
    Route::middleware('is_admin')->group(function () {
        Route::patch('bookings/{booking}/approve', [BookingController::class, 'approve']);
        Route::patch('bookings/{booking}/reject', [BookingController::class, 'reject']);
    });
});
