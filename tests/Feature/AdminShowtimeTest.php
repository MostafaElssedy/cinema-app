<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Theater;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminShowtimeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Movie $movie;
    protected Theater $theater;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@cinema.com',
            'name' => 'Cinema Admin',
        ]);

        $this->movie = Movie::create([
            'title' => 'Interstellar',
            'description' => 'A team of explorers travel through a wormhole in space.',
            'release_year' => 2014,
            'rating' => 8.7,
            'image' => 'https://example.com/interstellar.jpg',
        ]);

        $this->theater = Theater::create([
            'name' => 'Hall 1 - IMAX Grand',
            'type' => 'IMAX 3D',
            'rows_count' => 6,
            'seats_per_row' => 10,
        ]);
    }

    public function test_guest_cannot_access_admin_showtimes_or_theaters(): void
    {
        $response = $this->get(route('admin.showtimes.index'));
        $response->assertRedirect(route('admin.login'));

        $responseTheater = $this->get(route('admin.theaters.index'));
        $responseTheater->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_showtimes_and_theaters_pages(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.showtimes.index'));
        $response->assertStatus(200);
        $response->assertSee('Showtimes Schedule');

        $responseTheaters = $this->actingAs($this->admin)->get(route('admin.theaters.index'));
        $responseTheaters->assertStatus(200);
        $responseTheaters->assertSee('Hall 1 - IMAX Grand');
    }

    public function test_admin_can_create_a_theater(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.theaters.store'), [
            'name' => 'Hall 4 - 4DX Dynamic',
            'type' => '4DX Motion',
            'rows_count' => 5,
            'seats_per_row' => 8,
        ]);

        $response->assertRedirect(route('admin.theaters.index'));
        $this->assertDatabaseHas('theaters', [
            'name' => 'Hall 4 - 4DX Dynamic',
            'type' => '4DX Motion',
            'rows_count' => 5,
            'seats_per_row' => 8,
        ]);
    }

    public function test_admin_can_schedule_a_new_showtime(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.showtimes.store'), [
            'movie_id' => $this->movie->id,
            'theater_id' => $this->theater->id,
            'show_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:30 PM',
            'price' => 175.00,
        ]);

        $response->assertRedirect(route('admin.showtimes.index'));
        $this->assertDatabaseHas('showtimes', [
            'movie_id' => $this->movie->id,
            'theater_id' => $this->theater->id,
            'start_time' => '09:30 PM',
            'price' => 175.00,
        ]);
    }

    public function test_showtime_validation_fails_on_missing_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.showtimes.store'), [
            'movie_id' => '',
            'theater_id' => '',
            'show_date' => '',
            'start_time' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors(['movie_id', 'theater_id', 'show_date', 'start_time', 'price']);
    }

    public function test_cannot_delete_showtime_with_active_bookings(): void
    {
        $showtime = Showtime::create([
            'movie_id' => $this->movie->id,
            'theater_id' => $this->theater->id,
            'show_date' => now()->toDateString(),
            'start_time' => '07:00 PM',
            'price' => 150.00,
        ]);

        Booking::create([
            'booking_reference' => 'CNX-TEST99',
            'showtime_id' => $showtime->id,
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '01011112222',
            'total_price' => 150.00,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.showtimes.destroy', $showtime->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('showtimes', ['id' => $showtime->id]);
    }

    public function test_can_delete_unbooked_showtime(): void
    {
        $showtime = Showtime::create([
            'movie_id' => $this->movie->id,
            'theater_id' => $this->theater->id,
            'show_date' => now()->toDateString(),
            'start_time' => '11:00 PM',
            'price' => 150.00,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.showtimes.destroy', $showtime->id));
        $response->assertRedirect(route('admin.showtimes.index'));
        $this->assertDatabaseMissing('showtimes', ['id' => $showtime->id]);
    }
}
