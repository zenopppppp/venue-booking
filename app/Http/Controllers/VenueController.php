<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->venues();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // FIX: search location column, not city
        if ($request->filled('city')) {
            $query->where('location', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $venues = $query->latest()->paginate(9)->withQueryString();

        return view('venues.index', compact('venues'));
    }

    public function create()
    {
        return view('venues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'city'          => 'nullable|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'capacity'      => 'required|integer|min:1',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('venues', 'public');
        }

        Auth::user()->venues()->create($validated);

        return redirect()->route('venues.index')
            ->with('success', 'Venue added successfully!');
    }
    public function catalog(Request $request)
    {
        $query = Venue::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('city')) {
            // Search in BOTH location and city (case-insensitive)
            $query->where(function ($q) use ($request) {
                $q->where('location', 'ILIKE', '%' . $request->city . '%')
                    ->orWhere('city', 'ILIKE', '%' . $request->city . '%');
            });
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $venues = $query->latest()->paginate(9)->withQueryString();

        return view('venues.catalog', compact('venues'));
    }



    public function edit(Venue $venue)
    {
        if ($venue->user_id !== Auth::id()) {
            abort(403);
        }
        return view('venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        if ($venue->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'city'          => 'nullable|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'capacity'      => 'required|integer|min:1',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('venues', 'public');
        }

        $venue->update($validated);

        return redirect()->route('venues.index')
            ->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        if ($venue->user_id !== Auth::id()) {
            abort(403);
        }
        $venue->delete();
        return redirect()->route('venues.index')
            ->with('success', 'Venue deleted successfully!');
    }

    public function show(Venue $venue)
    {
        $venue->load(['reviews.user']);
        return view('venues.show', compact('venue'));
    }
}
