@extends('layouts.app')

@section('title', 'My Watchlist - CineMax')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-800">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-bookmark text-red-500"></i> My Personal Watchlist
            </h1>
            <p class="text-slate-400 text-sm mt-1">Saved movies for your device ID: <code id="device-id-display" class="bg-slate-900 px-2 py-0.5 rounded text-amber-400 font-mono text-xs">...</code></p>
        </div>

        <button onclick="loadWatchlist()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-rotate"></i> Refresh
        </button>
    </div>

    <!-- Watchlist Container -->
    <div id="watchlist-loading" class="py-20 text-center">
        <i class="fa-solid fa-spinner fa-spin text-3xl text-red-500 mb-4"></i>
        <p class="text-slate-400 text-sm">Loading your saved movies...</p>
    </div>

    <div id="watchlist-empty" class="py-20 text-center glass-card rounded-3xl p-12 hidden">
        <div class="w-16 h-16 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-4">
            <i class="fa-regular fa-bookmark"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Your Watchlist is Empty</h3>
        <p class="text-slate-400 text-sm max-w-md mx-auto mb-6">Explore our movie catalog and click the bookmark button on any movie to save it here for later.</p>
        <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm shadow-lg glow-red inline-flex items-center gap-2 transition-colors">
            <i class="fa-solid fa-compass"></i> Discover Movies
        </a>
    </div>

    <div id="watchlist-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 hidden">
        <!-- Rendered dynamically via JavaScript -->
    </div>

</div>
@endsection

@push('scripts')
<script>
    async function loadWatchlist() {
        const deviceId = getDeviceId();
        document.getElementById('device-id-display').textContent = deviceId;

        const loading = document.getElementById('watchlist-loading');
        const empty = document.getElementById('watchlist-empty');
        const grid = document.getElementById('watchlist-grid');

        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        grid.classList.add('hidden');

        try {
            const response = await fetch(`/api/watchlist?device_id=${deviceId}`);
            const result = await response.json();

            loading.classList.add('hidden');

            if ((result.success || result.status === 'success') && result.data && result.data.length > 0) {
                grid.innerHTML = '';
                result.data.forEach(item => {
                    const movie = item.movie;
                    if (!movie) return;

                    const card = document.createElement('div');
                    card.className = 'group glass-card rounded-2xl overflow-hidden flex flex-col transition-all hover:-translate-y-1';
                    card.innerHTML = `
                        <div class="relative aspect-[2/3] overflow-hidden bg-slate-900">
                            <img src="${movie.image}" alt="${movie.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80'">
                            
                            <!-- Trailer Play Button Overlay -->
                            <button type="button" 
                                    onclick="openTrailerModal('${movie.trailer_embed_url || ''}', '${escapeHtml(movie.title)}')"
                                    class="absolute inset-0 m-auto w-12 h-12 rounded-full bg-red-600/90 hover:bg-red-500 text-white flex items-center justify-center shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 z-20 border-2 border-white/30 glow-red"
                                    title="Watch Trailer">
                                <i class="fa-solid fa-play text-sm ml-0.5"></i>
                            </button>

                            <div class="absolute top-3 right-3 z-20">
                                <button onclick="removeWatchlistItem(${movie.id})" class="w-9 h-9 rounded-xl bg-red-600/90 text-white flex items-center justify-center hover:bg-red-500 shadow-md transition-colors" title="Remove from Watchlist">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-3 left-3 z-10">
                                <span class="px-2.5 py-1 rounded-xl bg-amber-500 text-slate-950 font-black text-xs shadow-lg">
                                    <i class="fa-solid fa-star"></i> ${movie.rating}
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg text-white group-hover:text-red-500 transition-colors line-clamp-1 mb-1">
                                    <a href="/movie/${movie.id}">${movie.title}</a>
                                </h3>
                                <span class="text-xs font-semibold text-slate-400 block mb-3">${movie.release_year}</span>
                                <p class="text-slate-400 text-xs line-clamp-2">${movie.description}</p>
                            </div>
                            <div class="pt-4 mt-4 border-t border-slate-800 flex items-center gap-2">
                                <button type="button" 
                                        onclick="openTrailerModal('${movie.trailer_embed_url || ''}', '${escapeHtml(movie.title)}')"
                                        class="flex-1 py-2.5 rounded-xl bg-red-600/20 hover:bg-red-600 border border-red-500/40 text-red-300 hover:text-white text-xs font-bold flex items-center justify-center gap-1.5 transition-colors">
                                    <i class="fa-brands fa-youtube"></i> Trailer
                                </button>
                                <a href="/movie/${movie.id}" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold flex items-center justify-center gap-1 transition-colors">
                                    View <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    `;
                    grid.appendChild(card);
                });
                grid.classList.remove('hidden');
            } else {
                empty.classList.remove('hidden');
            }
        } catch (e) {
            console.error('Error loading watchlist', e);
            loading.classList.add('hidden');
            empty.classList.remove('hidden');
        }
    }

    async function removeWatchlistItem(movieId) {
        const deviceId = getDeviceId();
        try {
            const res = await fetch('/api/watchlist', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ device_id: deviceId, movie_id: movieId })
            });
            const data = await res.json();
            if (data.success || data.status === 'success') {
                showToast('Removed from your watchlist');
                loadWatchlist();
                refreshWatchlistBadge();
            }
        } catch (e) {
            console.error(e);
        }
    }

    window.onWatchlistUpdated = loadWatchlist;

    document.addEventListener('DOMContentLoaded', loadWatchlist);
</script>
@endpush
