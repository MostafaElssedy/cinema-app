<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\BookedSeat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Show interactive seat selection page.
     */
    public function selectSeats($showtimeId)
    {
        $showtime = Showtime::with(['movie', 'theater', 'bookedSeats'])->findOrFail($showtimeId);
        $bookedSeats = $showtime->bookedSeats->pluck('seat_number')->toArray();

        $otherShowtimes = Showtime::where('movie_id', $showtime->movie_id)
            ->where('id', '!=', $showtime->id)
            ->whereDate('show_date', '>=', now()->toDateString())
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->take(4)
            ->get();

        return view('booking.seats', compact('showtime', 'bookedSeats', 'otherShowtimes'));
    }

    /**
     * Process booking and seat reservation.
     */
    public function book(BookingRequest $request, $showtimeId)
    {
        $showtime = Showtime::with(['movie', 'theater'])->findOrFail($showtimeId);

        $validated = $request->validated();

        // Parse seats array
        $seats = array_filter(array_map('trim', explode(',', $validated['seats'])));

        try {
            $booking = DB::transaction(function () use ($showtime, $seats, $validated) {
                // Check if any seat is already booked
                $alreadyBooked = BookedSeat::where('showtime_id', $showtime->id)
                    ->whereIn('seat_number', $seats)
                    ->lockForUpdate()
                    ->pluck('seat_number')
                    ->toArray();

                if (!empty($alreadyBooked)) {
                    throw new \Exception('Seats ' . implode(', ', $alreadyBooked) . ' are already booked. Please choose other seats.');
                }

                $totalPrice = count($seats) * $showtime->price;
                $reference = 'CNX-' . strtoupper(Str::random(6));

                $newBooking = Booking::create([
                    'booking_reference' => $reference,
                    'showtime_id' => $showtime->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'total_price' => $totalPrice,
                    'status' => 'confirmed',
                ]);

                foreach ($seats as $seat) {
                    BookedSeat::create([
                        'booking_id' => $newBooking->id,
                        'showtime_id' => $showtime->id,
                        'seat_number' => $seat,
                    ]);
                }

                return $newBooking;
            });

            return redirect()->route('booking.ticket', $booking->booking_reference)
                ->with('success', 'Your booking has been successfully confirmed!');

        } catch (\Exception $e) {
            return back()->withErrors(['booking_error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Show digital e-ticket page.
     */
    public function ticket($reference)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.theater', 'bookedSeats'])
            ->where('booking_reference', $reference)
            ->firstOrFail();

        return view('booking.ticket', compact('booking'));
    }

    /**
     * API for fetching booked seats dynamically.
     */
    public function getBookedSeats($showtimeId)
    {
        $bookedSeats = BookedSeat::where('showtime_id', $showtimeId)->pluck('seat_number');
        return response()->json([
            'success' => true,
            'booked_seats' => $bookedSeats,
        ]);
    }
}
