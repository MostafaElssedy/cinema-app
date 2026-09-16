@extends('layouts.app')

@section('title', 'CineMax - Discover Trending Movies & Cinema Collection')

@section('content')

<!-- Hero Section -->
@if(isset($featuredMovie))
<section class="relative min-h-[500px] flex items-end overflow-hidden mb-12 border-b border-slate-800">
    <!-- Hero Backdrop Image -->
    <div class="absolute inset-0 bg-cover bg-center transition-all duration-700 filter brightness-75 scale-105" 
         style="background-image: url('{{ $featuredMovie->image }}');"></div>
    <div class="absolute inset-0 hero-gradient"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 w-full z-10">
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-red-600 text-white shadow-lg shadow-red-600/30">Featured Movie</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                    <i class="fa-solid fa-star mr-1"></i> {{ number_format($featuredMovie->rating, 1) }}
                </span>
                <span class="text-xs font-semibold text-slate-300">{{ $featuredMovie->release_year }}</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-none mb-4 drop-shadow-md">
                {{ $featuredMovie->title }}
            </h1>

            <p class="text-slate-300 text-base sm:text-lg line-clamp-3 mb-8 font-normal leading-relaxed">
                {{ $featuredMovie->description }}
            </p>

            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('movies.show', $featuredMovie->id) }}#showtimes-section" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-red-600 via-rose-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-extrabold text-sm shadow-xl glow-red flex items-center gap-2 transition-all hover:scale-105">
                    <i class="fa-solid fa-ticket text-base"></i> Book Tickets
                </a>
                <button type="button" 
                        onclick="openTrailerModal('{{ $featuredMovie->trailer_embed_url }}', '{{ addslashes($featuredMovie->title) }}')"
                        class="px-6 py-3.5 rounded-2xl bg-slate-900/90 hover:bg-red-600 border border-slate-700 hover:border-red-500 text-white font-extrabold text-sm shadow-xl flex items-center gap-2 transition-all hover:scale-105 group">
                    <i class="fa-brands fa-youtube text-red-500 group-hover:text-white text-base transition-colors"></i> Watch Trailer
                </button>
                <a href="{{ route('movies.show', $featuredMovie->id) }}" class="px-6 py-3.5 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 text-white font-bold text-sm flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-circle-info text-base"></i> Details
                </a>
                <button onclick="toggleWatchlist({{ $featuredMovie->id }}, this)" class="px-5 py-3.5 rounded-2xl bg-slate-900/60 hover:bg-slate-800 border border-slate-700/80 text-white font-bold text-sm backdrop-blur-md flex items-center gap-2 transition-all">
                    <i class="fa-regular fa-bookmark"></i> Watchlist
                </button>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Movies Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Section Header & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-film text-red-500"></i> Explore Collection
            </h2>
            <p class="text-slate-400 text-sm mt-1">Browse all available movies, filter by release year, rating, or search title.</p>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('home') }}" method="GET" class="flex flex-wrap items-center gap-3">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <!-- Year Dropdown -->
            <select name="year" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-500">
                <option value="">All Release Years</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>

            <!-- Rating Dropdown -->
            <select name="min_rating" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-500">
                <option value="">All Ratings</option>
                <option value="9.0" {{ request('min_rating') == '9.0' ? 'selected' : '' }}>9.0+ ⭐ Superb</option>
                <option value="8.5" {{ request('min_rating') == '8.5' ? 'selected' : '' }}>8.5+ ⭐ Excellent</option>
                <option value="8.0" {{ request('min_rating') == '8.0' ? 'selected' : '' }}>8.0+ ⭐ Very Good</option>
            </select>

            <!-- Sort Dropdown -->
            <select name="sort" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-500">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort: Latest Added</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort: Highest Rated</option>
                <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Sort: Release Year</option>
            </select>

            @if(request()->hasAny(['search', 'year', 'min_rating', 'sort']))
                <a href="{{ route('home') }}" class="p-2.5 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-sm" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Movies Grid -->
    @if($movies->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($movies as $movie)
                <div class="group glass-card rounded-2xl overflow-hidden flex flex-col transition-all duration-300 hover:-translate-y-1">
                    
                    <!-- Poster Image Container -->
                    <div class="relative aspect-[2/3] overflow-hidden bg-slate-900">
                        <img src="{{ $movie->image }}" alt="{{ $movie->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy"
                             onerror="this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80'">
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity"></div>

                        <!-- Center Play Button on Hover -->
                        <button type="button" 
                                onclick="openTrailerModal('{{ $movie->trailer_embed_url }}', '{{ addslashes($movie->title) }}')"
                                class="absolute inset-0 m-auto w-12 h-12 rounded-full bg-red-600/90 hover:bg-red-500 text-white flex items-center justify-center shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 z-20 border-2 border-white/30 glow-red"
                                title="Watch Trailer">
                            <i class="fa-solid fa-play text-sm ml-0.5"></i>
                        </button>

                        <!-- Top Badges -->
                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-950/80 backdrop-blur-md text-slate-200 text-xs font-bold border border-slate-700/50">
                                {{ $movie->release_year }}
                            </span>
                            
                            <button onclick="toggleWatchlist({{ $movie->id }}, this)" 
                                    class="w-9 h-9 rounded-xl bg-slate-950/80 hover:bg-red-600 backdrop-blur-md text-slate-200 hover:text-white flex items-center justify-center border border-slate-700/50 transition-all shadow-md"
                                    title="Add to Watchlist">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </div>

                        <!-- Rating Badge (Bottom Left of Poster) -->
                        <div class="absolute bottom-3 left-3 z-10">
                            <span class="px-2.5 py-1 rounded-xl bg-amber-500/90 text-slate-950 font-black text-xs flex items-center gap-1 shadow-lg">
                                <i class="fa-solid fa-star text-xs"></i> {{ number_format($movie->rating, 1) }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-white group-hover:text-red-500 transition-colors line-clamp-1 mb-2">
                                <a href="{{ route('movies.show', $movie->id) }}">{{ $movie->title }}</a>
                            </h3>
                            <p class="text-slate-400 text-xs line-clamp-2 leading-relaxed mb-4">
                                {{ $movie->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                            <a href="{{ route('movies.show', $movie->id) }}#showtimes-section" class="text-xs font-bold text-amber-400 hover:text-amber-300 flex items-center gap-1.5 transition-colors">
                                <i class="fa-solid fa-ticket text-[10px]"></i> Book
                            </a>

                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        onclick="openTrailerModal('{{ $movie->trailer_embed_url }}', '{{ addslashes($movie->title) }}')" 
                                        class="text-xs font-semibold text-red-400 hover:text-red-300 flex items-center gap-1 transition-colors" 
                                        title="Watch Trailer">
                                    <i class="fa-brands fa-youtube text-xs"></i> Trailer
                                </button>
                                <a href="{{ route('movies.show', $movie->id) }}" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                                    Details
                                </a>
                                <button onclick="openQuickView({{ json_encode($movie) }})" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors" title="Quick View">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $movies->links() }}
        </div>
    @else
        <div class="py-20 text-center glass-card rounded-3xl p-12">
            <div class="w-16 h-16 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-2xl mb-4">
                <i class="fa-solid fa-film-slash"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Movies Found</h3>
            <p class="text-slate-400 text-sm max-w-md mx-auto mb-6">We couldn't find any movies matching your current filters or search criteria.</p>
            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-500 transition-colors">
                Clear Filters
            </a>
        </div>
    @endif

</div>

<!-- Quick View Modal -->
<div id="quick-view-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-2xl glass-card rounded-3xl overflow-hidden shadow-2xl border border-slate-700/60 flex flex-col md:flex-row">
        <button onclick="closeQuickView()" class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-slate-900/80 text-slate-300 hover:text-white flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="w-full md:w-1/2 aspect-[2/3] bg-slate-900 relative">
            <img id="qv-image" src="" alt="" class="w-full h-full object-cover">
        </div>

        <div class="w-full md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span id="qv-year" class="px-2.5 py-0.5 rounded-lg bg-slate-800 text-xs font-bold text-slate-300"></span>
                    <span id="qv-rating" class="px-2.5 py-0.5 rounded-lg bg-amber-500/20 text-amber-400 text-xs font-extrabold flex items-center gap-1"></span>
                </div>
                <h3 id="qv-title" class="text-2xl font-black text-white mb-3"></h3>
                <p id="qv-desc" class="text-slate-300 text-sm leading-relaxed mb-6"></p>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-800">
                <button type="button" id="qv-trailer-btn" class="w-full py-3 rounded-xl bg-slate-800 hover:bg-red-600 text-white font-bold text-center text-sm flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-brands fa-youtube text-red-500"></i> Watch Trailer
                </button>
                <a id="qv-details-btn" href="" class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-center text-sm flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-film"></i> Open Movie Page
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openQuickView(movie) {
        document.getElementById('qv-image').src = movie.image;
        document.getElementById('qv-title').textContent = movie.title;
        document.getElementById('qv-desc').textContent = movie.description;
        document.getElementById('qv-year').textContent = movie.release_year;
        document.getElementById('qv-rating').innerHTML = `<i class="fa-solid fa-star"></i> ${movie.rating}`;
        document.getElementById('qv-details-btn').href = `/movie/${movie.id}`;
        
        const trailerBtn = document.getElementById('qv-trailer-btn');
        trailerBtn.onclick = () => {
            closeQuickView();
            openTrailerModal(movie.trailer_embed_url, movie.title);
        };

        const modal = document.getElementById('quick-view-modal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeQuickView() {
        const modal = document.getElementById('quick-view-modal');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endpush
