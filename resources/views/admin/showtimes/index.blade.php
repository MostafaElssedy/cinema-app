@extends('layouts.admin')

@section('title', 'Manage Showtimes - Admin Portal')
@section('header_title', 'Showtimes & Scheduling Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Showtimes Schedule</h2>
            <p class="text-xs text-slate-400 mt-1">Manage cinema projection schedules, hall assignments, and ticket rates.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.showtimes.create') }}" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-semibold text-xs shadow-lg shadow-red-600/20 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i> Schedule New Showtime
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Scheduled Shows</span>
                <span class="text-2xl font-black text-white">{{ $stats['total_showtimes'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-600/20 text-red-500 border border-red-500/30 flex items-center justify-center text-lg">
                <i class="fa-regular fa-clock"></i>
            </div>
        </div>

        <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Upcoming Shows (From Today)</span>
                <span class="text-2xl font-black text-cyan-400">{{ $stats['upcoming_showtimes'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center text-lg">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>

        <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Auditorium Halls</span>
                <span class="text-2xl font-black text-amber-400">{{ $stats['total_theaters'] }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-lg">
                <i class="fa-solid fa-couch"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="admin-glass p-5 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.showtimes.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <!-- Filter by Movie -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Filter by Movie</label>
                <select name="movie_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-red-500">
                    <option value="">All Movies</option>
                    @foreach($movies as $mov)
                        <option value="{{ $mov->id }}" {{ request('movie_id') == $mov->id ? 'selected' : '' }}>
                            {{ $mov->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Theater -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Filter by Theater</label>
                <select name="theater_id" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-red-500">
                    <option value="">All Theaters</option>
                    @foreach($theaters as $th)
                        <option value="{{ $th->id }}" {{ request('theater_id') == $th->id ? 'selected' : '' }}>
                            {{ $th->name }} ({{ $th->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Date -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Show Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-red-500">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition-colors flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter text-slate-400"></i> Filter
                </button>
                @if(request()->hasAny(['movie_id', 'theater_id', 'date']))
                    <a href="{{ route('admin.showtimes.index') }}" class="py-2 px-3 rounded-xl bg-red-950/40 border border-red-500/30 text-red-300 hover:text-white font-semibold text-xs transition-colors" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Showtimes Table -->
    <div class="admin-glass rounded-3xl overflow-hidden border border-slate-800 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/90 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="py-4 px-6">Movie</th>
                        <th class="py-4 px-6">Theater Hall</th>
                        <th class="py-4 px-6">Date & Time</th>
                        <th class="py-4 px-6">Ticket Price</th>
                        <th class="py-4 px-6">Booked Occupancy</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($showtimes as $showtime)
                        @php
                            $capacity = ($showtime->theater->rows_count ?? 6) * ($showtime->theater->seats_per_row ?? 10);
                            $bookedCount = $showtime->bookedSeats->count();
                            $occupancyPercent = $capacity > 0 ? round(($bookedCount / $capacity) * 100) : 0;
                            $badgeColors = match($showtime->theater->type ?? '') {
                                'IMAX 3D' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                'Dolby Atmos' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                'VIP Lounge', 'VIP' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                default => 'bg-slate-700/40 text-slate-300 border-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <!-- Movie Title & Poster -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $showtime->movie->image }}" alt="{{ $showtime->movie->title }}"
                                         class="w-10 h-14 object-cover rounded-lg border border-slate-700 shadow-md">
                                    <div>
                                        <a href="{{ route('movies.show', $showtime->movie->id) }}" target="_blank" class="font-bold text-white hover:text-red-400 transition-colors block">
                                            {{ $showtime->movie->title }}
                                        </a>
                                        <span class="text-xs text-slate-400">{{ $showtime->movie->release_year }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Theater Hall -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-200">{{ $showtime->theater->name ?? 'N/A' }}</div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $badgeColors }}">
                                    {{ $showtime->theater->type ?? 'Standard' }}
                                </span>
                            </td>

                            <!-- Date & Time -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-white flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock text-red-500 text-xs"></i> {{ $showtime->start_time }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    {{ $showtime->show_date->format('D, M d, Y') }}
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6">
                                <span class="font-extrabold text-emerald-400 text-sm">
                                    {{ number_format($showtime->price, 0) }} EGP
                                </span>
                            </td>

                            <!-- Booked Occupancy -->
                            <td class="py-4 px-6">
                                <div class="w-36">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-bold text-white">{{ $bookedCount }} / {{ $capacity }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $occupancyPercent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-red-600 to-amber-500 h-1.5 rounded-full" style="width: {{ min($occupancyPercent, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('booking.seats', $showtime->id) }}" target="_blank" title="View Seat Map"
                                       class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-400 hover:text-cyan-300 text-xs transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" title="Edit Showtime"
                                       class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this showtime on {{ $showtime->show_date->format('M d') }} at {{ $showtime->start_time }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete Showtime"
                                                class="p-2 rounded-lg bg-red-950/40 hover:bg-red-900/60 text-red-400 border border-red-500/30 text-xs transition-colors">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fa-regular fa-calendar-xmark text-4xl text-slate-600 mb-3 block"></i>
                                <p class="font-bold text-base text-white">No Showtimes Found</p>
                                <p class="text-xs text-slate-500 mt-1">Try changing filter criteria or schedule a new showtime.</p>
                                <a href="{{ route('admin.showtimes.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 text-white font-semibold text-xs shadow-lg">
                                    <i class="fa-solid fa-plus"></i> Schedule New Showtime
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($showtimes->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $showtimes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
