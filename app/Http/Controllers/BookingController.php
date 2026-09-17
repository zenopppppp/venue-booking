<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // View customer's own booking requests
    public function index()
    {
        $bookings = Booking::with('venue')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    // Store a new booking request submitted by a customer
    // Accepts $venue directly from route binding: /venues/{venue}/book
    public function store(Request $request, Venue $venue)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Check for conflicting pending or approved bookings
        $hasOverlap = Booking::where('venue_id', $venue->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('start_date', '<=', $endDate)
                    ->whereDate('end_date', '>=', $startDate);
            })
            ->exists();

        if ($hasOverlap) {
            // Return JSON error for AJAX
            return response()->json([
                'message' => 'This venue is already booked or has a pending request for the selected dates.'
            ], 422);
        }

        // Calculate total days and total price
        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon   = Carbon::parse($endDate);
        $days = $startDateCarbon->diffInDays($endDateCarbon) + 1;

        $totalPrice = $days * ($venue->price_per_day ?? 0);

        // Create the booking record
        Booking::create([
            'user_id'     => Auth::id(),
            'venue_id'    => $venue->id,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        // Return JSON success for AJAX
        return response()->json([
            'message'  => 'Booking request submitted successfully!',
            'redirect' => route('bookings.index')
        ], 200);
    }


    // View all incoming requests for the vendor's venues
    public function vendorBookings()
    {
        $bookings = Booking::whereHas('venue', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['venue', 'user'])
            ->latest()
            ->get();

        return view('bookings.vendor', compact('bookings'));
    }

    // Update booking status (Approve / Reject)
    public function updateStatus(Request $request, Booking $booking)
    {
        if ($booking->venue->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Booking status updated to ' . $request->status);
    }

    // Cancel a customer's own pending booking
    public function destroy(Booking $booking)
    {
        // Only the booking owner can cancel
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Only pending bookings can be cancelled
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
