@extends('layouts.app')

@section('title', $movie->title . ' (' . $movie->release_year . ') - CineMax')

@section('content')
<div class="relative min-h-[600px] border-b border-slate-800">
    <!-- Backdrop Background -->
    <div class="absolute inset-0 bg-cover bg-center filter blur-md opacity-30 scale-110" style="background-image: url('{{ $movie->image }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-slate-950/60"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 z-10">
        <!-- Back Link -->
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900/80 border border-slate-700/80 text-slate-300 hover:text-white text-sm font-semibold mb-8 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Movies
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Poster Image -->
            <div class="lg:col-span-4">
                <div class="glass-card p-3 rounded-3xl shadow-2xl overflow-hidden relative group">
                    <img src="{{ $movie->image }}" alt="{{ $movie->title }}" class="w-full aspect-[2/3] object-cover rounded-2xl shadow-lg group-hover:scale-105 transition-transform duration-500">
                    
                    <!-- Play Trailer Button Overlay -->
                    <button type="button" 
                            onclick="openTrailerModal('{{ $movie->trailer_embed_url }}', '{{ addslashes($movie->title) }}')"
                            class="absolute inset-0 m-auto w-16 h-16 rounded-full bg-red-600/90 hover:bg-red-500 text-white flex items-center justify-center shadow-2xl hover:scale-110 transition-all border-2 border-white/40 glow-red group/play z-10"
                            title="Watch Official Trailer">
                        <i class="fa-solid fa-play text-xl ml-1 text-white group-hover/play:scale-110 transition-transform"></i>
                    </button>
                    
                    <!-- Subtle badge on poster -->
                    <div class="absolute bottom-6 left-6 right-6 flex justify-center z-10 pointer-events-none">
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-950/80 backdrop-blur-md text-xs font-bold text-slate-200 border border-slate-700/60 shadow-lg flex items-center gap-1.5">
                            <i class="fa-brands fa-youtube text-red-500"></i> Watch Trailer
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Movie Details -->
            <div class="lg:col-span-8 space-y-6">
                
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3.5 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center gap-1.5 shadow-lg">
                        <i class="fa-solid fa-star"></i> {{ number_format($movie->rating, 1) }} / 10
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 font-bold text-sm">
                        <i class="fa-regular fa-calendar mr-1"></i> Release Year: {{ $movie->release_year }}
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-red-600/20 text-red-400 border border-red-500/30 font-semibold text-sm">
                        HD 4K Cinema
                    </span>
                </div>

                <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight">
                    {{ $movie->title }}
                </h1>

                <div class="glass-card p-6 rounded-2xl space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Synopsis / Overview</h3>
                    <p class="text-slate-200 text-base sm:text-lg leading-relaxed">
                        {{ $movie->description }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    @if(isset($movie->showtimes) && $movie->showtimes->count() > 0)
                        <a href="#showtimes-section" 
                           class="px-8 py-4 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-extrabold text-base shadow-xl glow-red flex items-center gap-3 transition-all hover:scale-105">
                            <i class="fa-solid fa-ticket text-xl"></i> Book Tickets Now
                        </a>
                    @endif

                    <button type="button" 
                            onclick="openTrailerModal('{{ $movie->trailer_embed_url }}', '{{ addslashes($movie->title) }}')" 
                            class="px-8 py-4 rounded-2xl bg-slate-900/90 hover:bg-red-600 border border-slate-700 hover:border-red-500 text-white font-extrabold text-base shadow-xl flex items-center gap-3 transition-all hover:scale-105 group">
                        <i class="fa-brands fa-youtube text-red-500 group-hover:text-white text-xl transition-colors"></i> Watch Trailer
                    </button>

                    <button onclick="toggleWatchlist({{ $movie->id }}, this)" 
                            class="px-8 py-4 rounded-2xl bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-white font-bold text-base shadow-xl flex items-center gap-3 transition-all">
                        <i class="fa-regular fa-bookmark text-lg"></i> Add to Watchlist
                    </button>
                </div>

                <!-- Technical Specs Widget -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-6 border-t border-slate-800/80">
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                        <span class="text-xs text-slate-400 block font-medium">Database ID</span>
                        <span class="text-base font-bold text-white">#{{ $movie->id }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                        <span class="text-xs text-slate-400 block font-medium">User Rating</span>
                        <span class="text-base font-bold text-amber-400"><i class="fa-solid fa-star text-xs"></i> {{ number_format($movie->rating, 1) }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800">
                        <span class="text-xs text-slate-400 block font-medium">Showtimes</span>
                        <span class="text-base font-bold text-emerald-400"><i class="fa-solid fa-film text-xs"></i> {{ $movie->showtimes ? $movie->showtimes->count() : 0 }} Available</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Cinema Showtimes & Interactive Booking Section -->
<div id="showtimes-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
    <div class="glass-card rounded-3xl p-6 sm:p-10 border border-slate-800 relative overflow-hidden">
        
        <!-- Subtle Glow Background -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-red-600/10 rounded-full filter blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-amber-500/10 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-slate-800">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-red-500 flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-film"></i> CineMax Live Schedule
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Select Showtime & Reserve Your Seat
                </h2>
                <p class="text-slate-400 text-sm mt-1">
                    Choose your preferred screening time and theater hall to select your seats on the interactive map.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300 font-semibold flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Instant Digital Ticket
                </span>
            </div>
        </div>

        @if(isset($showtimesByDate) && $showtimesByDate->count() > 0)
            <div class="space-y-8 mt-8">
                @foreach($showtimesByDate as $date => $shows)
                    <div>
                        <!-- Date Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-700/80 text-amber-400 text-sm font-bold flex items-center gap-2 shadow-sm">
                                <i class="fa-regular fa-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($date)->isToday() ? 'Today, ' : (\Carbon\Carbon::parse($date)->isTomorrow() ? 'Tomorrow, ' : '') }}
                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                            </span>
                            <div class="h-px flex-1 bg-slate-800/80"></div>
                        </div>

                        <!-- Showtimes Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($shows as $showtime)
                                <div class="glass-card rounded-2xl p-5 border border-slate-800/80 hover:border-red-500/50 transition-all flex flex-col justify-between group">
                                    <div class="space-y-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <h4 class="text-xl font-black text-white group-hover:text-amber-400 transition-colors flex items-center gap-2">
                                                    <i class="fa-regular fa-clock text-red-500 text-base"></i>
                                                    {{ $showtime->start_time }}
                                                </h4>
                                                <p class="text-xs text-slate-400 mt-1 font-medium">
                                                    {{ $showtime->theater->name ?? 'Main Screen' }}
                                                </p>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ ($showtime->theater->type ?? '') == 'IMAX 3D' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30' : (($showtime->theater->type ?? '') == 'VIP Lounge' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-red-600/20 text-red-400 border border-red-500/30') }}">
                                                {{ $showtime->theater->type ?? 'Standard' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between text-xs text-slate-400 pt-2 border-t border-slate-800/60">
                                            <span>Ticket Price:</span>
                                            <span class="text-sm font-black text-white">{{ number_format($showtime->price, 0) }} EGP</span>
                                        </div>
                                    </div>

                                    <div class="pt-4 mt-2">
                                        <a href="{{ route('booking.seats', $showtime->id) }}" 
                                           class="w-full py-3 px-4 rounded-xl bg-red-600/90 hover:bg-red-600 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg glow-red transition-all group-hover:translate-x-0.5">
                                            <i class="fa-solid fa-chair"></i> Select Seats
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Showtimes State -->
            <div class="py-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-900 border border-slate-800 text-slate-500 flex items-center justify-center mx-auto text-2xl mb-4">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="text-lg font-bold text-white">No Showtimes Scheduled Yet</h3>
                <p class="text-sm text-slate-400 max-w-md mx-auto mt-1">
                    Check back soon or ask our AI chatbot to find out when this movie will be screened in theaters!
                </p>
            </div>
        @endif

    </div>
</div>

<!-- Related Movies Section -->
@if(isset($relatedMovies) && $relatedMovies->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-2xl font-extrabold text-white tracking-tight mb-8 flex items-center gap-3">
        <i class="fa-solid fa-clapperboard text-red-500"></i> You Might Also Like
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($relatedMovies as $rel)
            <a href="{{ route('movies.show', $rel->id) }}" class="group glass-card rounded-2xl overflow-hidden flex flex-col transition-all hover:-translate-y-1">
                <div class="aspect-[2/3] overflow-hidden bg-slate-900 relative">
                    <img src="{{ $rel->image }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-3 left-3">
                        <span class="px-2 py-1 rounded-lg bg-amber-500 text-slate-950 font-bold text-xs">
                            <i class="fa-solid fa-star"></i> {{ number_format($rel->rating, 1) }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-white group-hover:text-red-500 transition-colors line-clamp-1">{{ $rel->title }}</h4>
                    <span class="text-xs text-slate-400">{{ $rel->release_year }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection
