<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $venue->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Left Column: Venue Details --}}
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm border border-gray-100">

                    {{-- Image --}}
                    <div class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden mb-6 flex items-center justify-center text-gray-500">
                        @if($venue->image)
                        <img src="{{ asset('storage/' . $venue->image) }}"
                            alt="{{ $venue->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <span class="text-gray-400">No Image Available</span>
                        @endif
                    </div>

                    {{-- Title + Rating --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $venue->name }}</h1>
                            <p class="text-gray-500 text-sm mt-1">📍 {{ $venue->location }}</p>
                        </div>

                        @php $rating = $venue->averageRating(); @endphp

                        @if (is_numeric($rating))
                        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full">
                            ★ {{ number_format($rating, 1) }}
                        </span>
                        @else
                        <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                            New
                        </span>
                        @endif
                    </div>

                    <hr class="my-4 border-gray-100">

                    <h3 class="text-lg font-semibold text-gray-800 mb-2">About this venue</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        {{ $venue->description ?? 'No description available for this venue.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <span class="text-gray-600 text-sm font-medium">Capacity</span>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($venue->capacity) }} guests</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <span class="text-gray-600 text-sm font-medium">Pricing</span>
                            <p class="text-lg font-bold text-gray-900">
                                ₹{{ number_format($venue->price_per_day, 2) }}
                                <span class="text-xs font-normal text-gray-500">/ day</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Booking Form --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 h-fit">

                    @if (auth()->check() && auth()->user()->role === 'customer')
                    {{-- CUSTOMER: Show booking form --}}
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Reserve Venue</h3>

                    <form class="booking-form space-y-4" data-venue-id="{{ $venue->id }}">
                        @csrf

                        <div>
                            <label for="start_date" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                Check-in Date
                            </label>
                            <input type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                                class="start-date w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                Check-out Date
                            </label>
                            <input type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                                class="end-date w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <button type="submit"
                            class="submit-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-md shadow text-sm transition duration-150 flex items-center justify-center gap-2">
                            <svg class="spinner hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="btn-text">Request Booking</span>
                        </button>

                        <div class="error-box hidden p-3 bg-red-100 border border-red-400 text-red-700 text-xs rounded-md"></div>
                        <div class="success-box hidden p-3 bg-green-100 border border-green-400 text-green-700 text-xs rounded-md"></div>
                    </form>

                    @elseif (auth()->check() && auth()->user()->role === 'vendor')
                    {{-- VENDOR: Show message --}}
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Vendor View</h3>

                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500 mb-3">You're a vendor — you can't book venues.</p>
                        <a href="{{ route('venues.index') }}"
                            class="inline-block w-full bg-gray-200 text-gray-700 text-sm font-semibold py-2.5 px-4 rounded-md hover:bg-gray-300 transition">
                            Manage My Venues
                        </a>
                    </div>

                    @else
                    {{-- GUEST: Show login prompt --}}
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Get Started</h3>

                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500 mb-3">Log in to book this venue</p>
                        <a href="{{ route('login') }}"
                            class="inline-block w-full bg-indigo-600 text-white text-sm font-semibold py-2.5 px-4 rounded-md hover:bg-indigo-700 transition mb-2">
                            Log in to Book
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-block w-full bg-gray-100 text-gray-700 text-sm font-semibold py-2.5 px-4 rounded-md hover:bg-gray-200 transition">
                            Create an Account
                        </a>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Customer Reviews Section --}}
            <div class="mt-10 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Customer Reviews</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Real feedback from past guests</p>
                    </div>

                    @php $rating = $venue->averageRating(); @endphp

                    <div class="flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                        @if (is_numeric($rating))
                        <span class="text-amber-500 font-bold text-lg">★ {{ number_format($rating, 1) }}</span>
                        <span class="text-xs text-gray-600 font-semibold">({{ $venue->reviews->count() }} reviews)</span>
                        @else
                        <span class="text-gray-500 font-bold text-lg">New</span>
                        <span class="text-xs text-gray-600 font-semibold">(0 reviews)</span>
                        @endif
                    </div>
                </div>

                @forelse($venue->reviews as $review)
                <div class="py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">
                                    {{ $review->user->name ?? 'Verified Guest' }}
                                </h4>
                                <span class="text-xs text-gray-400">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="text-amber-400 text-sm font-bold">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                    </div>

                    @if($review->comment)
                    <p class="text-gray-600 text-sm mt-2 pl-10">
                        "{{ $review->comment }}"
                    </p>
                    @endif
                </div>
                @empty
                <div class="text-center py-6 text-gray-500 text-sm italic">
                    No reviews for this venue yet. Book first and leave your feedback!
                </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Booking AJAX Script (only runs if a booking form exists on the page) --}}
    <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.booking-form').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const start = form.querySelector('.start-date').value;
                        const end = form.querySelector('.end-date').value;
                        const errorBox = form.querySelector('.error-box');
                        const successBox = form.querySelector('.success-box');
                        const btn = form.querySelector('.submit-btn');
                        const btnText = btn.querySelector('.btn-text');
                        const spinner = btn.querySelector('.spinner');

                        errorBox.classList.add('hidden');
                        successBox.classList.add('hidden');
                        errorBox.innerText = '';
                        successBox.innerText = '';

                        btn.disabled = true;
                        btnText.innerText = 'Checking...';
                        spinner.classList.remove('hidden');

                        const startTime = Date.now();

                        fetch('/venues/' + form.dataset.venueId + '/book', {
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
                            })
                            .then(function(response) {
                                if (response.status === 401) {
                                    window.location.href = '/login';
                                    return Promise.reject('Unauthenticated');
                                }
                                if (response.status === 403) {
                                    return response.json().then(function(data) {
                                        return {
                                            ok: false,
                                            data: data
                                        };
                                    });
                                }
                                return response.json().then(function(data) {
                                    return {
                                        ok: response.ok,
                                        data: data
                                    };
                                });
                            })
                            .then(function(result) {
                                const elapsed = Date.now() - startTime;
                                const minDelay = 500;

                                return new Promise(function(resolve) {
                                    setTimeout(function() {
                                        if (!result.ok) {
                                            errorBox.innerText = result.data.message || 'Booking failed.';
                                            errorBox.classList.remove('hidden');
                                        } else {
                                            successBox.innerText = result.data.message || 'Success!';
                                            successBox.classList.remove('hidden');
                                            setTimeout(function() {
                                                window.location.href = result.data.redirect || '/my-bookings';
                                            }, 1200);
                                        }
                                        resolve();
                                    }, Math.max(0, minDelay - elapsed));
                                });
                            })
                            .catch(function(err) {
                                if (err !== 'Unauthenticated') {
                                    errorBox.innerText = 'Network error. Please try again.';
                                    errorBox.classList.remove('hidden');
                                }
                            })
                            .finally(function() {
                                btn.disabled = false;
                                btnText.innerText = 'Request Booking';
                                spinner.classList.add('hidden');
                            });
                    });
                });
            });
        })();
    </script>

</x-app-layout>