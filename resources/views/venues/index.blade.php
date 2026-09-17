<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Venues') }}
            </h2>

            <a href="{{ route('venues.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Add New Venue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            {{-- Venues Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                            <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Capacity</th>
                            <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Price/Day (₹)</th>
                            <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($venues as $venue)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Name + Rating --}}
                            <td class="p-4">
                                <div class="flex justify-between items-center gap-3">
                                    <a href="{{ route('venues.show', $venue) }}"
                                        class="text-lg font-bold text-gray-800 hover:text-blue-600 truncate">
                                        {{ $venue->name }}
                                    </a>

                                    @php $rating = $venue->averageRating(); @endphp

                                    @if (is_numeric($rating))
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap">
                                        ★ {{ number_format($rating, 1) }}
                                    </span>
                                    @else
                                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                        New
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $venue->location }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ number_format($venue->capacity) }} guests
                            </td>

                            <td class="p-4 font-semibold text-blue-600">
                                ₹{{ number_format($venue->price_per_day, 2) }}
                            </td>

                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('venues.edit', $venue) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('venues.destroy', $venue) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this venue?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                No venues found.
                                @if (request()->hasAny(['search', 'city', 'max_price']))
                                <br>
                                <a href="{{ route('venues.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                    Clear filters
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($venues instanceof \Illuminate\Pagination\LengthAwarePaginator && $venues->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $venues->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
    </div>
</x-app-layout>