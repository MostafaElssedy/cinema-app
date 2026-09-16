@extends('layouts.app')

@section('title', 'Book Seats - ' . $showtime->movie->title . ' - CineMax')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Top Navigation & Details Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <a href="{{ route('movies.show', $showtime->movie_id) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to {{ $showtime->movie->title }}
        </a>

        @if(isset($otherShowtimes) && $otherShowtimes->count() > 0)
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">Other Showtimes:</span>
                <div class="flex items-center gap-1.5 overflow-x-auto max-w-xs sm:max-w-md py-1">
                    @foreach($otherShowtimes->take(4) as $ost)
                        <a href="{{ route('booking.seats', $ost->id) }}" 
                           class="px-2.5 py-1 rounded-lg bg-slate-900/80 border border-slate-800 hover:border-red-500 text-xs text-slate-300 hover:text-white transition-colors whitespace-nowrap">
                            {{ $ost->start_time }} ({{ $ost->theater->type ?? 'Std' }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Error / Alert Messages -->
    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-950/60 border border-red-500/50 text-red-200 flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-red-400 text-lg mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Please check the following issues:</h4>
                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Interactive Cinema Hall & Seat Map (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-8 border border-slate-800 relative overflow-hidden">
                
                <!-- Hall Info Header -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-white flex items-center gap-2.5">
                            <i class="fa-solid fa-couch text-red-500"></i> Select Your Seats
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $showtime->theater->name }} &bull; <span class="text-amber-400 font-semibold">{{ $showtime->theater->type }}</span> &bull; Price: <span class="text-white font-bold">{{ number_format($showtime->price, 0) }} EGP</span> / seat
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300 font-bold">
                            <i class="fa-regular fa-clock text-red-500 mr-1"></i> {{ $showtime->start_time }}
                        </span>
                        <span class="px-3 py-1 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300 font-bold">
                            <i class="fa-regular fa-calendar text-amber-500 mr-1"></i> {{ $showtime->show_date ? $showtime->show_date->format('M d') : 'Today' }}
                        </span>
                    </div>
                </div>

                <!-- Curved Cinema Screen -->
                <div class="my-10 text-center">
                    <div class="relative w-4/5 mx-auto">
                        <!-- Curved glowing screen bar -->
                        <div class="h-3 w-full bg-gradient-to-r from-transparent via-red-500 to-transparent rounded-t-full shadow-[0_10px_35px_rgba(229,9,20,0.6)]"></div>
                        <div class="h-14 w-full bg-gradient-to-b from-red-500/10 to-transparent transform -perspective-500 rotateX-30 pointer-events-none"></div>
                        <span class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-slate-400 block -mt-10">
                            CINEMA SCREEN (FRONT)
                        </span>
                    </div>
                </div>

                <!-- Seat Grid -->
                @php
                    $rows = range('A', chr(ord('A') + ($showtime->theater->rows_count ?? 6) - 1));
                    $colsCount = $showtime->theater->seats_per_row ?? 10;
                @endphp

                <div class="overflow-x-auto py-6 px-2 flex justify-center">
                    <div class="inline-block space-y-3 min-w-[360px]">
                        @foreach($rows as $row)
                            <div class="flex items-center justify-center gap-2 sm:gap-3">
                                <!-- Row label left -->
                                <span class="w-5 text-center font-bold text-xs text-slate-500 select-none">{{ $row }}</span>

                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    @for($col = 1; $col <= $colsCount; $col++)
                                        @php
                                            $seatNumber = $row . $col;
                                            $isBooked = in_array($seatNumber, $bookedSeats);
                                            // Make middle seats or last row special if wanted
                                            $isVip = in_array($row, ['E', 'F']);
                                        @endphp

                                        @if($col == 6 && $colsCount > 6)
                                            <!-- Aisle Walkway Space in Middle -->
                                            <div class="w-4 sm:w-6"></div>
                                        @endif

                                        <button type="button"
                                                class="seat-btn w-7 h-7 sm:w-8 sm:h-8 rounded-lg text-[10px] sm:text-xs font-bold transition-all duration-150 flex items-center justify-center relative select-none
                                                {{ $isBooked ? 'bg-slate-900 border border-slate-800 text-slate-600 cursor-not-allowed' : 'bg-slate-800/90 border border-slate-700/80 text-slate-300 hover:border-red-500 hover:text-white cursor-pointer hover:scale-105' }}"
                                                data-seat="{{ $seatNumber }}"
                                                data-price="{{ $showtime->price }}"
                                                {{ $isBooked ? 'disabled' : '' }}
                                                title="{{ $isBooked ? 'Seat ' . $seatNumber . ' (Booked)' : 'Seat ' . $seatNumber . ' - ' . number_format($showtime->price, 0) . ' EGP' }}">
                                            @if($isBooked)
                                                <i class="fa-solid fa-xmark text-[9px]"></i>
                                            @else
                                                {{ $seatNumber }}
                                            @endif
                                        </button>
                                    @endfor
                                </div>

                                <!-- Row label right -->
                                <span class="w-5 text-center font-bold text-xs text-slate-500 select-none">{{ $row }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Seat Legend -->
                <div class="flex flex-wrap items-center justify-center gap-6 pt-6 border-t border-slate-800 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-slate-800 border border-slate-700"></div>
                        <span>Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-600 border border-red-500 shadow-sm shadow-red-600/50"></div>
                        <span>Selected</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-600">
                            <i class="fa-solid fa-xmark text-[9px]"></i>
                        </div>
                        <span>Reserved / Sold</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right: Booking Summary & Checkout Form (4 cols) -->
        <div class="lg:col-span-4 sticky top-24">
            <div class="glass-card rounded-3xl p-6 border border-slate-800 shadow-2xl relative">
                
                <!-- Movie Mini Summary -->
                <div class="flex gap-4 pb-5 border-b border-slate-800">
                    <img src="{{ $showtime->movie->image }}" alt="{{ $showtime->movie->title }}" class="w-16 h-24 object-cover rounded-xl shadow-md">
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-black uppercase text-red-500 tracking-wider">Booking Ticket</span>
                        <h3 class="font-extrabold text-white text-base truncate">{{ $showtime->movie->title }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Year: {{ $showtime->movie->release_year }} &bull; {{ $showtime->theater->type }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs font-bold text-amber-400">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($showtime->movie->rating, 1) }}
                            </span>
                            <span class="text-xs text-slate-500">&bull;</span>
                            <span class="text-xs text-slate-300 font-medium">{{ $showtime->start_time }}</span>
                        </div>
                    </div>
                </div>

                <!-- Selected Seats Dynamic Section -->
                <div class="py-5 border-b border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Selected Seats:</span>
                        <span id="selected-count-badge" class="px-2 py-0.5 rounded-full bg-slate-800 text-xs font-bold text-slate-300">0 Seats</span>
                    </div>

                    <!-- Selected Seats Tags Container -->
                    <div id="selected-seats-container" class="flex flex-wrap gap-1.5 min-h-[36px] items-center p-2 rounded-xl bg-slate-900/60 border border-slate-800/80">
                        <span class="text-xs text-slate-500 italic" id="empty-seats-placeholder">Click on seats above to select</span>
                    </div>

                    <!-- Pricing Calculation -->
                    <div class="space-y-1.5 pt-2 text-xs">
                        <div class="flex justify-between text-slate-400">
                            <span>Ticket Price:</span>
                            <span class="font-medium text-white">{{ number_format($showtime->price, 0) }} EGP &times; <span id="price-multiplier">0</span></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Booking Fee:</span>
                            <span class="font-medium text-emerald-400">FREE</span>
                        </div>
                        <div class="flex justify-between text-sm font-black text-white pt-2 border-t border-slate-800/80">
                            <span>Total Price:</span>
                            <span class="text-amber-400 text-lg font-black" id="total-price-display">0 EGP</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Details Form -->
                <form id="booking-form" action="{{ route('booking.store', $showtime->id) }}" method="POST" class="pt-5 space-y-4">
                    @csrf
                    <!-- Hidden field to hold selected seats string e.g. "A1,A2" -->
                    <input type="hidden" name="seats" id="seats-input" value="{{ old('seats') }}">

                    <div>
                        <label for="customer_name" class="block text-xs font-bold text-slate-300 mb-1">
                            Your Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="customer_name" 
                               id="customer_name" 
                               required 
                               value="{{ old('customer_name') }}"
                               placeholder="e.g. Mohamed Ali"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-red-500 transition-colors">
                    </div>

                    <div>
                        <label for="customer_email" class="block text-xs font-bold text-slate-300 mb-1">
                            Email Address (for E-Ticket) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="customer_email" 
                               id="customer_email" 
                               required 
                               value="{{ old('customer_email') }}"
                               placeholder="e.g. name@example.com"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-red-500 transition-colors">
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-xs font-bold text-slate-300 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="customer_phone" 
                               id="customer_phone" 
                               required 
                               value="{{ old('customer_phone') }}"
                               placeholder="e.g. 01012345678"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white placeholder-slate-500 text-xs focus:outline-none focus:border-red-500 transition-colors">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="confirm-btn"
                            disabled
                            class="w-full py-4 rounded-xl bg-red-600 hover:bg-red-500 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-red-600 text-white font-extrabold text-sm shadow-xl glow-red flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-check-circle"></i> Confirm & Get E-Ticket
                    </button>

                    <p class="text-[11px] text-slate-400 text-center flex items-center justify-center gap-1.5 pt-1">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Instant Booking Confirmation & QR Code
                    </p>
                </form>

            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    .rotateX-30 {
        transform: perspective(400px) rotateX(40deg);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ticketPrice = {{ $showtime->price }};
        const maxSeats = 8;
        let selectedSeats = new Set();

        const seatButtons = document.querySelectorAll('.seat-btn:not([disabled])');
        const seatsInput = document.getElementById('seats-input');
        const container = document.getElementById('selected-seats-container');
        const placeholder = document.getElementById('empty-seats-placeholder');
        const countBadge = document.getElementById('selected-count-badge');
        const multiplier = document.getElementById('price-multiplier');
        const totalDisplay = document.getElementById('total-price-display');
        const confirmBtn = document.getElementById('confirm-btn');

        // Restore if old input exists
        if (seatsInput.value) {
            seatsInput.value.split(',').forEach(s => {
                const trimmed = s.trim();
                if (trimmed) selectedSeats.add(trimmed);
            });
            updateSeatsUI();
        }

        seatButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const seatId = btn.dataset.seat;

                if (selectedSeats.has(seatId)) {
                    selectedSeats.delete(seatId);
                } else {
                    if (selectedSeats.size >= maxSeats) {
                        alert(`You can select a maximum of ${maxSeats} seats.`);
                        return;
                    }
                    selectedSeats.add(seatId);
                }

                updateSeatsUI();
            });
        });

        function updateSeatsUI() {
            // Update seat button appearances
            seatButtons.forEach(btn => {
                const seatId = btn.dataset.seat;
                if (selectedSeats.has(seatId)) {
                    btn.classList.add('bg-red-600', 'border-red-500', 'text-white', 'shadow-md', 'shadow-red-600/50', 'scale-105');
                    btn.classList.remove('bg-slate-800/90', 'border-slate-700/80', 'text-slate-300');
                } else {
                    btn.classList.remove('bg-red-600', 'border-red-500', 'text-white', 'shadow-md', 'shadow-red-600/50', 'scale-105');
                    btn.classList.add('bg-slate-800/90', 'border-slate-700/80', 'text-slate-300');
                }
            });

            // Update hidden input
            const sortedSeats = Array.from(selectedSeats).sort();
            seatsInput.value = sortedSeats.join(',');

            // Update tags container
            container.innerHTML = '';
            if (sortedSeats.length === 0) {
                container.appendChild(placeholder);
                confirmBtn.disabled = true;
            } else {
                sortedSeats.forEach(seat => {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-600/20 border border-red-500/40 text-red-300 font-bold text-xs';
                    tag.innerHTML = `<span>${seat}</span> <button type="button" class="text-red-400 hover:text-white" onclick="removeSeat('${seat}')">&times;</button>`;
                    container.appendChild(tag);
                });
                confirmBtn.disabled = false;
            }

            // Update counts & total
            const count = sortedSeats.length;
            countBadge.innerText = `${count} ${count === 1 ? 'Seat' : 'Seats'}`;
            multiplier.innerText = count;
            const total = count * ticketPrice;
            totalDisplay.innerText = `${total.toLocaleString()} EGP`;
        }

        window.removeSeat = function(seat) {
            selectedSeats.delete(seat);
            updateSeatsUI();
        };
    });
</script>
@endpush

@endsection
