<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ---------- VENDOR DASHBOARD ----------
        if ($user->role === 'vendor') {
            $venueIds = $user->venues()->pluck('id');

            $stats = [
                'total_venues'   => $user->venues()->count(),
                'total_bookings' => Booking::whereIn('venue_id', $venueIds)->count(),
                'pending'        => Booking::whereIn('venue_id', $venueIds)->where('status', 'pending')->count(),
                'approved'       => Booking::whereIn('venue_id', $venueIds)->where('status', 'approved')->count(),
                'revenue'        => Booking::whereIn('venue_id', $venueIds)
                    ->where('status', 'approved')
                    ->sum('total_price'),
            ];

            $recentBookings = Booking::with(['venue', 'user'])
                ->whereIn('venue_id', $venueIds)
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact('stats', 'recentBookings'));
        }

        // ---------- CUSTOMER DASHBOARD ----------
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending'        => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved'       => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
        ];

        $myBookings = Booking::with('venue')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'myBookings'));
    }
}
