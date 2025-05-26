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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Room Types - Public access
Route::get('/room-types', [RoomTypeController::class, 'index']);
Route::get('/room-types/{roomType}', [RoomTypeController::class, 'show']);

// Payment webhook (public for Midtrans callback)
Route::post('/payment/webhook', [PaymentController::class, 'webhook']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Bookings
    Route::apiResource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Payments
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::post('/payments/create', [PaymentController::class, 'create']);
    Route::get('/payments/{payment}/status', [PaymentController::class, 'status']);

    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // Room Types Management
        Route::apiResource('room-types', RoomTypeController::class)->except(['index', 'show']);

        // Bookings Management
        Route::get('/bookings', [BookingController::class, 'adminIndex']);
        Route::put('/bookings/{booking}/confirm', [BookingController::class, 'confirm']);
        Route::put('/bookings/{booking}/check-in', [BookingController::class, 'checkIn']);
        Route::put('/bookings/{booking}/check-out', [BookingController::class, 'checkOut']);
        Route::put('/bookings/{booking}/cancel', [BookingController::class, 'adminCancel']);

        // Payments Management
        Route::get('/payments', [PaymentController::class, 'adminIndex']);
        Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt']);

        // Users Management
        Route::get('/users', [AuthController::class, 'adminUsers']);
        Route::put('/users/{user}/toggle-status', [AuthController::class, 'toggleUserStatus']);
    });
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
    ], 404);
}); 