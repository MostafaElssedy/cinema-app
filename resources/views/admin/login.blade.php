<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CineMax Cinema Portal</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: radial-gradient(circle at top right, #1e1b4b, #090d16 60%);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .login-glass {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 antialiased text-slate-100">

    <div class="w-full max-w-md">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center text-white text-2xl shadow-xl glow-red group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-film"></i>
                </div>
                <span class="text-3xl font-extrabold tracking-tight text-white">
                    Cine<span class="text-red-500">Max</span>
                </span>
            </a>
            <h2 class="text-xl font-bold text-white mt-4">Admin Dashboard Login</h2>
            <p class="text-slate-400 text-xs mt-1">Sign in to manage movies, view stats, and handle cinema collection.</p>
        </div>

        <!-- Login Card -->
        <div class="login-glass rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            
            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-base"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Admin Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="login-email" value="{{ old('email', 'admin@gmail.com') }}" required 
                               class="w-full bg-slate-900/90 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="login-password" value="password" required 
                               class="w-full bg-slate-900/90 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-white">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-red-600 focus:ring-red-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-bold text-sm shadow-xl shadow-red-600/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In to Dashboard
                </button>
            </form>

        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Public Website
            </a>
        </div>
    </div>

</body>
</html>
