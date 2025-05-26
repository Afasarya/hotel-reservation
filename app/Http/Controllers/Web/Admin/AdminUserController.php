<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('email_verified_at', $request->status === 'verified' ? '!=' : '=', null);
        }
        
        $users = $query->withCount('bookings')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show(User $user)
    {
        $user->load(['bookings.roomType', 'bookings.payment']);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = 'User has been deactivated.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = 'User has been activated.';
        }
        
        return back()->with('success', $message);
    }
} 