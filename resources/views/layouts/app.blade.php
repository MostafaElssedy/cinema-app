<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CineMax - Discover & Manage Movies')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cinema: {
                            900: '#070a12',
                            800: '#0f172a',
                            700: '#1e293b',
                            600: '#334155',
                            accent: '#e50914',
                            gold: '#f59e0b',
                            goldHover: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #070a12;
            color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .glass-nav {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card:hover {
            border-color: rgba(229, 9, 20, 0.4);
            box-shadow: 0 10px 30px -10px rgba(229, 9, 20, 0.3);
        }
        .hero-gradient {
            background: linear-gradient(180deg, rgba(7, 10, 18, 0.2) 0%, rgba(7, 10, 18, 0.95) 85%, #070a12 100%);
        }
        .glow-red {
            box-shadow: 0 0 25px -5px rgba(229, 9, 20, 0.5);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-red-600 selection:text-white">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center text-white shadow-lg glow-red group-hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-film text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-white group-hover:text-red-500 transition-colors">
                    Cine<span class="text-red-500">Max</span>
                </span>
            </a>

            <!-- Search Bar (Desktop) -->
            <form action="{{ route('home') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-8 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search movies, genres, actors..." 
                       class="w-full bg-slate-900/90 border border-slate-700/80 rounded-full py-2.5 pl-11 pr-4 text-sm text-slate-100 placeholder-slate-400 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            </form>

            <!-- Nav Links & Watchlist Badge -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'text-red-500' : 'text-slate-300 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-compass mr-1.5"></i> Discover
                </a>

                <a href="{{ route('watchlist.index') }}" class="relative px-3 py-2 text-sm font-semibold {{ request()->routeIs('watchlist.index') ? 'text-red-500' : 'text-slate-300 hover:text-white' }} transition-colors">
                    <i class="fa-solid fa-bookmark mr-1.5"></i> Watchlist
                    <span id="nav-watchlist-count" class="ml-1.5 px-2 py-0.5 text-xs font-bold bg-red-600 text-white rounded-full hidden">0</span>
                </a>

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 text-sm font-semibold hover:bg-slate-700 transition-colors">
                        <i class="fa-solid fa-gauge text-red-500"></i> Admin Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white text-sm font-semibold shadow-md glow-red transition-all">
                        <i class="fa-solid fa-user-shield"></i> Admin Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Floating AI Chatbot Button & Drawer -->
    <div class="fixed bottom-6 right-6 z-50">
        <!-- Floating Bot Trigger Button -->
        <button id="chatbot-toggle-btn" onclick="toggleChatbot()" class="w-14 h-14 rounded-full bg-gradient-to-tr from-red-600 to-amber-500 text-white shadow-2xl glow-red flex items-center justify-center text-2xl hover:scale-110 transition-transform duration-300 border border-white/20">
            <i class="fa-solid fa-robot" id="chatbot-toggle-icon"></i>
        </button>

        <!-- Floating Chat Drawer Card -->
        <div id="chatbot-drawer" class="hidden absolute bottom-20 right-0 w-[90vw] sm:w-[380px] h-[500px] max-h-[75vh] bg-slate-900/95 backdrop-blur-2xl border border-slate-700/80 rounded-3xl shadow-2xl flex flex-col overflow-hidden transition-all duration-300">
            
            <!-- Drawer Header -->
            <div class="p-4 bg-slate-800/80 border-b border-slate-700/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-600/20 text-red-500 border border-red-500/30 flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-sm">CineBot AI Assistant</h4>
                        <span class="text-[10px] text-emerald-400 flex items-center gap-1 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online & Active
                        </span>
                    </div>
                </div>
                <button onclick="toggleChatbot()" class="text-slate-400 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Chat Messages Container -->
            <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3 text-xs leading-relaxed">
                <!-- Bot Welcome Message -->
                <div class="flex gap-2">
                    <div class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 font-bold">
                        <i class="fa-solid fa-robot text-xs"></i>
                    </div>
                    <div class="p-3 rounded-2xl rounded-tl-none bg-slate-800 text-slate-200 border border-slate-700/50 max-w-[85%]">
                        👋 مرحباً بك! أنا مساعد الذكاء الاصطناعي الخاص بـ CineMax. يمكنني الإجابة عن أي موضوع واقتراح أفضل الأفلام لك!
                    </div>
                </div>
            </div>

            <!-- Chat Input Form -->
            <form id="chatbot-form" onsubmit="sendChatMessage(event)" class="p-3 bg-slate-950/80 border-t border-slate-800 flex items-center gap-2">
                <input type="text" id="chatbot-input" placeholder="Type a message or question..." autocomplete="off"
                       class="flex-1 bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-red-500">
                <button type="submit" id="chatbot-send-btn" class="w-9 h-9 rounded-xl bg-red-600 hover:bg-red-500 text-white flex items-center justify-center shrink-0 transition-colors shadow-lg">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>

        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-800 bg-slate-950/80 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-slate-400 text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold text-sm">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <span class="font-bold text-white tracking-tight text-lg">CineMax</span>
                    <span class="text-slate-600">|</span>
                    <span>Cinema Management & Movie Discovery</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="hover:text-red-400 transition-colors">Movies</a>
                    <a href="{{ route('watchlist.index') }}" class="hover:text-red-400 transition-colors">My Watchlist</a>
                    <a href="/api/movies" target="_blank" class="hover:text-red-400 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-code text-xs"></i> REST API
                    </a>
                </div>
                <p>&copy; {{ date('Y') }} CineMax App. Built with Laravel Blade & REST APIs.</p>
            </div>
        </div>
    </footer>

    <!-- Global Device ID & Watchlist & Chatbot JS Manager -->
    <script>
        // Get or Create persistent Device ID for Mobile / Anonymous Web User
        function getDeviceId() {
            let deviceId = localStorage.getItem('cinemax_device_id');
            if (!deviceId) {
                deviceId = 'device_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
                localStorage.setItem('cinemax_device_id', deviceId);
            }
            return deviceId;
        }

        // Global function to update Nav Watchlist badge
        async function refreshWatchlistBadge() {
            const deviceId = getDeviceId();
            try {
                const response = await fetch(`/api/watchlist?device_id=${deviceId}`);
                const data = await response.json();
                if (data.success || data.status === 'success') {
                    const badge = document.getElementById('nav-watchlist-count');
                    if (badge) {
                        badge.textContent = data.count;
                        if (data.count > 0) {
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                }
            } catch (e) {
                console.error("Failed to update watchlist count", e);
            }
        }

        // Toggle movie in Watchlist via REST API
        async function toggleWatchlist(movieId, btnElement) {
            const deviceId = getDeviceId();
            const icon = btnElement.querySelector('i');
            
            try {
                // Check current state
                const res = await fetch(`/api/watchlist?device_id=${deviceId}`);
                const data = await res.json();
                const existing = data.data ? data.data.find(item => item.movie_id == movieId || item.id == movieId || (item.movie && item.movie.id == movieId)) : null;

                if (existing) {
                    // Remove
                    const delRes = await fetch('/api/watchlist', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ device_id: deviceId, movie_id: movieId })
                    });
                    const delData = await delRes.json();
                    if (delData.success || delData.status === 'success') {
                        btnElement.classList.remove('bg-red-600', 'text-white');
                        btnElement.classList.add('bg-slate-800/80', 'text-slate-300');
                        if (icon) icon.className = 'fa-regular fa-bookmark';
                        showToast('Removed from your watchlist');
                    }
                } else {
                    // Add
                    const addRes = await fetch('/api/watchlist', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ device_id: deviceId, movie_id: movieId })
                    });
                    const addData = await addRes.json();
                    if (addData.success || addData.status === 'success') {
                        btnElement.classList.remove('bg-slate-800/80', 'text-slate-300');
                        btnElement.classList.add('bg-red-600', 'text-white');
                        if (icon) icon.className = 'fa-solid fa-bookmark';
                        showToast('Added to your watchlist!');
                    }
                }
                refreshWatchlistBadge();
                if (window.onWatchlistUpdated) window.onWatchlistUpdated();
            } catch (err) {
                console.error('Error toggling watchlist:', err);
            }
        }

        function showToast(message) {
            let toast = document.getElementById('global-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'global-toast';
                toast.className = 'fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl bg-slate-900 border border-slate-700 text-white font-medium text-sm shadow-2xl flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300';
                document.body.appendChild(toast);
            }
            toast.innerHTML = `<i class="fa-solid fa-circle-info text-red-500"></i> ${message}`;
            toast.classList.remove('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
            }, 3000);
        }

        // AI Chatbot State Manager with Conversation Memory
        let conversationHistory = [];

        function toggleChatbot() {
            const drawer = document.getElementById('chatbot-drawer');
            const icon = document.getElementById('chatbot-toggle-icon');
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                icon.className = 'fa-solid fa-xmark';
                document.getElementById('chatbot-input').focus();
            } else {
                drawer.classList.add('hidden');
                icon.className = 'fa-solid fa-robot';
            }
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesContainer = document.getElementById('chatbot-messages');
            const sendBtn = document.getElementById('chatbot-send-btn');

            // Render User Message
            const userBubble = document.createElement('div');
            userBubble.className = 'flex justify-end';
            userBubble.innerHTML = `<div class="p-3 rounded-2xl rounded-tr-none bg-red-600 text-white max-w-[85%] shadow-md">${escapeHtml(message)}</div>`;
            messagesContainer.appendChild(userBubble);

            input.value = '';
            sendBtn.disabled = true;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Render Typing Indicator
            const typingBubble = document.createElement('div');
            typingBubble.id = 'typing-indicator';
            typingBubble.className = 'flex gap-2';
            typingBubble.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 font-bold">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="p-3 rounded-2xl rounded-tl-none bg-slate-800 text-slate-400 border border-slate-700/50 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:0.4s]"></span>
                </div>
            `;
            messagesContainer.appendChild(typingBubble);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const res = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: message,
                        history: conversationHistory
                    })
                });

                const data = await res.json();
                typingBubble.remove();
                sendBtn.disabled = false;

                const replyText = data.reply || 'No response received.';

                // Append to History Memory
                conversationHistory.push({ role: 'user', text: message });
                conversationHistory.push({ role: 'model', text: replyText });

                // Render Bot Reply
                const botBubble = document.createElement('div');
                botBubble.className = 'flex gap-2';
                botBubble.innerHTML = `
                    <div class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 font-bold">
                        <i class="fa-solid fa-robot text-xs"></i>
                    </div>
                    <div class="p-3 rounded-2xl rounded-tl-none bg-slate-800 text-slate-100 border border-slate-700/50 max-w-[85%] leading-relaxed whitespace-pre-wrap">${escapeHtml(replyText)}</div>
                `;
                messagesContainer.appendChild(botBubble);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

            } catch (err) {
                console.error('Chatbot error:', err);
                typingBubble.remove();
                sendBtn.disabled = false;

                const errBubble = document.createElement('div');
                errBubble.className = 'flex gap-2';
                errBubble.innerHTML = `
                    <div class="w-7 h-7 rounded-lg bg-red-600/20 text-red-500 flex items-center justify-center shrink-0 font-bold">
                        <i class="fa-solid fa-robot text-xs"></i>
                    </div>
                    <div class="p-3 rounded-2xl rounded-tl-none bg-red-500/10 text-red-400 border border-red-500/30 max-w-[85%]">
                        عذراً، حدث خطأ في الاتصال بالذكاء الاصطناعي.
                    </div>
                `;
                messagesContainer.appendChild(errBubble);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // --- Movie Trailer Modal Global Manager ---
        function openTrailerModal(embedUrl, title) {
            const modal = document.getElementById('trailer-modal');
            const iframe = document.getElementById('trailer-iframe');
            const titleEl = document.getElementById('trailer-title');
            
            if (!embedUrl) return;

            titleEl.innerHTML = `<i class="fa-brands fa-youtube text-red-500 text-xl"></i> <span class="truncate">${escapeHtml(title)} - Official Trailer</span>`;
            iframe.src = embedUrl;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10);
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeTrailerModal() {
            const modal = document.getElementById('trailer-modal');
            const iframe = document.getElementById('trailer-iframe');
            
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                iframe.src = ''; // Stop video and audio playback immediately
                document.body.style.overflow = '';
            }, 300);
        }

        function handleTrailerBackdrop(e) {
            if (e.target.id === 'trailer-modal') {
                closeTrailerModal();
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeTrailerModal();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            refreshWatchlistBadge();
        });
    </script>

    <!-- Global Movie Trailer Video Modal -->
    <div id="trailer-modal" onclick="handleTrailerBackdrop(event)" class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6 bg-slate-950/85 backdrop-blur-md hidden opacity-0 transition-opacity duration-300">
        <div class="relative w-full max-w-4xl bg-slate-900 border border-slate-700/80 rounded-3xl overflow-hidden shadow-2xl flex flex-col transform transition-all">
            <!-- Modal Header -->
            <div class="px-5 py-4 bg-slate-800/90 flex items-center justify-between border-b border-slate-700">
                <h4 id="trailer-title" class="font-black text-white text-base sm:text-lg flex items-center gap-2 truncate pr-4">
                    <i class="fa-brands fa-youtube text-red-500 text-xl"></i> Official Trailer
                </h4>
                <button type="button" onclick="closeTrailerModal()" class="w-9 h-9 rounded-full bg-slate-700/80 hover:bg-red-600 text-slate-300 hover:text-white flex items-center justify-center transition-all shadow-md shrink-0" title="Close (Esc)">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <!-- Video Player -->
            <div class="aspect-video w-full bg-black relative">
                <iframe id="trailer-iframe" src="" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
