<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Admin\DashboardController;

/**
 * @OA\Info(
 *     title="AoraGrand Hotel Booking API",
 *     version="1.0.0",
 *     description="RESTful API for AoraGrand Hotel Booking System",
 *     @OA\Contact(
 *         email="admin@aoragrand.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// Room Types - Public access
Route::get('/room-types', [RoomTypeController::class, 'index'])->name('api.room-types.index');
Route::get('/room-types/{roomType}', [RoomTypeController::class, 'show'])->name('api.room-types.show');

// Payment webhook (public for Midtrans callback)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('api.payment.webhook');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('api.profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.profile.update');

    // Bookings - RESTful resource routes with explicit names
    Route::apiResource('bookings', BookingController::class)->names([
        'index' => 'api.bookings.index',
        'store' => 'api.bookings.store',
        'show' => 'api.bookings.show',
        'update' => 'api.bookings.update',
        'destroy' => 'api.bookings.destroy',
    ]);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('api.bookings.cancel');

    // Payments - RESTful resource routes with explicit names
    Route::apiResource('payments', PaymentController::class)->only(['index', 'store', 'show'])->names([
        'index' => 'api.payments.index',
        'store' => 'api.payments.store',
        'show' => 'api.payments.show',
    ]);
    Route::get('/payments/{payment}/status', [PaymentController::class, 'status'])->name('api.payments.status');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('api.admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // Room Types Management
        Route::apiResource('room-types', RoomTypeController::class)->except(['index', 'show'])->names([
            'store' => 'room-types.store',
            'update' => 'room-types.update',
            'destroy' => 'room-types.destroy',
        ]);

        // Bookings Management
        Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
        Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
        Route::patch('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.check-out');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'adminCancel'])->name('bookings.cancel');

        // Payments Management
        Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
        Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

        // Users Management
        Route::get('/users', [AuthController::class, 'adminUsers'])->name('users.index');
        Route::patch('/users/{user}/toggle-status', [AuthController::class, 'toggleUserStatus'])->name('users.toggle-status');
    });
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
    ], 404);
})->name('api.fallback'); 