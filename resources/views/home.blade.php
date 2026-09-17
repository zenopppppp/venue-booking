<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VenueBook — Find Your Perfect Venue</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">VenueBook</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('venues.catalog') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Browse Venues</a>
                    @auth
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">My Bookings</a>
                    @endauth
                </div>

                <div class="flex items-center gap-3">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="hidden sm:block text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="bg-gradient-to-b from-indigo-50 via-white to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24">
            <div class="max-w-3xl mx-auto text-center">
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider mb-6">
                    🎉 Trusted by 500+ event planners
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Find Your Perfect <span class="text-indigo-600">Venue</span> for Any Event
                </h1>
                <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto">
                    Browse hundreds of venues, compare prices, and book in seconds. No paperwork. No waiting.
                </p>

                <form action="{{ route('venues.catalog') }}" method="GET" class="max-w-2xl mx-auto flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-2xl shadow-lg border border-gray-100">
                    <div class="flex-1 flex items-center gap-2 px-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="city" placeholder="Search by city or venue name..."
                            class="w-full border-0 focus:ring-0 focus:outline-none text-sm py-3 bg-transparent">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">Search</button>
                </form>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('venues.catalog') }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md">Browse All Venues</a>
                    @guest
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-50 transition border border-gray-200">Create Free Account</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="py-20 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3">How It Works</h2>
                <p class="text-gray-600">Book your next event in 3 simple steps</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">1. Browse</h3>
                    <p class="text-sm text-gray-600">Search venues by location, price, or capacity to find the perfect match.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">2. Book</h3>
                    <p class="text-sm text-gray-600">Pick your dates, send a request, and get instant confirmation from the vendor.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">3. Enjoy</h3>
                    <p class="text-sm text-gray-600">Show up, host your event, and leave a review for other customers.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-indigo-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Ready to book your next event?</h2>
            <p class="text-indigo-100 mb-8 text-lg">Join hundreds of customers finding the perfect venue for their events.</p>
            @guest
            <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg">Get Started Free</a>
            @else
            <a href="{{ route('venues.catalog') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition shadow-lg">Browse Venues</a>
            @endguest
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-400 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="font-bold text-white">VenueBook</span>
            </div>
            <p class="text-sm">© {{ date('Y') }} VenueBook. Built with Laravel.</p>
        </div>
    </footer>

</body>

</html>