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
                        <div class="flex justify-between items-center mb-2">
                            <a href="{{ route('venues.show', $venue) }}"
                                class="text-xl font-bold text-gray-900 hover:text-blue-600 transition">
                                {{ $venue->name }}
                            </a>
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                                ★ {{ number_format((float) ($venue->averageRating() ?? 0), 1) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">📍 {{ $venue->location }}</p>

                        <p class="text-gray-700 text-sm mb-4">
                            {{ Str::limit($venue->description, 100) }}
                        </p>

                        <div class="text-sm text-gray-500 mb-2">
                            Capacity: <strong>{{ $venue->capacity }} guests</strong>
                        </div>

                        <div class="text-lg font-bold text-blue-600 mb-4">
                            ₹{{ number_format($venue->price_per_day, 2) }} / day
                        </div>
                    </div>

                    <!-- Booking Form (logged-in users only) -->
                    @if (auth()->check() && auth()->user()->role === 'customer')
                    <form class="booking-form mt-4 border-t pt-4" data-venue-id="{{ $venue->id }}">
                        @csrf

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div>
                                <label class="block text-xs text-gray-600 font-semibold mb-1">Start Date</label>
                                <input type="date" name="start_date" class="start-date w-full text-xs p-1 border rounded-md" required>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 font-semibold mb-1">End Date</label>
                                <input type="date" name="end_date" class="end-date w-full text-xs p-1 border rounded-md" required>
                            </div>
                        </div>

                        <button type="submit"
                            class="submit-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition">
                            Request Booking
                        </button>

                        <div class="error-box hidden mt-3 p-2 bg-red-100 border border-red-400 text-red-700 text-xs rounded-md"></div>
                        <div class="success-box hidden mt-3 p-2 bg-green-100 border border-green-400 text-green-700 text-xs rounded-md"></div>
                    </form>

                    @elseif (auth()->check() && auth()->user()->role === 'vendor')
                    <div class="mt-4 border-t pt-4 text-center">
                        <p class="text-xs text-gray-500 mb-2">You're a vendor — you can't book venues.</p>
                        <a href="{{ route('venues.index') }}"
                            class="inline-block w-full bg-gray-200 text-gray-700 text-sm font-semibold py-2 px-4 rounded-md hover:bg-gray-300 transition">
                            Manage My Venues
                        </a>
                    </div>

                    @else
                    <div class="mt-4 border-t pt-4 text-center">
                        <p class="text-xs text-gray-500 mb-2">Log in to book this venue</p>
                        <a href="{{ route('login') }}"
                            class="inline-block w-full bg-indigo-600 text-white text-sm font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                            Log in to Book
                        </a>
                    </div>
                    @endif


                </div>

                @empty
                <div class="col-span-3 bg-white p-6 text-center text-gray-500 rounded-lg shadow-sm">
                    No venues available right now.
                </div>
                @endforelse

            </div>
        </div>
    </div>

    @auth
    {{-- Booking AJAX Script (only needed for logged-in users) --}}
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

                    errorBox.classList.add('hidden');
                    successBox.classList.add('hidden');
                    errorBox.innerText = '';
                    successBox.innerText = '';

                    btn.disabled = true;
                    btn.innerText = 'Checking...';

                    try {
                        const response = await fetch(`/venues/${venueId}/book`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                start_date: start,
                                end_date: end
                            })
                        });

                        // Session expired / not authenticated
                        if (response.status === 401) {
                            window.location.href = '/login';
                            return;
                        }

                        const data = await response.json();

                        if (!response.ok) {
                            errorBox.innerText = data.message || 'This venue is already booked for those dates.';
                            errorBox.classList.remove('hidden');
                        } else {
                            successBox.innerText = data.message || 'Booking request submitted successfully!';
                            successBox.classList.remove('hidden');
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
    @endauth

</x-app-layout>