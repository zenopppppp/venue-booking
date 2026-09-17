<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public Catalog
Route::get('/catalog', [VenueController::class, 'catalog'])->name('venues.catalog');

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vendor Venues (registers /venues/create BEFORE wildcard matching)
    Route::resource('venues', VenueController::class)->except(['show']);

    // Customer Bookings
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/venues/{venue}/book', [BookingController::class, 'store'])->name('bookings.store');

    // Vendor Dashboard & Approval
    Route::get('/vendor/bookings', [BookingController::class, 'vendorBookings'])->name('bookings.vendor');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Reviews
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// ============================================
// PUBLIC WILDCARD ROUTES (Must stay at the bottom)
// ============================================
Route::get('/venues/{venue}', [VenueController::class, 'show'])->name('venues.show');

require __DIR__ . '/auth.php';
