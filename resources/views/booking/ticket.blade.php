@extends('layouts.app')

@section('title', 'E-Ticket - ' . $booking->booking_reference . ' - CineMax')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 print:p-0 print:m-0">
    
    <!-- Non-printable top action bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8 print:hidden">
        <a href="{{ route('movies.show', $booking->showtime->movie_id) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white text-sm font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Movie
        </a>

        <div class="flex items-center gap-3">
            <button onclick="downloadTicketImage()" 
                    id="download-ticket-btn"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-amber-500 hover:from-red-500 hover:to-amber-400 text-white font-bold text-sm shadow-lg flex items-center gap-2 transition-all hover:scale-105">
                <i class="fa-solid fa-download"></i> Download as Image (PNG)
            </button>
            <a href="{{ route('home') }}" 
               class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 text-white font-bold text-sm transition-all">
                Browse Movies
            </a>
        </div>
    </div>

    <!-- Success Confirmation Banner (print:hidden) -->
    <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-emerald-950/80 via-slate-900 to-slate-900 border border-emerald-500/40 text-white shadow-xl flex items-center gap-4 print:hidden">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-2xl flex-shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <span class="text-xs font-black uppercase tracking-widest text-emerald-400">Reservation Successful</span>
            <h2 class="text-xl font-black text-white">Your Cinema Ticket is Ready!</h2>
            <p class="text-xs text-slate-300 mt-0.5">
                Booking reference: <strong class="text-amber-400 font-mono">{{ $booking->booking_reference }}</strong>. Present this QR code or printed ticket at the cinema entrance.
            </p>
        </div>
    </div>

    <!-- Official Cinema E-Ticket Card -->
    <div id="printable-ticket" class="relative rounded-3xl bg-slate-900 border-2 border-slate-800 shadow-2xl overflow-hidden print:border print:border-black print:bg-white print:text-black">
        
        <!-- Ticket Header Glow & Banner -->
        <div class="h-3 bg-gradient-to-r from-red-600 via-amber-500 to-red-600 print:bg-black"></div>

        <div class="grid grid-cols-1 md:grid-cols-12">
            
            <!-- Left Side: Movie Poster & Branding (4 cols) -->
            <div class="md:col-span-4 p-6 sm:p-8 bg-slate-950/60 border-b md:border-b-0 md:border-r border-slate-800/80 flex flex-col justify-between items-center text-center print:bg-white print:border-black">
                <div class="space-y-4 w-full">
                    <!-- Brand -->
                    <div class="flex items-center justify-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-red-600 flex items-center justify-center text-white text-xs font-black shadow-md">
                            <i class="fa-solid fa-play"></i>
                        </span>
                        <span class="text-lg font-black tracking-wider text-white print:text-black">
                            CINE<span class="text-red-500">MAX</span>
                        </span>
                    </div>

                    <!-- Poster -->
                    <div class="mx-auto w-36 sm:w-44 aspect-[2/3] rounded-2xl overflow-hidden shadow-xl border border-slate-800 print:border-black">
                        <img src="{{ $booking->showtime->movie->image }}" 
                             alt="{{ $booking->showtime->movie->title }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <div>
                        <span class="px-2.5 py-1 rounded-lg bg-red-600/20 text-red-400 border border-red-500/30 text-[11px] font-extrabold uppercase tracking-widest print:border print:text-black">
                            {{ $booking->showtime->theater->type }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-800/60 w-full print:border-black">
                    <span class="text-[11px] text-slate-400 block print:text-black">Admission Status</span>
                    <span class="text-xs font-black text-emerald-400 uppercase tracking-widest print:text-black">
                        <i class="fa-solid fa-shield-check mr-1"></i> Confirmed
                    </span>
                </div>
            </div>

            <!-- Right Side: Ticket Details & QR Code (8 cols) -->
            <div class="md:col-span-8 p-6 sm:p-8 flex flex-col justify-between space-y-6 print:bg-white">
                
                <!-- Ticket Top Bar -->
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-400 block print:text-black">
                            Electronic Cinema Ticket
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black text-white mt-0.5 print:text-black">
                            {{ $booking->showtime->movie->title }}
                        </h1>
                        <p class="text-xs text-slate-400 mt-1 print:text-black">
                            {{ $booking->showtime->theater->name }} &bull; {{ $booking->showtime->movie->release_year }}
                        </p>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] font-mono text-slate-400 block uppercase print:text-black">Ref Code</span>
                        <span class="text-lg font-mono font-black text-amber-400 tracking-wider print:text-black">
                            #{{ $booking->booking_reference }}
                        </span>
                    </div>
                </div>

                <!-- Ticket Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-slate-950/40 border border-slate-800/80 print:bg-gray-50 print:border-black">
                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Date</span>
                        <span class="text-sm font-bold text-white print:text-black">
                            <i class="fa-regular fa-calendar text-red-500 mr-1"></i>
                            {{ $booking->showtime->show_date ? $booking->showtime->show_date->format('M d, Y') : 'Today' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Time</span>
                        <span class="text-sm font-bold text-white print:text-black">
                            <i class="fa-regular fa-clock text-amber-500 mr-1"></i>
                            {{ $booking->showtime->start_time }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Screen / Hall</span>
                        <span class="text-sm font-bold text-white print:text-black truncate block">
                            {{ $booking->showtime->theater->name }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Booked For</span>
                        <span class="text-sm font-bold text-white print:text-black truncate block">
                            {{ $booking->customer_name }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Phone</span>
                        <span class="text-sm font-bold text-white print:text-black">
                            {{ $booking->customer_phone }}
                        </span>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 block font-medium print:text-black">Total Paid</span>
                        <span class="text-sm font-black text-emerald-400 print:text-black">
                            {{ number_format($booking->total_price, 0) }} EGP
                        </span>
                    </div>
                </div>

                <!-- Booked Seats Display -->
                <div class="p-4 rounded-2xl bg-red-600/10 border border-red-500/20 flex flex-wrap items-center justify-between gap-3 print:bg-gray-100 print:border-black">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-red-400 block print:text-black">Reserved Seats</span>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @foreach($booking->bookedSeats as $seat)
                                <span class="px-3 py-1 rounded-lg bg-red-600 text-white font-black text-sm shadow-md print:bg-black">
                                    {{ $seat->seat_number }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block print:text-black">Quantity</span>
                        <span class="text-base font-black text-white print:text-black">
                            {{ $booking->bookedSeats->count() }} {{ $booking->bookedSeats->count() === 1 ? 'Ticket' : 'Tickets' }}
                        </span>
                    </div>
                </div>

                <!-- Bottom Bar: QR Code & Verification -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 print:border-black">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-white print:text-black">Scan for Instant Entry</p>
                        <p class="text-[11px] text-slate-400 max-w-xs print:text-black">
                            Keep this ticket handy upon arriving at the cinema gates. Valid for 1-time entrance.
                        </p>
                        <span class="text-[10px] font-mono text-slate-400 block print:text-black">
                            Issued on {{ $booking->created_at->format('M d, Y - h:i A') }}
                        </span>
                    </div>

                    <!-- Real QR Code Generator -->
                    <div class="p-2 rounded-xl bg-white border border-slate-700 shadow-md flex-shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&margin=0&data={{ urlencode(route('booking.ticket', $booking->booking_reference)) }}" 
                             alt="Ticket QR Code" 
                             class="w-20 h-20 sm:w-24 sm:h-24">
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Print Footer note -->
    <div class="mt-8 text-center text-xs text-slate-500 print:text-black print:mt-4">
        Thank you for choosing CineMax Cinema. Have an unforgettable movie experience!
    </div>

</div>

@push('styles')
<style>
    @media print {
        header, footer, #chatbot-container, .print\:hidden {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        #printable-ticket {
            box-shadow: none !important;
            border-color: #000000 !important;
            background-color: #ffffff !important;
            color: #000000 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    async function downloadTicketImage() {
        const ticketElement = document.getElementById('printable-ticket');
        const btn = document.getElementById('download-ticket-btn');
        const originalHtml = btn.innerHTML;

        // Set Loading State
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating Image...';

        try {
            const canvas = await html2canvas(ticketElement, {
                scale: 2, // High resolution retina capture
                useCORS: true, // Allows cross-origin images (Unsplash posters, QR server)
                allowTaint: false,
                backgroundColor: '#0f172a',
                logging: false
            });

            // Trigger file download
            const imageURL = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = imageURL;
            downloadLink.download = 'CineMax-Ticket-{{ $booking->booking_reference }}.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            // Success feedback
            btn.innerHTML = '<i class="fa-solid fa-check text-emerald-300"></i> Downloaded!';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }, 2500);

        } catch (err) {
            console.error('Failed to generate ticket image:', err);
            alert('Could not download ticket image. Please try again.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
</script>
@endpush

@endsection
