<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
                <h3 class="text-2xl font-bold text-gray-900">
                    Welcome back, {{ Auth::user()->name }}!
                </h3>
                <p class="text-gray-600 mt-1 text-sm">
                    You're logged in as <strong class="capitalize">{{ Auth::user()->role }}</strong>.
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @if (Auth::user()->role === 'vendor')
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">My Venues</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_venues'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Pending</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Revenue</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">
                        ₹{{ number_format($stats['revenue'], 0) }}
                    </p>
                </div>
                @else
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">My Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Pending</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Approved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['approved'] }}</p>
                </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
                <h4 class="text-lg font-bold text-gray-900 mb-3">Quick Actions</h4>
                <div class="flex flex-wrap gap-3">
                    @if (Auth::user()->role === 'vendor')
                    <a href="{{ route('venues.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700">
                        + Add New Venue
                    </a>
                    <a href="{{ route('bookings.vendor') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md text-sm font-semibold hover:bg-gray-200">
                        View All Bookings
                    </a>
                    @else
                    <a href="{{ route('venues.catalog') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700">
                        Browse Venues
                    </a>
                    <a href="{{ route('bookings.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md text-sm font-semibold hover:bg-gray-200">
                        My Bookings
                    </a>
                    @endif
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Recent Activity</h4>

                @if (Auth::user()->role === 'vendor')
                @forelse ($recentBookings as $booking)
                <div class="py-3 border-b last:border-b-0 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $booking->venue->name }}</p>
                        <p class="text-xs text-gray-500">
                            by {{ $booking->user->name }} —
                            {{ $booking->start_date }} to {{ $booking->end_date }}
                        </p>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                @if ($booking->status === 'approved') bg-green-100 text-green-700
                                @elseif ($booking->status === 'rejected') bg-red-100 text-red-700
                                @else bg-amber-100 text-amber-700 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">No bookings yet.</p>
                @endforelse
                @else
                @forelse ($myBookings as $booking)
                <div class="py-3 border-b last:border-b-0 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $booking->venue->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $booking->start_date }} to {{ $booking->end_date }}
                        </p>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full
                                @if ($booking->status === 'approved') bg-green-100 text-green-700
                                @elseif ($booking->status === 'rejected') bg-red-100 text-red-700
                                @else bg-amber-100 text-amber-700 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">You haven't made any bookings yet.</p>
                @endforelse
                @endif
            </div>

        </div>
    </div>
</x-app-layout>