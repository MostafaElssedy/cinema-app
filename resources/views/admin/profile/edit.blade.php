@extends('layouts.admin')

@section('title', 'Admin Profile - Edit Details')
@section('header_title', 'Edit Admin Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    <!-- Header Action Bar -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Admin Profile Settings</h2>
            <p class="text-xs text-slate-400 mt-1">Update your account avatar, name, email address, and security password.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white font-semibold text-xs flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Edit Profile Card -->
    <div class="admin-glass p-8 rounded-3xl shadow-2xl">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Preview & Upload -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-800">
                <div class="relative w-24 h-24 rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-700 shadow-xl flex-shrink-0 flex items-center justify-center">
                    @if($user->avatar)
                        <img id="avatar_preview" src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div id="avatar_placeholder" class="w-full h-full bg-red-600/20 text-red-500 flex items-center justify-center font-bold text-3xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img id="avatar_preview" src="" alt="Avatar Preview" class="w-full h-full object-cover hidden">
                    @endif
                </div>

                <div class="space-y-2 flex-1 text-center sm:text-left">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">Admin Avatar Image</label>
                    <input type="file" name="avatar" accept="image/*" onchange="previewAvatarImage(this)"
                           class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
                    <span class="text-xs text-slate-500 block">Supported formats: JPG, PNG, WEBP (Max: 4MB)</span>
                    @error('avatar') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Name -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Admin Name *</label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-red-500">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                </div>
                @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Admin Email Address *</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-red-500">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                </div>
                @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-slate-800 my-6">

            <div class="space-y-1">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-lock text-red-500"></i> Change Password
                </h3>
                <p class="text-xs text-slate-400">Leave password fields empty if you do not wish to change your password.</p>
            </div>

            <!-- Current Password -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Current Password</label>
                <div class="relative">
                    <input type="password" name="current_password" placeholder="Enter current password to verify"
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-red-500">
                    <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                </div>
                @error('current_password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- New Password & Confirmation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" placeholder="At least 8 characters"
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-red-500">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    </div>
                    @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" placeholder="Re-type new password"
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-red-500">
                        <i class="fa-solid fa-check-double absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-8 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm shadow-xl shadow-red-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function previewAvatarImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar_preview');
                const placeholder = document.getElementById('avatar_placeholder');
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
