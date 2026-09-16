<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Theater;
use App\Models\Booking;
use App\Models\BookedSeat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected Movie $movie;
    protected Theater $theater;
    protected Showtime $showtime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->movie = Movie::create([
            'title' => 'Inception',
            'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology.',
            'release_year' => 2010,
            'rating' => 8.8,
            'image' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800',
        ]);

        $this->theater = Theater::create([
            'name' => 'Hall 1 - IMAX Grand',
            'type' => 'IMAX 3D',
            'rows_count' => 6,
            'seats_per_row' => 10,
        ]);

        $this->showtime = Showtime::create([
            'movie_id' => $this->movie->id,
            'theater_id' => $this->theater->id,
            'show_date' => now()->toDateString(),
            'start_time' => '08:00 PM',
            'price' => 150.00,
        ]);
    }

    public function test_movie_details_page_loads_with_showtimes()
    {
        $response = $this->get(route('movies.show', $this->movie->id));
        $response->assertStatus(200);
        $response->assertSee('Select Showtime');
        $response->assertSee('08:00 PM');
    }

    public function test_seat_selection_page_loads_successfully()
    {
        $response = $this->get(route('booking.seats', $this->showtime->id));
        $response->assertStatus(200);
        $response->assertSee('Select Your Seats');
        $response->assertSee('CINEMA SCREEN');
        $response->assertSee('150 EGP');
    }

    public function test_user_can_book_seats_and_see_ticket()
    {
        // Book seats D1, D2
        $response = $this->post(route('booking.store', $this->showtime->id), [
            'seats' => 'D1, D2',
            'customer_name' => 'Sara Ahmed',
            'customer_email' => 'sara@example.com',
            'customer_phone' => '01122334455',
        ]);

        $booking = Booking::where('customer_email', 'sara@example.com')->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->bookedSeats()->count());
        $this->assertEquals(300.00, $booking->total_price);
        $this->assertEquals('confirmed', $booking->status);

        $response->assertRedirect(route('booking.ticket', $booking->booking_reference));

        // Follow redirect to ticket
        $ticketResponse = $this->get(route('booking.ticket', $booking->booking_reference));
        $ticketResponse->assertStatus(200);
        $ticketResponse->assertSee($booking->booking_reference);
        $ticketResponse->assertSee('Sara Ahmed');
        $ticketResponse->assertSee('D1');
        $ticketResponse->assertSee('D2');
        $ticketResponse->assertSee('300 EGP');
    }

    public function test_double_booking_same_seat_fails()
    {
        // First customer books E1
        $this->post(route('booking.store', $this->showtime->id), [
            'seats' => 'E1',
            'customer_name' => 'First Customer',
            'customer_email' => 'first@example.com',
            'customer_phone' => '01000000001',
        ]);

        // Second customer tries to book E1 again
        $response = $this->post(route('booking.store', $this->showtime->id), [
            'seats' => 'E1',
            'customer_name' => 'Second Customer',
            'customer_email' => 'second@example.com',
            'customer_phone' => '01000000002',
        ]);

        $response->assertSessionHasErrors('booking_error');
    }

    public function test_booking_request_validation_fails_on_missing_fields()
    {
        $response = $this->post(route('booking.store', $this->showtime->id), [
            'seats' => '',
            'customer_name' => '',
            'customer_email' => 'invalid-email',
            'customer_phone' => '',
        ]);

        $response->assertSessionHasErrors(['seats', 'customer_name', 'customer_email', 'customer_phone']);
    }

    public function test_booking_request_validation_fails_if_more_than_8_seats()
    {
        $response = $this->post(route('booking.store', $this->showtime->id), [
            'seats' => 'A1,A2,A3,A4,A5,A6,A7,A8,A9',
            'customer_name' => 'Max Buyer',
            'customer_email' => 'max@example.com',
            'customer_phone' => '01000000009',
        ]);

        $response->assertSessionHasErrors('seats');
    }
}
