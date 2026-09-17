<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Venue: {{ $venue->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <form action="{{ route('venues.update', $venue->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Venue Name</label>
                        <input type="text" name="name" value="{{ old('name', $venue->name) }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $venue->location) }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Price Per Day (₹)</label>
                            <input type="number" step="0.01" name="price_per_day" value="{{ old('price_per_day', $venue->price_per_day) }}" class="w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Capacity (Guests)</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $venue->capacity) }}" class="w-full border rounded p-2" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full border rounded p-2">{{ old('description', $venue->description) }}</textarea>
                    </div>

                    <button type="submit" style="background-color: #2563eb; color: white; padding: 10px 20px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                        Update Venue
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>