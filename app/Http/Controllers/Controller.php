<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="AoraGrand Hotel Booking API",
 *     version="1.0.0",
 *     description="RESTful API for AoraGrand Hotel Booking System with Midtrans payment integration",
 *     @OA\Contact(
 *         email="admin@aoragrand.com",
 *         name="AoraGrand Hotel Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 * @OA\Server(
 *     url="https://api.aoragrand.com",
 *     description="Production server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter token in format: Bearer {token}"
 * )
 */
abstract class Controller
{
    //
}
