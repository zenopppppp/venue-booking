<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Received Booking Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">

                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3">Customer</th>
                                <th class="p-3">Venue</th>
                                <th class="p-3">Dates</th>
                                <th class="p-3">Total</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr class="border-b">
                                <td class="p-3 font-semibold">{{ $booking->user->name }}</td>
                                <td class="p-3">{{ $booking->venue->name }}</td>
                                <td class="p-3 text-sm">{{ $booking->start_date }} to {{ $booking->end_date }}</td>
                                <td class="p-3 font-bold text-blue-600">₹{{ number_format($booking->total_price, 2) }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded font-bold uppercase
                                        {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : ($booking->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="p-3 flex items-center space-x-2">
                                    @if($booking->status === 'pending')
                                    <!-- Approve Form -->
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <!-- Approve Button -->
                                        <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-3 rounded text-xs transition duration-150 ease-in-out cursor-pointer border-0">
                                                Approve
                                            </button>
                                        </form>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold border-0 cursor-pointer">
                                            Reject
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-gray-400">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">No requests received yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>