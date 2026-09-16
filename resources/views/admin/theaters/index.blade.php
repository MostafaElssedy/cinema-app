@extends('layouts.admin')

@section('title', 'Manage Theaters - Admin Portal')
@section('header_title', 'Cinema Theaters & Auditoriums')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Cinema Theaters</h2>
            <p class="text-xs text-slate-400 mt-1">Configure auditorium halls, seating capacities, and projection systems.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.theaters.create') }}" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-semibold text-xs shadow-lg shadow-red-600/20 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-plus"></i> Add New Theater
            </a>
        </div>
    </div>

    <!-- Theaters Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($theaters as $theater)
            @php
                $totalCapacity = $theater->rows_count * $theater->seats_per_row;
                $badgeColors = match($theater->type) {
                    'IMAX 3D' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                    'Dolby Atmos' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                    'VIP Lounge', 'VIP' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                    default => 'bg-slate-700/40 text-slate-300 border-slate-600',
                };
            @endphp
            <div class="admin-glass p-6 rounded-3xl border border-slate-800 flex flex-col justify-between hover:border-slate-700 transition-all shadow-xl">
                <div>
                    <!-- Header -->
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-600/20 text-red-500 border border-red-500/30 flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-couch"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $badgeColors }}">
                            {{ $theater->type }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg font-bold text-white mb-2">{{ $theater->name }}</h3>

                    <!-- Specs List -->
                    <div class="space-y-2 py-3 border-y border-slate-800/80 text-xs">
                        <div class="flex items-center justify-between text-slate-300">
                            <span class="text-slate-500"><i class="fa-solid fa-users mr-1.5"></i> Total Capacity:</span>
                            <span class="font-black text-white text-sm">{{ $totalCapacity }} Seats</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span class="text-slate-500"><i class="fa-solid fa-arrows-up-down mr-1.5"></i> Rows Layout:</span>
                            <span class="font-semibold text-slate-200">{{ $theater->rows_count }} Rows (A - {{ chr(64 + min($theater->rows_count, 26)) }})</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span class="text-slate-500"><i class="fa-solid fa-arrows-left-right mr-1.5"></i> Seats / Row:</span>
                            <span class="font-semibold text-slate-200">{{ $theater->seats_per_row }} seats</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span class="text-slate-500"><i class="fa-regular fa-clock mr-1.5"></i> Scheduled Shows:</span>
                            <span class="font-semibold text-red-400">{{ $theater->showtimes_count }} Showtimes</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-4 mt-2">
                    <a href="{{ route('admin.theaters.edit', $theater->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <form action="{{ route('admin.theaters.destroy', $theater->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete theater \'{{ $theater->name }}\'?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-950/40 hover:bg-red-900/60 text-red-400 border border-red-500/30 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-trash-can"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full admin-glass p-12 text-center rounded-3xl border border-slate-800">
                <i class="fa-solid fa-couch text-4xl text-slate-600 mb-3 block"></i>
                <h3 class="text-base font-bold text-white">No Theaters Found</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Start by setting up your cinema auditoriums and seating layouts.</p>
                <a href="{{ route('admin.theaters.create') }}" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-semibold text-xs inline-flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add First Theater
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
