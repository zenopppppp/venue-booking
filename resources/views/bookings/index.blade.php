<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Bookings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">

                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3">Venue</th>
                                <th class="p-3">Dates</th>
                                <th class="p-3">Calculation (Days × Price/Day)</th>
                                <th class="p-3">Total Price</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            @php
                            $start = \Carbon\Carbon::parse($booking->start_date);
                            $end = \Carbon\Carbon::parse($booking->end_date);
                            $calculatedDays = $start->diffInDays($end) ?: 1;
                            @endphp
                            <tr class="border-b align-top">
                                {{-- Venue --}}
                                <td class="p-3 font-semibold">{{ $booking->venue->name }}</td>

                                {{-- Dates --}}
                                <td class="p-3 text-sm text-gray-600">
                                    {{ $booking->start_date }} to {{ $booking->end_date }}
                                </td>

                                {{-- Calculation --}}
                                <td class="p-3 text-sm text-gray-600">
                                    {{ $calculatedDays }} days × ₹{{ number_format($booking->venue->price_per_day, 2) }}
                                </td>

                                {{-- Total Price --}}
                                <td class="p-3 font-bold text-blue-600">
                                    ₹{{ number_format($booking->total_price, 2) }}
                                </td>

                                {{-- Status + Actions --}}
                                <td class="p-3">
                                    <div class="flex flex-col items-start gap-1">

                                        {{-- Status Badge --}}
                                        <span class="px-2 py-1 text-xs rounded-full font-bold uppercase
                                        {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' :
                                           ($booking->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                           'bg-yellow-100 text-yellow-800') }}">
                                            {{ $booking->status }}
                                        </span>

                                        {{-- Cancel button (only for PENDING) --}}
                                        @if ($booking->status === 'pending')
                                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                class="text-xs text-red-600 hover:text-red-800 font-semibold underline">
                                                Cancel Booking
                                            </button>
                                        </form>
                                        @endif

                                        {{-- Already-reviewed indicator (only for APPROVED + reviewed) --}}
                                        @if ($booking->status === 'approved' && $booking->review)
                                        <span class="text-xs text-green-600 font-semibold">
                                            ✓ Reviewed: {{ $booking->review->rating }}/5 Stars
                                        </span>
                                        @endif

                                        {{-- Review form (only for APPROVED + NOT reviewed) --}}
                                        @if ($booking->status === 'approved' && !$booking->review)
                                        <form action="{{ route('reviews.store', $booking->id) }}" method="POST"
                                            class="mt-2 bg-gray-50 p-3 rounded border w-full">
                                            @csrf
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                                Leave a Review
                                            </label>
                                            <div class="flex items-center space-x-2 mb-2">
                                                <select name="rating"
                                                    class="text-sm border-gray-300 rounded focus:ring-blue-500">
                                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                                    <option value="2">⭐⭐ (2/5)</option>
                                                    <option value="1">⭐ (1/5)</option>
                                                </select>
                                            </div>
                                            <textarea name="comment" rows="2"
                                                placeholder="How was your experience?"
                                                class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 mb-2"></textarea>
                                            <button type="submit"
                                                class="bg-blue-600 text-white font-bold text-xs py-1 px-3 rounded hover:bg-blue-700">
                                                Submit Review
                                            </button>
                                        </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">
                                    No bookings requested yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>