<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        // Security checks: user owns booking & booking is approved
        if ($booking->user_id !== Auth::id() || $booking->status !== 'approved') {
            return back()->with('error', 'You can only review approved bookings.');
        }

        // Prevent duplicate reviews for same booking
        if ($booking->review) {
            return back()->with('error', 'You have already submitted a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'venue_id' => $booking->venue_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
