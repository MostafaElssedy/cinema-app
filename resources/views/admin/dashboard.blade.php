@extends('layouts.admin')

@section('title', 'Admin Dashboard - Movie Management')
@section('header_title', 'Cinema Collection Dashboard')

@section('content')

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    
    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Movies</span>
            <span class="text-2xl font-black text-white">{{ $stats['total_movies'] }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-600/20 text-red-500 border border-red-500/30 flex items-center justify-center text-lg">
            <i class="fa-solid fa-film"></i>
        </div>
    </div>

    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Bookings</span>
            <span class="text-2xl font-black text-cyan-400">{{ $stats['total_bookings'] ?? 0 }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center text-lg">
            <i class="fa-solid fa-ticket"></i>
        </div>
    </div>

    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Cinema Revenue</span>
            <span class="text-2xl font-black text-emerald-400">{{ number_format($stats['total_revenue'] ?? 0, 0) }} <span class="text-xs text-slate-400 font-normal">EGP</span></span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-lg">
            <i class="fa-solid fa-coins"></i>
        </div>
    </div>

    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Average Rating</span>
            <span class="text-2xl font-black text-amber-400">{{ $stats['avg_rating'] }} <span class="text-xs text-slate-500 font-normal">/10</span></span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-lg">
            <i class="fa-solid fa-star"></i>
        </div>
    </div>

    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Latest Release</span>
            <span class="text-2xl font-black text-blue-400">{{ $stats['latest_year'] }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30 flex items-center justify-center text-lg">
            <i class="fa-regular fa-calendar"></i>
        </div>
    </div>

    <div class="admin-glass p-5 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Watchlist Saves</span>
            <span class="text-2xl font-black text-purple-400">{{ $stats['total_watchlist'] }}</span>
        </div>
        <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center justify-center text-lg">
            <i class="fa-solid fa-bookmark"></i>
        </div>
    </div>

</div>

<!-- Movies Management Table Card -->
<div class="admin-glass rounded-3xl overflow-hidden shadow-2xl">
    
    <!-- Table Header Toolbar -->
    <div class="p-6 border-b border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-list text-red-500"></i> Movie Inventory ({{ $movies->total() }})
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Manage movie collection, edit details, or remove titles.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Search Input -->
            <form action="{{ route('admin.dashboard') }}" method="GET" class="relative flex-1 md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or year..." 
                       class="w-full bg-slate-900 border border-slate-700/80 rounded-xl py-2 pl-9 pr-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-red-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
            </form>

            <a href="{{ route('admin.showtimes.create') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs border border-slate-700 whitespace-nowrap flex items-center gap-1.5 transition-colors">
                <i class="fa-regular fa-clock text-red-500"></i> Schedule Show
            </a>

            <a href="{{ route('admin.movies.create') }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/20 whitespace-nowrap flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-plus"></i> Add Movie
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="bg-slate-900/80 border-b border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Poster & Movie</th>
                    <th class="px-6 py-4">Release Year</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($movies as $movie)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $movie->image }}" alt="{{ $movie->title }}" class="w-12 h-16 object-cover rounded-lg shadow border border-slate-700/60" onerror="this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80'">
                                <div>
                                    <h4 class="font-bold text-white text-sm hover:text-red-400 transition-colors line-clamp-1">
                                        <a href="{{ route('movies.show', $movie->id) }}" target="_blank">{{ $movie->title }}</a>
                                    </h4>
                                    <span class="text-xs text-slate-500 font-mono">ID: #{{ $movie->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-200">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-800 text-xs border border-slate-700">
                                {{ $movie->release_year }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 font-bold text-xs flex items-center gap-1 w-fit">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($movie->rating, 1) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $movie->description }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.movies.edit', $movie->id) }}" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors" title="Edit Movie">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>

                                <button type="button" 
                                        onclick="openDeleteModal({{ $movie->id }}, '{{ addslashes($movie->title) }}')"
                                        class="p-2 rounded-lg bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/30 transition-colors" title="Delete Movie">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-film-slash text-2xl mb-2 block text-slate-600"></i>
                            <p class="font-semibold text-sm">No movies found in database.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer / Pagination -->
    <div class="p-6 border-t border-slate-800">
        {{ $movies->links() }}
    </div>
</div>

<!-- Recent Cinema Bookings Table Card -->
<div class="admin-glass rounded-3xl overflow-hidden shadow-2xl mt-8">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-ticket text-cyan-400"></i> Recent Cinema Reservations
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Live customer seat reservations, showtimes, and payments.</p>
        </div>
        <span class="px-3 py-1 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold">
            Live Bookings: {{ count($recentBookings ?? []) }}
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="bg-slate-900/80 border-b border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Ref Code</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Movie & Showtime</th>
                    <th class="px-6 py-4">Hall / Screen</th>
                    <th class="px-6 py-4">Reserved Seats</th>
                    <th class="px-6 py-4">Total Price</th>
                    <th class="px-6 py-4 text-right">E-Ticket</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($recentBookings ?? [] as $bk)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-amber-400 text-xs">
                            #{{ $bk->booking_reference }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-white text-xs block">{{ $bk->customer_name }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $bk->customer_phone }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-white text-xs block">{{ $bk->showtime->movie->title ?? 'N/A' }}</span>
                            <span class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                <i class="fa-regular fa-calendar text-[10px] text-red-400"></i>
                                {{ $bk->showtime->show_date ? $bk->showtime->show_date->format('M d') : 'Today' }} - {{ $bk->showtime->start_time }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-300">
                            {{ $bk->showtime->theater->name ?? 'Main Screen' }}
                            <span class="text-[10px] block text-slate-500 font-semibold">{{ $bk->showtime->theater->type ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($bk->bookedSeats as $st)
                                    <span class="px-2 py-0.5 rounded bg-red-600/20 text-red-300 border border-red-500/30 font-mono font-bold text-[10px]">
                                        {{ $st->seat_number }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-black text-emerald-400">
                            {{ number_format($bk->total_price, 0) }} EGP
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('booking.ticket', $bk->booking_reference) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-semibold border border-slate-700 transition-colors"
                               title="View & Print E-Ticket">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            <i class="fa-solid fa-ticket text-2xl mb-2 block text-slate-600"></i>
                            <p class="font-semibold text-sm">No reservations made yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Custom Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4 transition-all">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 text-center transform scale-95 transition-transform duration-200" id="deleteModalCard">
        
        <!-- Danger Warning Icon -->
        <div class="w-16 h-16 rounded-full bg-red-600/20 text-red-500 border border-red-500/30 flex items-center justify-center text-2xl mx-auto">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div class="space-y-2">
            <h3 class="text-xl font-black text-white">Delete Movie Confirmation</h3>
            <p class="text-xs text-slate-400 leading-relaxed">
                Are you sure you want to delete <span id="deleteMovieTitle" class="font-bold text-red-400"></span>? This action cannot be undone.
            </p>
        </div>

        <!-- Action Form Buttons -->
        <form id="deleteForm" method="POST" action="" class="flex items-center justify-center gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition-colors">
                Cancel
            </button>
            <button type="submit" class="flex-1 px-5 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/30 transition-all">
                Yes, Delete Movie
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(movieId, movieTitle) {
        const modal = document.getElementById('deleteModal');
        const modalForm = document.getElementById('deleteForm');
        const titleSpan = document.getElementById('deleteMovieTitle');

        modalForm.action = `/admin/movies/${movieId}`;
        titleSpan.textContent = `"${movieTitle}"`;

        modal.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('deleteModalCard').classList.remove('scale-95');
            document.getElementById('deleteModalCard').classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const card = document.getElementById('deleteModalCard');

        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }
</script>

@endsection
