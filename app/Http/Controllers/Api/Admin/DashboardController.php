<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Admin Dashboard",
 *     description="API Endpoints for admin dashboard and statistics"
 * )
 */
class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/dashboard",
     *     summary="Get admin dashboard overview",
     *     tags={"Admin Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dashboard data retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="overview", type="object",
     *                     @OA\Property(property="total_users", type="integer", example=150),
     *                     @OA\Property(property="total_bookings", type="integer", example=75),
     *                     @OA\Property(property="total_revenue", type="number", format="float", example=50000000),
     *                     @OA\Property(property="occupancy_rate", type="number", format="float", example=85.5)
     *                 ),
     *                 @OA\Property(property="recent_bookings", type="array", @OA\Items(ref="#/components/schemas/Booking")),
     *                 @OA\Property(property="room_status", type="object",
     *                     @OA\Property(property="available", type="integer", example=45),
     *                     @OA\Property(property="occupied", type="integer", example=10),
     *                     @OA\Property(property="maintenance", type="integer", example=1),
     *                     @OA\Property(property="cleaning", type="integer", example=0)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     )
     * )
     */
    public function index()
    {
        // Overview statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        
        // Calculate occupancy rate
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Recent bookings (last 10)
        $recentBookings = Booking::with(['user', 'roomType', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Room status breakdown
        $roomStatus = [
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
            'cleaning' => Room::where('status', 'cleaning')->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'overview' => [
                    'total_users' => $totalUsers,
                    'total_bookings' => $totalBookings,
                    'total_revenue' => (float) $totalRevenue,
                    'occupancy_rate' => round($occupancyRate, 2),
                ],
                'recent_bookings' => $recentBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                        'user_name' => $booking->user->name,
                        'room_type' => $booking->roomType->name,
                        'room_number' => $booking->room?->room_number,
                        'check_in_date' => $booking->check_in_date->format('Y-m-d'),
                        'check_out_date' => $booking->check_out_date->format('Y-m-d'),
                        'status' => $booking->status,
                        'total_amount' => (float) $booking->total_amount,
                        'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'room_status' => $roomStatus,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/dashboard/stats",
     *     summary="Get detailed statistics",
     *     tags={"Admin Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="period",
     *         in="query",
     *         description="Statistics period",
     *         required=false,
     *         @OA\Schema(type="string", enum={"daily","weekly","monthly"}, example="monthly")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statistics retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="booking_trends", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="revenue_trends", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="room_type_popularity", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="payment_methods", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     )
     * )
     */
    public function stats(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        // Determine date range and grouping
        switch ($period) {
            case 'daily':
                $startDate = Carbon::now()->subDays(30);
                $dateFormat = '%Y-%m-%d';
                break;
            case 'weekly':
                $startDate = Carbon::now()->subWeeks(12);
                $dateFormat = '%Y-%u';
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now()->subMonths(12);
                $dateFormat = '%Y-%m';
                break;
        }

        // Booking trends
        $bookingTrends = Booking::selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period, COUNT(*) as count")
            ->where('created_at', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Revenue trends
        $revenueTrends = Payment::selectRaw("DATE_FORMAT(paid_at, '{$dateFormat}') as period, SUM(amount) as total")
            ->where('status', 'paid')
            ->where('paid_at', '>=', $startDate)
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Room type popularity
        $roomTypePopularity = Booking::selectRaw('room_types.name, COUNT(*) as bookings')
            ->join('room_types', 'bookings.room_type_id', '=', 'room_types.id')
            ->where('bookings.created_at', '>=', $startDate)
            ->groupBy('room_types.id', 'room_types.name')
            ->orderBy('bookings', 'desc')
            ->get();

        // Payment methods
        $paymentMethods = Payment::selectRaw('payment_method, COUNT(*) as count')
            ->where('status', 'paid')
            ->where('paid_at', '>=', $startDate)
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Statistics retrieved successfully',
            'data' => [
                'booking_trends' => $bookingTrends->map(function ($item) {
                    return [
                        'period' => $item->period,
                        'count' => $item->count,
                    ];
                }),
                'revenue_trends' => $revenueTrends->map(function ($item) {
                    return [
                        'period' => $item->period,
                        'total' => (float) $item->total,
                    ];
                }),
                'room_type_popularity' => $roomTypePopularity->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'bookings' => $item->bookings,
                    ];
                }),
                'payment_methods' => $paymentMethods->map(function ($item) {
                    return [
                        'method' => $item->payment_method,
                        'count' => $item->count,
                    ];
                }),
            ],
        ]);
    }
}
