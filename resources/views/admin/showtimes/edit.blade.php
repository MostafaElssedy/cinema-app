@extends('layouts.admin')

@section('title', 'Edit Showtime - Admin Portal')
@section('header_title', 'Edit Showtime')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Top Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Edit Showtime Details</h2>
            <p class="text-xs text-slate-400 mt-1">Modify auditorium hall, screening time, and price for '{{ $showtime->movie->title }}'.</p>
        </div>
        <a href="{{ route('admin.showtimes.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white font-semibold text-xs flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Schedule
        </a>
    </div>

    <!-- Form Container Card -->
    <div class="admin-glass p-8 rounded-3xl shadow-2xl border border-slate-800">
        <form action="{{ route('admin.showtimes.update', $showtime->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Select Movie -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Movie *</label>
                    <select name="movie_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id', $showtime->movie_id) == $movie->id ? 'selected' : '' }}>
                                {{ $movie->title }} ({{ $movie->release_year }})
                            </option>
                        @endforeach
                    </select>
                    @error('movie_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Select Theater Hall -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Auditorium / Theater Hall *</label>
                    <select name="theater_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                        @foreach($theaters as $theater)
                            <option value="{{ $theater->id }}" {{ old('theater_id', $showtime->theater_id) == $theater->id ? 'selected' : '' }}>
                                {{ $theater->name }} &bull; {{ $theater->type }} ({{ $theater->rows_count * $theater->seats_per_row }} seats)
                            </option>
                        @endforeach
                    </select>
                    @error('theater_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Show Date & Ticket Price -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Screening Date *</label>
                        <input type="date" name="show_date" value="{{ old('show_date', $showtime->show_date->toDateString()) }}" required
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                        @error('show_date') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Ticket Price (EGP) *</label>
                        <div class="relative">
                            <input type="number" step="1" name="price" value="{{ old('price', round($showtime->price)) }}" min="10" max="2000" required
                                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 pr-14">
                            <span class="absolute right-4 top-3 text-xs font-bold text-slate-400">EGP</span>
                        </div>
                        @error('price') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Show Start Time *</label>
                    <input type="text" name="start_time" id="start_time_input" value="{{ old('start_time', $showtime->start_time) }}" required
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 mb-2">
                    
                    <!-- Quick Time Suggestions -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[11px] text-slate-500">Quick suggestions:</span>
                        @foreach(['01:30 PM', '04:30 PM', '07:30 PM', '08:00 PM', '10:45 PM', '11:30 PM'] as $suggestedTime)
                            <button type="button" onclick="document.getElementById('start_time_input').value = '{{ $suggestedTime }}'"
                                    class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white text-[11px] transition-colors">
                                {{ $suggestedTime }}
                            </button>
                        @endforeach
                    </div>
                    @error('start_time') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                    <a href="{{ route('admin.showtimes.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/30 flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-check"></i> Update Showtime
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
