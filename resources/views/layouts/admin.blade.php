<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - CineMax')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0b0f19; color: #f1f5f9; }
        .admin-glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex bg-slate-950 font-sans antialiased">

    <!-- Sidebar -->
    <aside class="w-64 h-screen sticky top-0 bg-slate-900/90 border-r border-slate-800 flex flex-col justify-between hidden md:flex">
        <div>
            <!-- Brand -->
            <div class="h-20 px-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center text-white font-bold shadow-lg">
                    <i class="fa-solid fa-film text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-lg text-white tracking-tight leading-none">CineMax</h1>
                    <span class="text-xs font-semibold text-red-500 uppercase tracking-widest">Admin Portal</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    <i class="fa-solid fa-gauge-high text-lg"></i>
                    <span>Dashboard & Movies</span>
                </a>

                <a href="{{ route('admin.showtimes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.showtimes.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    <i class="fa-regular fa-clock text-lg"></i>
                    <span>Manage Showtimes</span>
                </a>

                <a href="{{ route('admin.theaters.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.theaters.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    <i class="fa-solid fa-couch text-lg"></i>
                    <span>Cinema Theaters</span>
                </a>

                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.profile.edit') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }} transition-all">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span>Admin Profile</span>
                </a>

                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all mt-4 border-t border-slate-800/80 pt-4">
                    <i class="fa-solid fa-globe text-lg text-emerald-400"></i>
                    <span>View Public Website</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-auto text-slate-500"></i>
                </a>

            </nav>
        </div>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/50 border border-slate-700/50">
                <div class="flex items-center gap-3 overflow-hidden">
                    @if(auth()->user() && auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-lg object-cover border border-slate-700">
                    @else
                        <div class="w-9 h-9 rounded-lg bg-red-600/20 border border-red-500/30 flex items-center justify-center text-red-500 font-bold">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                    @endif
                    <div class="truncate">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@cinema.com' }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-700/50 transition-colors">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Admin Container -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Admin Top Header -->
        <header class="h-20 bg-slate-900/60 border-b border-slate-800 px-6 flex items-center justify-between sticky top-0 z-40 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-400 hover:text-white text-xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="text-xl font-bold text-white tracking-tight">@yield('header_title', 'Dashboard Overview')</h2>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.movies.create') }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-semibold text-sm shadow-lg shadow-red-600/20 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Movie</span>
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-6 md:p-8 flex-1 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 flex items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-xl"></i>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
