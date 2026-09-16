@extends('layouts.admin')

@section('title', 'Edit Theater - Admin Portal')
@section('header_title', 'Edit Theater')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Top Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Edit Auditorium Hall</h2>
            <p class="text-xs text-slate-400 mt-1">Modify settings for '{{ $theater->name }}'.</p>
        </div>
        <a href="{{ route('admin.theaters.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white font-semibold text-xs flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Theaters
        </a>
    </div>

    <!-- Form Container Card -->
    <div class="admin-glass p-8 rounded-3xl shadow-2xl border border-slate-800">
        <form action="{{ route('admin.theaters.update', $theater->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Hall Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Theater / Hall Name *</label>
                    <input type="text" name="name" value="{{ old('name', $theater->name) }}" required
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Screen / Audio Type -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Screen & Sound Experience *</label>
                    <select name="type" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                        @foreach(['IMAX 3D', 'Dolby Atmos', 'VIP Lounge', '4DX Motion', 'Standard'] as $typeOption)
                            <option value="{{ $typeOption }}" {{ old('type', $theater->type) == $typeOption ? 'selected' : '' }}>
                                {{ $typeOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Seating Grid Configuration -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            Number of Rows (A, B, C...) *
                        </label>
                        <input type="number" name="rows_count" id="rows_count" value="{{ old('rows_count', $theater->rows_count) }}" min="2" max="20" required
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500"
                               oninput="updateCapacity()">
                        <span class="text-[11px] text-slate-500 mt-1 block">Min: 2, Max: 20 rows</span>
                        @error('rows_count') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            Seats Per Row *
                        </label>
                        <input type="number" name="seats_per_row" id="seats_per_row" value="{{ old('seats_per_row', $theater->seats_per_row) }}" min="4" max="30" required
                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500"
                               oninput="updateCapacity()">
                        <span class="text-[11px] text-slate-500 mt-1 block">Min: 4, Max: 30 seats per row</span>
                        @error('seats_per_row') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Calculated Total Capacity Preview -->
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-600/20 text-red-500 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Calculated Seating Capacity:</span>
                            <span class="text-base font-black text-white" id="capacity_preview">
                                {{ $theater->rows_count * $theater->seats_per_row }} Total Seats
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">Scheduled: {{ $theater->showtimes_count }} Shows</span>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <a href="{{ route('admin.theaters.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-600/30 flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-check"></i> Update Theater
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    function updateCapacity() {
        const rows = parseInt(document.getElementById('rows_count').value) || 0;
        const perRow = parseInt(document.getElementById('seats_per_row').value) || 0;
        document.getElementById('capacity_preview').innerText = (rows * perRow) + ' Total Seats';
    }
</script>
@endsection
