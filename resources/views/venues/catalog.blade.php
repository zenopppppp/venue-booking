<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Venues
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @forelse($venues as $venue)

                <div id="venue-card-{{ $venue->id }}"
                    class="bg-white overflow-hidden shadow-sm rounded-lg p-6 flex flex-col justify-between border">

                    <div>
                        <!-- Venue Name & Rating -->
                        <div class="flex justify-between items-center mb-2">

                            <a href="{{ route('venues.show', $venue) }}"
                                class="text-xl font-bold text-gray-900 hover:text-blue-600 transition">
                                {{ $venue->name }}
                            </a>

                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                                ★ {{ number_format((float) ($venue->averageRating() ?? 0), 1) }}
                            </span>

                        </div>

                        <!-- Venue Location -->
                        <p class="text-sm text-gray-600 mb-4">
                            📍 {{ $venue->location }}
                        </p>

                        <!-- Description -->
                        <p class="text-gray-700 text-sm mb-4">
                            {{ Str::limit($venue->description, 100) }}
                        </p>

                        <!-- Capacity -->
                        <div class="text-sm text-gray-500 mb-2">
                            Capacity:
                            <strong>{{ $venue->capacity }} guests</strong>
                        </div>

                        <!-- Price -->
                        <div class="text-lg font-bold text-blue-600 mb-4">
                            ₹{{ number_format($venue->price_per_day, 2) }} / day
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form class="booking-form mt-4 border-t pt-4" data-venue-id="{{ $venue->id }}">
                        @csrf

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div>
                                <label class="block text-xs text-gray-600 font-semibold mb-1">
                                    Start Date
                                </label>
                                <input type="date"
                                    name="start_date"
                                    class="start-date w-full text-xs p-1 border rounded-md"
                                    required>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 font-semibold mb-1">
                                    End Date
                                </label>
                                <input type="date"
                                    name="end_date"
                                    class="end-date w-full text-xs p-1 border rounded-md"
                                    required>
                            </div>
                        </div>

                        <button type="submit"
                            class="submit-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition">
                            Request Booking
                        </button>

                        {{-- Hidden error box inside the card --}}
                        <div class="error-box hidden mt-3 p-2 bg-red-100 border border-red-400 text-red-700 text-xs rounded-md"></div>

                        {{-- Hidden success box inside the card --}}
                        <div class="success-box hidden mt-3 p-2 bg-green-100 border border-green-400 text-green-700 text-xs rounded-md"></div>
                    </form>

                </div>

                @empty
                <div class="col-span-3 bg-white p-6 text-center text-gray-500 rounded-lg shadow-sm">
                    No venues available right now.
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Booking AJAX Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.booking-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const venueId = this.dataset.venueId;
                    const card = document.getElementById(`venue-card-${venueId}`);
                    const start = card.querySelector('.start-date').value;
                    const end = card.querySelector('.end-date').value;
                    const errorBox = card.querySelector('.error-box');
                    const successBox = card.querySelector('.success-box');
                    const btn = card.querySelector('.submit-btn');

                    // Reset messages
                    errorBox.classList.add('hidden');
                    successBox.classList.add('hidden');
                    errorBox.innerText = '';
                    successBox.innerText = '';

                    // Button loading state
                    btn.disabled = true;
                    btn.innerText = 'Checking...';

                    try {
                        const response = await fetch(`/venues/${venueId}/book`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                    '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                start_date: start,
                                end_date: end
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            // Show error INSIDE the card
                            errorBox.innerText = data.message || 'This venue is already booked for those dates.';
                            errorBox.classList.remove('hidden');
                        } else {
                            // Show success INSIDE the card
                            successBox.innerText = data.message || 'Booking request submitted successfully!';

                            // Redirect after a short delay
                            setTimeout(() => {
                                window.location.href = data.redirect || '/my-bookings';
                            }, 1200);
                        }
                    } catch (err) {
                        errorBox.innerText = 'Network error. Please try again.';
                        errorBox.classList.remove('hidden');
                    } finally {
                        btn.disabled = false;
                        btn.innerText = 'Request Booking';
                    }
                });
            });
        });
    </script>

</x-app-layout>